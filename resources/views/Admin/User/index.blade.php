@extends('Admin.Layouts.app')

@section('content')
    <div class="row g-6 mb-6">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100  pt-4">
                    <div class="col-sm-6 col-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0  ps-6">
                            <img src="../../assets/img/illustrations/man-role.png" class="img-fluid" alt="Image"
                                width="120" data-app-light-img="illustrations/man-role.png"
                                data-app-dark-img="illustrations/man-role.png" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">

                            <button data-bs-toggle="modal" data-bs-target="#createUserModal"
                                class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role"
                                @cannot('users.create') disabled @endcannot>
                                Add User
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        {{-- ✅ ACTIVE --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Active Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ number_format($activeUsers) }}</h4>
                            </div>
                            <small class="mb-0">Users currently active</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-user-check icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ⚠️ SUSPEND --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Suspended Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ number_format($suspendUsers) }}</h4>
                            </div>
                            <small class="mb-0">Temporarily restricted</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-user-x icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ❌ BANNED --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Banned Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ number_format($bannedUsers) }}</h4>
                            </div>
                            <small class="mb-0">Permanently blocked</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-block icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-body">
            {{-- 🔥 TABLE --}}

            <table id="userTable" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Seen</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>




    {{-- 🔥 CREATE MODAL --}}
    <div class="modal fade" id="createUserModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.store') }}" class="modal-content">
                @csrf

                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="text-center">Add User</h4>

                    <div class="row">

                        <div class="col-6">
                            <label for="">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}"
                                class="form-control mb-1 @error('first_name') is-invalid @enderror"
                                placeholder="First Name">

                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="">Last Name</label>

                            <input type="text" name="last_name" value="{{ old('last_name') }}"
                                class="form-control mb-1 @error('last_name') is-invalid @enderror" placeholder="Last Name">

                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="">Userame</label>

                            <input type="text" name="username" value="{{ old('username') }}"
                                class="form-control mb-1 @error('username') is-invalid @enderror" placeholder="Username">

                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control mb-1 @error('email') is-invalid @enderror" placeholder="Email">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="col-12 mb-4">
                        <label for="">Password</label>

                        <div class="input-group input-group-merge has-validation">
                            <input class="form-control" type="password" name="password" id="password"
                                placeholder="············">
                            <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-show"></i></span>
                        </div>
                    </div>





                    <div class="mb-3">
                        <label>Roles</label>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach (\Spatie\Permission\Models\Role::where('is_assignable', true)->get() as $role)
                                <label class="badge bg-label-primary p-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="me-1">
                                    {{ $role->icon }} {{ ucfirst($role->name) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>


    {{-- 🔥 STATUS MODAL --}}
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog">
            <form method="POST" id="statusForm" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <h4 class="text-center">Update</h4>

                    <label>Roles</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\Spatie\Permission\Models\Role::where('is_assignable', true)->get() as $role)
                            <label class="badge bg-label-primary p-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    class="me-1 role-checkbox">
                                {{ $role->icon }} {{ ucfirst($role->name) }}
                            </label>
                        @endforeach
                    </div>

                    <hr>

                    <div class="mt-4">
                        <label>Account Status</label>
                        <select name="status" id="statusSelect" class="form-control mb-2">
                            <option value="active">Active</option>
                            <option value="suspend">Suspend</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                    <div class="mt-4" style="display:none;" id="suspendTime">
                        <label style="display:none;" id="labelSuspendTime">Suspend Time</label>
                        <input type="datetime-local" name="suspended_until" class="form-control">
                    </div>

                    <div style="display:none;" id="Reason" class="mt-4">
                        <label for="status_reason" class="form-label">Reason</label>

                        <textarea name="status_reason" id="status_reason" rows="3" class="form-control"
                            placeholder="Enter reason..."></textarea>

                        @error('status_reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class='text-center'>
                        <button class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('Admin.Layouts.Parts.datatables')

    <script>
        $("#userTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.data') }}",
            search: {
                smart: false // tambah ini
            },


            responsive: true,
            deferRender: true,
            lengthChange: false,
            language: {
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            },

            columns: [{
                    data: "user",
                    name: "user",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "role",
                    name: "role",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "status",
                    name: "status",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "last_seen",
                    name: "last_seen",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "created_at",
                    name: "created_at"
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false
                },
            ],
        });

        // 🔥 LISTEN EVENT REALTIME
        Echo.channel('users')
            .listen('UserUnsuspended', (e) => {
                console.log('Reload table karena unsuspend:', e.userId);

                table.ajax.reload(null, false); // ga reset pagination
            });
    </script>

    <script src="../../assets/custom-js/user.js"></script>
@endsection
