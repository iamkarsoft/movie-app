@component('mail::message')
# Released Today
<ul>

@forelse ($releases as $release)
<li>{{$release->name}}</li>
@empty
<p>No Releases for you today!</p>
@endforelse
</ul>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
