<x-form-layout>

    <title>Sign in - Centralino Flora</title>
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="images/background/Main-Entrance_917.webp" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    {{-- ✅ Light mode logo --}}
                    <img src="{{ asset('images/Logo/Centralino Flora.png') }}" alt="Logo"
                        class="mb-40 max-w-290-px light-logo">

                    {{-- ✅ Dark mode logo --}}
                    <img src="{{ asset('images/Logo/Centralino Flora White.png') }}" alt="Logo"
                        class="mb-40 max-w-290-px dark-logo">
                    <h4 class="mb-12">Sign In to your Account</h4>
                    <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
                </div>
                <form action="/login" method="POST" id="loginForm">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email"
                            id="email" name="email" value="{{ old('email') }}" required />
                        <x-form-error name="email" />
                    </div>
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12" id="password"
                                placeholder="Password" name="password" required />
                            <x-form-error name="password" />
                        </div>
                        <span
                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="#your-password"></span>
                    </div>
                    <div class="">
                        <div class="d-flex justify-content-between gap-2">
                            {{-- <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input border border-neutral-300" type="checkbox" value=""
                                    id="remeber">
                                <label class="form-check-label" for="remember">Remember me </label>
                            </div> --}}
                            <a href="/forgot-password" class="text-primary-600 fw-medium">Forgot Password?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-600 text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Sign In</button>

                </form>
            </div>
        </div>
    </section>
</x-form-layout>