<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DevToolsController extends Controller
{
    private function pidFile(): string
    {
        return storage_path('app/queue-worker.pid');
    }

    private function logFile(): string
    {
        return storage_path('logs/queue-worker.log');
    }

    private function phpBinary(): string
    {
        $bin = PHP_BINARY;
        if (str_ends_with($bin, '-fpm')) {
            $cli = substr($bin, 0, -4);
            if (is_executable($cli)) {
                return $cli;
            }
        }
        exec('which php 2>/dev/null', $out, $code);
        if ($code === 0 && ! empty($out[0]) && is_executable(trim($out[0]))) {
            return trim($out[0]);
        }

        return 'php';
    }

    private function workerRunning(): bool
    {
        $pidFile = $this->pidFile();
        if (file_exists($pidFile)) {
            $pid = (int) file_get_contents($pidFile);
            if ($pid > 0) {
                if (function_exists('posix_kill')) {
                    $alive = posix_kill($pid, 0);
                } else {
                    exec("ps -p {$pid}", $out, $code);
                    $alive = $code === 0;
                }
                if ($alive) {
                    return true;
                }
                @unlink($pidFile);
            }
        }
        exec('pgrep -f '.escapeshellarg('queue:work.*default'), $pids);
        if (! empty($pids)) {
            file_put_contents($pidFile, (int) trim($pids[0]));

            return true;
        }

        return false;
    }

    private function recentActivity(): array
    {
        $logFile = $this->logFile();
        if (! file_exists($logFile) || filesize($logFile) === 0) {
            return [];
        }

        $lines = array_reverse(array_slice(
            file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -100
        ));

        $jobLabels = [
            'RunArtisanCommand' => 'Artisan Command',
        ];

        $activity = [];
        foreach ($lines as $line) {
            if (! preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\s+\S+\\\\(\w+)\s+.*?\s+(RUNNING|DONE|FAIL(?:ED)?)/i', $line, $m)) {
                continue;
            }
            $activity[] = [
                'time'   => $m[1],
                'job'    => $jobLabels[$m[2]] ?? $m[2],
                'status' => strtolower($m[3]) === 'running' ? 'running'
                          : (str_starts_with(strtolower($m[3]), 'fail') ? 'failed' : 'done'),
            ];
            if (count($activity) >= 10) {
                break;
            }
        }

        return $activity;
    }

    public function status(): JsonResponse
    {
        $running = $this->workerRunning();

        try {
            $jobRows = DB::table('jobs')
                ->where('queue', 'default')
                ->orderBy('created_at')
                ->get(['id', 'payload', 'reserved_at', 'created_at']);

            $pending = $jobRows->count();
            $processing = $jobRows->whereNotNull('reserved_at')->count();

            $pendingJobs = $jobRows->map(fn ($job) => [
                'id'     => $job->id,
                'job'    => class_basename(json_decode($job->payload, true)['displayName'] ?? 'Unknown'),
                'status' => $job->reserved_at ? 'processing' : 'waiting',
            ])->values();

            $failedCols = DB::getSchemaBuilder()->getColumnListing('failed_jobs');
            $idCol = in_array('uuid', $failedCols) ? 'uuid' : 'id';
            $failed = DB::table('failed_jobs')
                ->orderByDesc('failed_at')->limit(5)
                ->get([$idCol, 'payload', 'exception', 'failed_at'])
                ->map(fn ($j) => [
                    'uuid'      => $j->$idCol,
                    'job'       => class_basename(json_decode($j->payload, true)['displayName'] ?? 'Unknown'),
                    'error'     => str($j->exception)->before("\n")->toString(),
                    'failed_at' => $j->failed_at,
                ]);
        } catch (\Throwable) {
            $pending = $processing = 0;
            $pendingJobs = $failed = collect();
        }

        return response()->json([
            'running'     => $running,
            'pending'     => $pending,
            'processing'  => $processing,
            'pendingJobs' => $pendingJobs,
            'failed'      => $failed,
            'activity'    => $this->recentActivity(),
        ]);
    }

    public function start(): JsonResponse
    {
        if ($this->workerRunning()) {
            return response()->json(['message' => 'Worker already running.']);
        }

        $php = $this->phpBinary();
        $artisan = base_path('artisan');
        $log = $this->logFile();
        $pidFile = $this->pidFile();

        $cmd = sprintf(
            'nohup %s %s queue:work database --queue=default --stop-when-empty --timeout=600 --tries=1 --sleep=2 >> %s 2>&1 &',
            escapeshellarg($php),
            escapeshellarg($artisan),
            escapeshellarg($log)
        );

        exec($cmd);
        usleep(500_000);

        exec('pgrep -f '.escapeshellarg('queue:work.*default'), $pids);
        $pid = isset($pids[0]) ? (int) trim($pids[0]) : 0;
        if ($pid > 0) {
            file_put_contents($pidFile, $pid);
        }

        return response()->json(['message' => 'Worker started.']);
    }

    public function stop(): JsonResponse
    {
        $pidFile = $this->pidFile();

        if (! file_exists($pidFile)) {
            return response()->json(['message' => 'No worker running.']);
        }

        $pid = (int) file_get_contents($pidFile);
        if ($pid) {
            if (function_exists('posix_kill')) {
                posix_kill($pid, SIGTERM);
            } else {
                exec("kill {$pid} 2>/dev/null");
            }
        }

        @unlink($pidFile);

        return response()->json(['message' => 'Worker stopped.']);
    }

    public function clearLog(): JsonResponse
    {
        $log = $this->logFile();
        if (file_exists($log)) {
            file_put_contents($log, '');
        }

        return response()->json(['message' => 'Log cleared.']);
    }

    public function clearQueue(): JsonResponse
    {
        \Illuminate\Support\Facades\Artisan::call('queue:clear', ['--queue' => 'default', '--force' => true]);

        return response()->json(['message' => 'Queue cleared.']);
    }

    public function cancelJob(int $id): JsonResponse
    {
        DB::table('jobs')->where('id', $id)->where('queue', 'default')->whereNull('reserved_at')->delete();

        return response()->json(['message' => 'Job cancelled.']);
    }
}
