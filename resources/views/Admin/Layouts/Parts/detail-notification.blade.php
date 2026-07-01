<div class="modal fade" id="notifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Notification Detail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h6 id="modalTitle"></h6>

                <div class='row'>
                    <div class='col-3'>
                        <p id="modalAvatar"></p>
                    </div>
                    <div class='col-9'>
                        <p id="modalMessage"></p>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <div class="modal-footer">
                    <a href="#" id="modalActionBtn" class="btn btn-primary d-none">
                        Open
                    </a>

                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="{{ asset('assets/custom-js/notif-detail.js') }}"></script>
