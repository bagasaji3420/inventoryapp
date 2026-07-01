<div class="d-flex align-items-center gap-2">
    <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width:35px;height:35px;object-fit:cover">

    <div>
        <div>{{ $user->first_name }} {{ $user->last_name }}</div>
        <small class="text-muted">{{ $user->email }}</small>
    </div>
</div>
