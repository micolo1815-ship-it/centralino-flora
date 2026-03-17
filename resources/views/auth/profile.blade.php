<x-auth-dashboard>

    <title>Profile - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Profile
                </x-auth-navbar-right>

                <div class="row gy-4">
                    <div class="col-lg-4">
                        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                            <img src="images/background/Narra x acacia.webp" alt=""
                                class="w-100 object-fit-cover profile-background-card">
                            <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                                <div class="text-center border border-top-0 border-start-0 border-end-0">
                                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/avatar/blank-profile.png') }}"
                                        alt=""
                                        class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover"
                                        draggable="false">
                                    <h6 class="mb-0 mt-16">{{ auth()->user()->first_name }}
                                        {{ auth()->user()?->middle_initial ? auth()->user()->middle_initial . '.' : '' }}
                                        {{ auth()->user()->last_name }}
                                    </h6>
                                    <span class="text-secondary-light mb-16">{{ auth()->user()->position }}</span>
                                </div>
                                <div class="mt-24">
                                    <h6 class="text-xl mb-16">Personal Info</h6>
                                    <ul>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                            <span class="w-70 text-secondary-light fw-medium">:
                                                {{ auth()->user()->first_name }}
                                                {{ auth()->user()?->middle_initial ? auth()->user()->middle_initial . '.' : '' }}
                                                {{ auth()->user()->last_name }}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light"> Role</span>
                                            <span class="w-70 text-secondary-light fw-medium">:
                                                {{ auth()->user()->position }}</span>
                                        </li>
                                        <li class="d-flex align-items-center gap-1 mb-12">
                                            <span class="w-30 text-md fw-semibold text-primary-light"> School
                                                Year</span>
                                            <span class="w-70 text-secondary-light fw-medium">:
                                                {{ auth()->user()->officer?->school_year ?? '—' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card h-100">
                            <div class="card-body p-24">
                                @php
                                    $active = session('active_tab', 'edit');
                                @endphp
                                <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab"
                                    role="tablist">

                                    <li class="nav-item" role="presentation">
                                        <button
                                            class="nav-link d-flex align-items-center px-24 {{ $active === 'edit' ? 'active' : '' }}"
                                            id="pills-edit-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-edit-profile" type="button" role="tab"
                                            aria-controls="pills-edit-profile"
                                            aria-selected="{{ $active === 'edit' ? 'true' : 'false' }}">
                                            Edit Profile
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button
                                            class="nav-link d-flex align-items-center px-24 {{ $active === 'password' ? 'active' : '' }}"
                                            id="pills-change-password-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-change-password" type="button" role="tab"
                                            aria-controls="pills-change-password"
                                            aria-selected="{{ $active === 'password' ? 'true' : 'false' }}">
                                            Change Password
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show {{ $active === 'edit' ? 'show active' : '' }}"
                                        id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab"
                                        tabindex="0">
                                        <form action="{{ route('profile.update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')
                                            <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                            <!-- Upload Image Start -->
                                            {{-- Continue this. My brain is lagging --}}
                                            <div class="mb-24 mt-16">
                                                <div class="avatar-upload">
                                                    <div
                                                        class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                        <input type='file' id="imageUpload" name="profile_image"
                                                            accept=".png, .jpg, .jpeg" hidden>
                                                        <label for="imageUpload"
                                                            class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                            <iconify-icon icon="solar:camera-outline"
                                                                class="icon"></iconify-icon>
                                                        </label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview"
                                                            style="background-image: url('{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/avatar/blank-profile.png') }}')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Upload Image End -->

                                            {{-- Editing shit --}}
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="name"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">First
                                                            Name <span class="text-danger-600">*</span></label>
                                                        <input type="text" name="first_name"
                                                            class="form-control radius-8" id="fname"
                                                            placeholder="Enter First Name"
                                                            value="{{ auth()->user()->first_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="name"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Middle
                                                            Initial <span class="text-danger-600"></span></label>
                                                        <input type="text" name="middle_initial"
                                                            class="form-control radius-8" id="mi"
                                                            placeholder="Enter Middle Initial"
                                                            value="{{ auth()->user()->middle_initial }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="name"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Last
                                                            Name <span class="text-danger-600">*</span></label>
                                                        <input type="text" name="last_name"
                                                            class="form-control radius-8" id="lname"
                                                            placeholder="Enter Last Name"
                                                            value="{{ auth()->user()->last_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="email"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                            Email <span class="text-danger-600">*</span>
                                                            {{-- ✅ Info icon with tooltip --}}
                                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="If you want to change your email, please contact IT or your professor.">
                                                                <iconify-icon icon="lucide:info"
                                                                    class="text-warning-600 text-sm"
                                                                    style="cursor:help;"></iconify-icon>
                                                            </span>
                                                        </label>
                                                        <input type="email" name="email"
                                                            class="form-control radius-8 bg-neutral-200 text-secondary-light"
                                                            id="email" placeholder="Enter email address"
                                                            value="{{ auth()->user()->email }}" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <button type="submit"
                                                    class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <form action="{{ route('profile.password.update') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="tab-pane fade {{ $active === 'password' ? 'show active' : '' }}"
                                            id="pills-change-password" role="tabpanel"
                                            aria-labelledby="pills-change-password-tab" tabindex="0">
                                            <div class="mb-20">
                                                <label for="your-password"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">New
                                                    Password
                                                    <span class="text-danger-600">*</span></label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control radius-8" name="password"
                                                        id="your-password" placeholder="Enter New Password*" required>
                                                    <span
                                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                        data-toggle="#your-password"></span>
                                                </div>
                                            </div>
                                            <div class="mb-20">
                                                <label for="confirm-password"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed
                                                    Password <span class="text-danger-600">*</span></label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control radius-8"
                                                        name="password_confirmation" id="confirm-password"
                                                        placeholder="Confirm Password*" required>
                                                    <span
                                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                        data-toggle="#confirm-password"></span>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <button type="submit"
                                                    class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <x-auth-footer>

            </x-auth-footer>
        </main>

        <script src="js/profile.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // ✅ Initialize tooltips
                const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipEls.forEach(el => new bootstrap.Tooltip(el));

                // ✅ Edit Profile form — exclude disabled email from change detection
                const editForm = document.querySelector('#pills-edit-profile form');
                const editBtn = editForm.querySelector('button[type="submit"]');
                const editInputs = editForm.querySelectorAll('input:not([disabled])'); // ✅ skip disabled

                const editOriginals = {};
                editInputs.forEach(input => {
                    if (input.name) editOriginals[input.name] = input.value;
                });

                editBtn.disabled = true;
                editBtn.classList.add('opacity-50');

                function checkEditChanges() {
                    let changed = false;
                    editInputs.forEach(input => {
                        if (input.type === 'file') {
                            if (input.files && input.files.length > 0) changed = true;
                        } else if (input.name && input.value !== editOriginals[input.name]) {
                            changed = true;
                        }
                    });
                    editBtn.disabled = !changed;
                    editBtn.classList.toggle('opacity-50', !changed);
                }

                editInputs.forEach(input => {
                    input.addEventListener('input', checkEditChanges);
                    input.addEventListener('change', checkEditChanges);
                });

                // ✅ Change Password form
                const passForm = document.querySelector('#pills-change-password').closest('form');
                const passBtn = passForm.querySelector('button[type="submit"]');
                const passInputs = passForm.querySelectorAll('input[type="password"]');

                passBtn.disabled = true;
                passBtn.classList.add('opacity-50');

                function checkPassChanges() {
                    const allFilled = Array.from(passInputs).every(i => i.value.trim() !== '');
                    passBtn.disabled = !allFilled;
                    passBtn.classList.toggle('opacity-50', !allFilled);
                }

                passInputs.forEach(input => input.addEventListener('input', checkPassChanges));
                // ✅ Profile image preview
                const imageUpload = document.getElementById('imageUpload');
                const imagePreview = document.getElementById('imagePreview');

                imageUpload.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.style.backgroundImage = `url('${e.target.result}')`;
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
</x-auth-dashboard>