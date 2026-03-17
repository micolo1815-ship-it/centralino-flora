<x-form-layout>
    <title>Verify OTP - Centralino Flora</title>
    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="images/background/Main-Entrance_796.webp" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <h4 class="mb-12">Verify OTP</h4>
                <p class="mb-32 text-secondary-light text-lg">
                    Enter the OTP sent to <strong>{{ session('otp_email') }}</strong>.
                    It expires in 2 minutes.
                </p>
                @error('otp')
                    <div class="alert alert-danger text-sm mb-16">{{ $message }}</div>
                @enderror
                @if(session('error'))
                    <div class="alert alert-danger text-sm mb-16">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success text-sm mb-16">{{ session('success') }}</div>
                @endif
                <form action="{{ route('auth.checkOtp') }}" method="POST">
                    @csrf
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:lock"></iconify-icon>
                        </span>
                        <input type="text" name="otp" maxlength="6"
                            class="form-control h-56-px bg-neutral-50 radius-12 @error('otp') is-invalid @enderror"
                            placeholder="Enter 6-digit OTP" required>
                    </div>
                    <button type="submit" class="btn btn-primary-600 text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Verify OTP
                    </button>
                    <div class="mt-32 text-center text-sm">
                        <p class="mb-0">Didn't receive?
                            <a href="{{ route('auth.resendOtp') }}" class="text-primary-600 fw-semibold">Resend OTP</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-form-layout>