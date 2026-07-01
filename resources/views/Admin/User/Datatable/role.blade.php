@foreach ($user->roles as $role)
    <span class="badge bg-label-primary">
        {{ $role->icon }} {{ $role->name }}
    </span>
@endforeach
