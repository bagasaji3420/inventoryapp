{{-- ============================================================
     Home Navbar: Notification Dropdown
     ============================================================ --}}

@auth
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
    @endphp

    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2">

        {{-- Bell Icon Trigger --}}
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" aria-expanded="false">
            <span class="position-relative">
                <i class="icon-base bx bx-bell icon-lg"></i>
                <span class="badge rounded-pill bg-danger badge-dot badge-notifications border" id="homeNotifBadge"
                    style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
                </span>
            </span>
        </a>

        {{-- Dropdown Menu --}}
        <ul class="dropdown-menu dropdown-menu-end p-0" style="min-width: 340px; ">

            {{-- ── Header ── --}}
            <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center px-4 py-3">
                    <h6 class="mb-0 me-auto fw-semibold">Notifications</h6>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-primary rounded-pill px-2" id="homeNotifCount">
                            {{ $unreadCount }} New
                        </span>
                        @if ($unreadCount > 0)
                            <form action="{{ route('notifications.readAll') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent text-muted"
                                    title="Mark all as read">
                                    <i class="icon-base bx bx-envelope-open fs-5"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </li>

            {{-- ── Notification List ── --}}
            <li class="dropdown-notifications-list scrollable-container" style="max-height: 300px; overflow-y: auto;">
                <ul class="list-group list-group-flush" id="homeNotifList">

                    @forelse (auth()->user()->unreadNotifications()->limit(5)->get() as $notif)
                        <li class="list-group-item list-group-item-action dropdown-notifications-item notif-item px-4 py-3"
                            style="cursor: pointer;" data-id="{{ $notif->id }}"
                            data-url="{{ $notif->data['url'] ?? '' }}" data-title="{{ $notif->data['title'] ?? '' }}"
                            data-message="{{ $notif->data['message'] ?? '' }}"
                            data-avatar="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                            data-type="{{ $notif->data['type'] ?? '' }}" data-icon="{{ $notif->data['icon'] ?? 'bx-bell' }}"
                            data-color="{{ $notif->data['color'] ?? 'primary' }}">

                            <div class="d-flex align-items-start gap-3">

                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm">
                                        @if (in_array($notif->data['type'] ?? null, ['comment', 'mention']))
                                            <img src="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                                                class="rounded-circle" width="36" height="36"
                                                style="object-fit: cover;">
                                        @else
                                            <span
                                                class="avatar-initial rounded-circle bg-label-{{ $notif->data['color'] ?? 'primary' }}">
                                                <i class="icon-base bx {{ $notif->data['icon'] ?? 'bx-bell' }}"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="small fw-semibold mb-1 text-truncate">
                                        {{ $notif->data['title'] ?? 'Notification' }}
                                    </h6>
                                    @if (!empty($notif->data['message']))
                                        <p class="text-muted mb-1 text-truncate"
                                            style="font-size: 0.78rem; line-height: 1.4;">
                                            {{ Str::limit($notif->data['message'], 60) }}
                                        </p>
                                    @endif
                                    <small class="text-body-secondary">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                {{-- Unread Dot --}}
                                <span class="badge rounded-pill bg-primary flex-shrink-0 mt-1 p-1"
                                    style="width: 8px; height: 8px;">
                                </span>

                            </div>
                        </li>
                    @empty
                        <li class="d-flex flex-column align-items-center justify-content-center py-4 text-muted">
                            <i class="bx bx-bell-off mb-1 fs-4 opacity-25"></i>
                            <small>No new notifications</small>
                        </li>
                    @endforelse

                </ul>
            </li>

            {{-- Footer --}}

            <li class="border-top">
                <div class="d-grid p-3">
                    <a href="{{ route('notif.index') }}" class="btn btn-sm btn-outline-primary">
                        View all notifications
                    </a>
                </div>
            </li>

        </ul>
    </li>
@endauth


{{-- ============================================================
     Notification Detail Modal
     ============================================================ --}}

<div class="modal fade" id="notifModal" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-semibold">Notification Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <h6 class="fw-semibold mb-3" id="modalTitle"></h6>
                <div class="d-flex align-items-start gap-3">
                    <div id="modalAvatar" class="flex-shrink-0"></div>
                    <p class="text-muted mb-0 small" id="modalMessage" style="line-height: 1.6;"></p>
                </div>
            </div>

            <div class="modal-footer border-top px-4 py-3">
                <a href="#" id="modalActionBtn" class="btn btn-primary btn-sm d-none">
                    <i class="bx bx-link-external me-1"></i> Open
                </a>
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('assets/custom-js/notif-detail.js') }}"></script>
<script src="{{ asset('assets/custom-js/home-notif.js') }}"></script>
