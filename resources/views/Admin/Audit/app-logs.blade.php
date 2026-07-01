@extends('Admin.Layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="appLogTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="logModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Log Detail</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre id="logDetail" style="white-space: pre-wrap;"></pre>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.Layouts.datatables')

    <script>
        $(document).ready(function() {
            $('#appLogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('audit.log.app') }}',
                columns: [{
                        data: 'level_badge',
                        name: 'level',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'message_short',
                        name: 'message'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>

    <script>
        function showDetail(message) {
            document.getElementById('logDetail').textContent = message;
        }
    </script>
@endsection
