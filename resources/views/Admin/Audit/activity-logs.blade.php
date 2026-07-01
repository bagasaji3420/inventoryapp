@extends('Admin.Layouts.app')
@section('content')
    <div class="card">
        <div class='card-body'>
            <table id="logTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Event</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
            </table>

            <div class="modal fade" id="logModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Detail Changes</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div id="logContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.Layouts.Parts.datatables')

    <script>
        $(document).ready(function() {
            $('#logTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                },
                ajax: '{{ route('audit.log.activity') }}',
                columns: [{
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'event_badge',
                        name: 'event',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'created_at'
                    },
                ]
            });
        });
    </script>
    <script src="../../assets/custom-js/logs.js"></script>
@endsection
