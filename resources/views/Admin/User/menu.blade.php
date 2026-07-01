

<ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
    <li class="nav-item">
        <a class="nav-link {{ $title == 'Setting' ? 'active' : '' }}"
            href="{{ route('profile', auth()->user()->username) }}"><i class="icon-base bx bx-user icon-sm me-1_5"></i>
            Account</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ $title == 'Security' ? 'active' : '' }}" href="{{ route('security') }}"><i
                class="icon-base bx bx-lock-alt icon-sm me-1_5"></i> Security</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $title == 'Notifications' ? 'active' : '' }}" href="{{ route('notifications.index') }}"><i
                class="icon-base bx bx-bell icon-sm me-1_5"></i> Notifications</a>
    </li>
</ul>
