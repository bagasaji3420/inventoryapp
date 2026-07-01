@extends('Home.Layouts.app')
@section('content')
    <div class="container py-5" style="margin-top: 100px">
        <section>
            <div class="card">
                <div class="card-body p-3">

                    <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
                        <div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notifSwitch">
                                <label for="notifSwitch">Enable Notification</label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">

                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm">
                                    Mark All
                                </button>
                            </form>

                            <form action="{{ route('notifications.destroyAll') }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-outline-danger btn-sm">
                                    Clear All
                                </button>
                            </form>
                        </div>
                    </div>


                    <ul class="list-group list-group-flush" id="notificationPageList">

                        @forelse ($notifications as $notif)
                            <li class="list-group-item d-flex justify-content-between align-items-start notif-item"
                                data-title="{{ $notif->data['title'] }}" data-message="{{ $notif->data['message'] }}"
                                data-avatar="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                                data-type="{{ $notif->data['type'] }}" data-icon="{{ $notif->data['icon'] }}"
                                data-color="{{ $notif->data['color'] }}" data-id="{{ $notif->id }}"
                                data-url="{{ $notif->data['url'] ?? '' }}">

                                <div class="d-flex gap-3">

                                    {{-- ICON --}}
                                    <div>
                                        <div class="avatar">
                                            @if (in_array($notif->data['type'] ?? null, ['comment', 'mention']))
                                                <img src="{{ $notif->data['avatar'] ?? asset('default.png') }}"
                                                    class="rounded-circle" width="40" height="40"
                                                    style="object-fit:cover;">
                                            @else
                                                <span
                                                    class="avatar-initial rounded-circle bg-label-{{ $notif->data['color'] ?? 'primary' }}">
                                                    <i class="icon-base bx {{ $notif->data['icon'] ?? 'bx-bell' }}"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- CONTENT --}}
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $notif->data['title'] ?? 'Notification' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                </div>

                                {{-- ACTION --}}
                                <div class="d-flex gap-2">

                                    @if (is_null($notif->read_at))
                                        <span class="badge bg-outline-info">New</span>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')


                                        <i class="icon-base bx bx-trash"></i>
                                    </form>

                                </div>

                            </li>
                        @empty

                            <li class="list-group-item text-center text-muted" id="emptyState">
                                <i class="bx bx-bell-off fs-1"></i>
                                <div>No notifications</div>
                            </li>
                        @endforelse

                    </ul>


                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </section>
    </div>


    <script src="{{ asset('assets/custom-js/notif-page.js') }}"></script>
@endsection
