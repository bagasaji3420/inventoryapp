@extends('Admin.Layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="nav-align-top">
            @include('Admin.User.menu')
        </div>
        <!-- Change Password -->

        <livewire:user.change-password />
        <!--/ Change Password -->

        <!-- Two-steps verification -->
        <livewire:user.two-factor-toggle />


        <!-- Recent Devices -->
        <div class="card my-6">
            <h5 class="card-header">Recent Devices</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-truncate">Browser</th>
                            <th class="text-truncate">Device</th>
                            <th class="text-truncate">Location</th>
                            <th class="text-truncate">Recent Activitiy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sessions as $session)
                            <tr>
                                <td class="text-truncate text-heading fw-medium">
                                    <i class="icon-base bx bx-globe icon-md align-top text-info me-4"></i>
                                    {{ $session->browser }} on {{ $session->os }}
                                </td>

                                <td class="text-truncate">
                                    {{ $session->os }}
                                </td>

                                <td class="text-truncate">
                                    {{ $session->ip }}
                                </td>

                                <td class="text-truncate">
                                    <div class="d-flex justify-content-between align-items-center">

                                        <span>{{ $session->last_activity }}</span>

                                        <form action="{{ route('session.destroy', $session->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm text-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Recent Devices -->
    </div>
@endsection
