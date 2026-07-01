@extends('Admin.Layouts.app')
@section('content')
    <!-- Permission Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <button @cannot('permissions.create') disabled @endcannot class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                    Add Permission
                </button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="border-top">
                        <tr>
                            <th>Name</th>
                            <th>Assigned To</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    @if ($permission->roles->count())
                                        @foreach ($permission->roles as $role)
                                            <span class="badge bg-label-primary">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No role</span>
                                    @endif
                                </td>
                                <td>{{ $permission->created_at->format('d M Y') }}</td>
                                <td>
                                    <!-- Edit -->
                                    <button @cannot('permissions.update') disabled @endcannot class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editPermissionModal"
                                        onclick="setEdit({{ $permission->id }}, '{{ Str::before($permission->name, '.') }}')">
                                        Edit
                                    </button>

                                    <!-- Delete -->
                                    <form  action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button @cannot('permissions.delete') disabled @endcannot class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Permission Table -->

    <!-- Modal -->
    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Add New Permission</h4>
                        <p>Permissions you may use and assign to your users.</p>
                    </div>
                    <form id="addPermissionForm" action="{{ route('permissions.store') }}" class="row" method="POST">
                        @csrf
                        <div class="col-12 form-control-validation mb-4">
                            <label class="form-label" for="modalPermissionName">Permission Name</label>
                            <input type="text" id="modalPermissionName" name="name" class="form-control"
                                placeholder="Permission Name" autofocus />
                        </div>
                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-4 me-1">Create Permission</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Discard
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Permission Modal -->

    <!-- Edit Permission Modal -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Edit Permission</h4>
                        <p>Edit permission as per your requirements.</p>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <span>
                            <span class="alert-heading mb-1 h5">Warning</span><br />
                            <span class="mb-0 p">By editing the permission name, you might break the system permissions
                                functionality.
                                Please ensure you're absolutely certain before proceeding.</span>
                        </span>
                    </div>
                    <form id="editForm" class="row pt-2 row-gap-2 gx-4" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-sm-9 form-control-validation">
                            <label class="form-label" for="editPermissionName">Permission Name</label>
                            <input type="text" id="editPermissionName" name="name" class="form-control"
                                placeholder="Permission Name" tabindex="-1" />
                        </div>
                        <div class="col-sm-3 mb-4">
                            <label class="form-label invisible d-none d-sm-inline-block">Button</label>
                            <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setEdit(id, name) {
            const form = document.getElementById('editForm');
            form.action = '/permissions/' + id;

            document.getElementById('editPermissionName').value = name;
        }
    </script>


    <!--/ Edit Permission Modal -->
@endsection
