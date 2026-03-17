<x-form-layout>
    <title>Reset Password - Centralino Flora</title>
    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="images/background/Main-Entrance_796.webp" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <h4 class="mb-12">Reset Password</h4>
                <p class="mb-32 text-secondary-light text-lg">Enter your new password below.</p>
                @error('password')
                    <div class="alert alert-danger text-sm mb-16">{{ $message }}</div>
                @enderror
                <form action="{{ route('auth.update') }}" method="POST">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:lock"></iconify-icon>
                        </span>
                        <input type="password" name="password"
                            class="form-control h-56-px bg-neutral-50 radius-12 @error('password') is-invalid @enderror"
                            placeholder="New Password" required>
                    </div>
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:lock"></iconify-icon>
                        </span>
                        <input type="password" name="password_confirmation"
                            class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Confirm New Password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary-600 text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-form-layout>