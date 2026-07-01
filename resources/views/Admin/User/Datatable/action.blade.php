@php
    $roles = $user->roles->pluck('name')->toJson();
    $canDelete = Auth::user()->can('users.delete');
    $canEdit = Auth::user()->can('users.update');
@endphp

<div class="d-flex gap-2 align-items-center">

    {{-- EDIT --}}
    @if ($user->roles->contains(fn($r) => $r->is_editable))
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal"
            @if (!$canEdit) disabled @endif
            onclick="setUserEdit(
                {{ $user->id }},
                {{ $roles }},
                '{{ $user->status }}',
                '{{ $user->suspended_until }}',
                '{{ e($user->status_reason) }}'
            )">

            <i class="bx bx-shield"></i>
        </button>
    @else
        <button class="btn btn-secondary btn-sm" disabled>
            <i class="bx bx-block"></i>

        </button>
    @endif

    {{-- DELETE --}}
    @if ($user->roles->contains(fn($r) => $r->is_protected))
        <button class="btn btn-secondary btn-sm" disabled>
            <i class="bx bx-block"></i>

        </button>
    @else
        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm" @if (!$canDelete) disabled @endif>
                <i class="bx bx-trash"></i>
            </button>
        </form>
    @endif

</div>
