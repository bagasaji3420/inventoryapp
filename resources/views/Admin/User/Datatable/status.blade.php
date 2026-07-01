@switch($user->status)
    @case('active')
        <span class="badge bg-success">Active</span>
        @break

    @case('suspend')
        <span class="badge bg-warning">Suspend</span>
        @break

    @default
        <span class="badge bg-danger">Banned</span>
@endswitch