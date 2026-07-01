<!-- Notification -->

@php
    $unreadCount = auth()->user()->unreadNotifications->count();
@endphp
<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" aria-expanded="false">
        <span class="position-relative">
            <i class="icon-base bx bx-bell icon-md"></i>
            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border" id="notifBadge"
                style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
            </span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end p-0">
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h6 class="mb-0 me-auto">Notification</h6>
                <div class="d-flex align-items-center h6 mb-0">
                    <span class="badge bg-label-primary me-2" id="notifCount">
                        {{ $unreadCount }} New
                    </span>
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-notifications-all p-2 border-0 bg-transparent">
                            <i class="icon-base bx bx-envelope-open text-heading"></i>
                        </button>
                    </form>
                </div>
            </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container" style="max-height: 300px; overflow-y: auto;">
            <ul class="list-group list-group-flush" id="notifList">

                @foreach (auth()->user()->unreadNotifications()->limit(5)->get() as $notif)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item notif-item"
                        data-title="{{ $notif->data['title'] }}" data-message="{{ $notif->data['message'] }}"
                        data-id="{{ $notif->id }}" data-avatar="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                        data-type="{{ $notif->data['type'] }}" data-icon="{{ $notif->data['icon'] }}"
                        data-color="{{ $notif->data['color'] }}" data-url="{{ $notif->data['url'] ?? '' }}">
                        <div class="d-flex">

                            <div class="shrink-0 me-3">
                                <div class="avatar">
                                    @if (in_array($notif->data['type'] ?? null, ['comment', 'mention']))
                                        <img src="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                                            class="rounded-circle" width="35" height="35"
                                            style="object-fit:cover;">
                                    @else
                                        <span
                                            class="avatar-initial rounded-circle bg-label-{{ $notif->data['color'] ?? 'primary' }}">
                                            <i class="icon-base bx {{ $notif->data['icon'] ?? 'bx-bell' }}"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grow">
                                <h6 class="small mb-0">
                                    {{ $notif->data['title'] ?? 'Notification' }}
                                </h6>
                                <small class="text-body-secondary">
                                    {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </div>

                        </div>
                    </li>
                @endforeach
            </ul>
        </li>
        @if ($unreadCount > 0)
            <li class="border-top">
                <div class="d-grid p-4">
                    <a class="btn btn-primary btn-sm d-flex" href="{{ route('notifications.index') }}">
                        <small class="align-middle">View all notifications</small>
                    </a>
                </div>
            </li>
        @endif
    </ul>
</li>
<!--/ Notification -->




<script src="{{ asset('assets/custom-js/notif-navbar.js') }}"></script>
