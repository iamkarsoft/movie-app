<?php

	namespace App\Contracts;

	interface MovieApiConnection
	{
        public static function connect(string $token, string $url, string $type);
	}
