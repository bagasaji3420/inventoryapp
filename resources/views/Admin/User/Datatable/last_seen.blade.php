@if (isOnline($user->id))
    <span class="text-success">● Online</span>
@else
    <span class="text-muted">
        {{ $user->last_seen?->diffForHumans() ?? 'Offline' }}
    </span>
@endif