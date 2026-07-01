@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Roles List</h4>

    <p class="mb-6">
        A role provided access to predefined menus and features so that depending on assigned role an
        administrator can have access to what user needs.
    </p>
    <div class="row g-4">
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-6 col-5">
                        <div class="d-flex align-items-center h-100 justify-content-center mt-sm-0 ps-6">
                            <img src="../../assets/img/illustrations/role.png" class="img-fluid" alt="Image" width="120"
                                data-app-light-img="illustrations/role.png" data-app-dark-img="illustrations/role.png" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role"
                                @cannot('roles.create') disabled @endcannot>
                                Add New Role
                            </button>
                            <p class="mb-0">
                                Add new role, <br />
                                if it doesn't exist.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">

                        <!-- TOP -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-normal mb-0 text-body">
                                Total {{ $role->users->count() }} users
                            </h6>

                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">

                                @foreach ($role->users->take(3) as $user)
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="{{ $user->name }}" class="avatar pull-up">
                                        <img class="rounded-circle" src="{{ $user->avatar_url }}">
                                    </li>
                                @endforeach

                                @if ($role->users->count() > 3)
                                    <li class="avatar">
                                        <span class="avatar-initial rounded-circle">
                                            +{{ shortNumber($role->users->count() - 3) }}
                                        </span>
                                    </li>
                                @endif

                                @if ($role->users->count() < 1)
                                    <li class="avatar">
                                        <span class="avatar-initial rounded-circle">
                                            0
                                        </span>
                                    </li>
                                @endif

                            </ul>
                        </div>

                        <!-- BOTTOM -->
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <h5 class="mb-1">{{ ucfirst($role->name) . ' ' . $role->icon }}</h5>

                                <a @cannot('roles.update') style="pointer-events: none; display: none;" @endcannot
                                    href="javascript:;" data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                    onclick='setEdit(
                                        {{ $role->id }},
                                        @json($role->name),
                                        @json($role->permissions->pluck('name')),
                                        @json($role->icon),
                                        @json((bool) $role->is_assignable),
                                        @json((bool) $role->is_protected),
                                        @json((bool) $role->is_editable)
                                    )'>
                                    Edit Role
                                </a>
                            </div>

                            @if (in_array($role->name, ['owner', 'developer']))
                            @else
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button @cannot('roles.delete') disabled @endcannot type="submit"
                                        class="btn p-0 border-0 bg-transparent">
                                        <i class="bx bx-trash text-secondary fs-5"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- DELETE ICON -->


                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="addRoleModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('roles.store') }}" class="modal-content">
                @csrf
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h3 class="text=center">Add Role</h3>

                    <input type="text" name="name" class="form-control mb-3" placeholder="Role name">

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="addIsAssignable" name="is_assignable"
                                    value="1">
                                <label class="form-check-label" for="addIsAssignable">
                                    Assignable
                                </label>
                            </div>

                        </div>

                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="addIsProtected" name="is_protected"
                                    value="1">
                                <label class="form-check-label" for="addIsProtected">
                                    Protected
                                </label>
                            </div>

                        </div>

                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input permission-item" type="checkbox" id="addIsEditable"
                                    name="is_editable" value="1">
                                <label class="form-check-label" for="addIsEditable">
                                    Editable
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="position-relative mb-3 w-100">

                        <input type="text" name="icon" id="emojiInput" class="form-control" placeholder="Choose Icon"
                            readonly>



                        <!-- Dropdown emoji -->
                        <div id="emojiDropdown" class="border rounded shadow bg-white p-2 position-absolute w-100"
                            style="display: none; z-index: 999;">

                            <emoji-picker style="width:100%;"></emoji-picker>
                        </div>
                    </div>

                    <h5 class="mb-6">Role Permissions</h5>
                    <div class="table-responsive">
                        <table class="table table-flush-spacing mb-0 border-top">
                            <tbody>
                                @foreach ($permissions as $module => $perms)
                                    <tr>
                                        <td class="text-nowrap fw-medium text-heading">
                                            {{ ucfirst($module) }}
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-end">

                                                @php
                                                    $readOnlyModules = ['dashboard', 'finance'];
                                                    $actions = in_array($module, $readOnlyModules) ? ['read'] : ['read', 'create', 'update', 'delete'];
                                                @endphp

                                                @foreach ($actions as $action)
                                                    @php
                                                        $fullName = $module . '.' . $action;
                                                        $exists = $perms->contains('name', $fullName);
                                                    @endphp

                                                    <div class="form-check mb-0 me-4">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]" value="{{ $fullName }}"
                                                            {{ $exists ? '' : 'disabled' }}>
                                                        <label class="form-check-label">
                                                            {{ ucfirst($action) }}
                                                        </label>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary mt-3">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="editRoleModal">
        <div class="modal-dialog">
            <form method="POST" id="editForm" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <h5>Edit Role</h5>

                    <input type="text" name="name" id="editName" class="form-control mb-3">

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input permission-item" type="checkbox" id="editIsAssignable"
                                    name="is_assignable" value="1">
                                <label class="form-check-label" for="editIsAssignable">
                                    Assignable
                                </label>
                            </div>

                        </div>

                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input permission-item" type="checkbox" id="editIsProtected"
                                    name="is_protected" value="1">
                                <label class="form-check-label" for="editIsProtected">
                                    Protected
                                </label>
                            </div>

                        </div>

                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input permission-item" type="checkbox" id="editIsEditable"
                                    name="is_editable" value="1">
                                <label class="form-check-label" for="editIsEditable">
                                    Editable
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="position-relative mb-3">
                        <label>Icon</label>

                        <input type="text" name="icon" id="editEmojiInput" class="form-control" readonly>

                        <div id="editEmojiDropdown" class="border rounded shadow bg-white p-2 position-absolute w-100"
                            style="display: none; z-index: 999;">

                            <emoji-picker></emoji-picker>
                        </div>
                    </div>

                    <h5 class="mb-6">Role Permissions</h5>
                    <div class="table-responsive">
                        <table class="table table-flush-spacing mb-0 border-top">
                            <tbody>
                                @foreach ($permissions as $module => $perms)
                                    <tr>
                                        <td class="text-nowrap fw-medium text-heading">
                                            {{ ucfirst($module) }}
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-end">

                                                @php
                                                    $readOnlyModules = ['dashboard', 'finance'];
                                                    $actions = in_array($module, $readOnlyModules) ? ['read'] : ['read', 'create', 'update', 'delete'];
                                                @endphp

                                                @foreach ($actions as $action)
                                                    @php
                                                        $fullName = $module . '.' . $action;
                                                        $exists = $perms->contains('name', $fullName);
                                                    @endphp

                                                    <div class="form-check mb-0 me-4">
                                                        <input class="form-check-input permission-item" type="checkbox"
                                                            name="permissions[]" value="{{ $fullName }}"
                                                            data-permission="{{ $fullName }}"
                                                            {{ $exists ? '' : 'disabled' }}>
                                                        <label class="form-check-label">
                                                            {{ ucfirst($action) }}
                                                        </label>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/custom-js/role.js"></script>
@endsection
