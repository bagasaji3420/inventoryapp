@if (!auth()->user()->email_verified_at)

    @if (session('success'))
        <div
            class="alert alert-success d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        @else
            <div
                class="alert alert-warning d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    @endif

    <div>
        <strong>Email not verified!</strong><br>

        @if (session('success'))
            <span class="text-success">
                Verification link has been sent. Please check your email.
            </span>
        @else
            <span>
                Please check your email to verify your account.
                If you didn't receive the email, click the resend button.
            </span>
        @endif
    </div>

    <div class="d-flex gap-2">
        {{-- Resend --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" id="resendBtn" class="btn btn-sm {{ session('success') ? 'btn-info' : 'btn-warning' }}">

                {{ session('success') ? 'Sent' : 'Resend' }}
            </button>
        </form>

        {{-- Open Gmail --}}
        <a href="https://mail.google.com" target="_blank" class="btn btn-sm btn-outline-primary">
            Open Gmail
        </a>
    </div>

    </div>
@endif
<script src="{{ asset('assets/custom-js/verified-email.js') }}"></script>
