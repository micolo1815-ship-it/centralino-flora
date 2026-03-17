<x-form-layout>
    <title>Forgot Password - Centralino Flora</title>
    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="images/background/Main-Entrance_796.webp" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12">Forgot Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Enter the email address associated with your account
                        and we will send you a OTP to reset your password.</p>
                </div>
                <form action="{{ route('auth.sendOtp') }}" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success text-sm mb-16">{{ session('success') }}</div>
                    @endif
                    @error('email')
                        <div class="alert alert-danger text-sm mb-16">{{ $message }}</div>
                    @enderror
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control h-56-px bg-neutral-50 radius-12 @error('email') is-invalid @enderror"
                            placeholder="Enter Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary-600 text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Send OTP
                    </button>
                    <div class="mt-120 text-center text-sm">
                        <p class="mb-0">Already have an account?
                            <a href="{{ route('login') }}" class="text-primary-600 fw-semibold">Log In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-form-layout>