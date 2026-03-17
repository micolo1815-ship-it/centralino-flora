<x-auth-dashboard>
  <title>Edit User - Centralino Flora</title>

  <x-auth-sidebar></x-auth-sidebar>

  <main class="dashboard-main">
    <x-auth-navbar-header></x-auth-navbar-header>

    <div class="dashboard-main-body">
      <x-auth-navbar-right>Edit User's Profile</x-auth-navbar-right>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show radius-8 mb-20" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

        @if($errors->has('error'))
          <div class="alert alert-danger alert-dismissible fade show radius-8 mb-20" role="alert">
              {{ $errors->first('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <div class="row gy-4">

            {{-- Left panel --}}
            <div class="col-lg-4">
                <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                    <img src="{{ asset('images/background/Narra x acacia.webp') }}" alt=""
                        class="w-100 object-fit-cover profile-background-card">
                    <div class="pb-24 ms-16 mb-24 me-16 mt--100">
                        <div class="text-center border border-top-0 border-start-0 border-end-0 pb-16">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/avatar/blank-profile.png') }}"
                                alt="" id="sidePreview"
                                class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                            <h6 class="mb-0 mt-16">
                                {{ $user->first_name }}
                                {{ $user->middle_initial ? $user->middle_initial . '.' : '' }}
                                {{ $user->last_name }}
                            </h6>
                            <span class="text-secondary-light">{{ $user->email }}</span>
                        </div>
                        <div class="mt-24">
                            <h6 class="text-xl mb-16">Personal Info</h6>
                            <ul>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                        {{ $user->first_name }}
                                        {{ $user->middle_initial ? $user->middle_initial . '.' : '' }}
                                        {{ $user->last_name }}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Position</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{ $user->position }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Status</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                        @if($user->status === 'active')
                                          <span class="bg-success-focus text-success-main px-12 py-2 rounded-pill text-sm">Activated</span>
                                        @else
                                          <span class="bg-warning-focus text-warning-main px-12 py-2 rounded-pill text-sm">Deactivated</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right panel --}}
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body p-24">

                        <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center px-24 active"
                                    id="pills-edit-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-edit-profile" type="button" role="tab">
                                    Edit Profile
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center px-24"
                                    id="pills-change-password-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-change-password" type="button" role="tab">
                                    Change Password
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">

                            {{-- Edit Profile Tab --}}
                            <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" tabindex="0">
                                <form action="{{ route('users.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                    <div class="mb-24 mt-16">
                                        <div class="avatar-upload">
                                            <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type="file" id="imageUpload" name="profile_image"
                                                    accept=".png, .jpg, .jpeg" hidden onchange="previewImage(this)">
                                                <label for="imageUpload"
                                                    class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview"
                                                    style="background-image: url('{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/avatar/blank-profile.png') }}')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    First Name <span class="text-danger-600">*</span>
                                                </label>
                                                <input type="text" name="first_name"
                                                    value="{{ old('first_name', $user->first_name) }}"
                                                    class="form-control radius-8 @error('first_name') is-invalid @enderror"
                                                    placeholder="Enter First Name" required>
                                                @error('first_name')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Middle Initial
                                                </label>
                                                <input type="text" name="middle_initial"
                                                    value="{{ old('middle_initial', $user->middle_initial) }}"
                                                    class="form-control radius-8"
                                                    placeholder="Enter Middle Initial" maxlength="1">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Last Name <span class="text-danger-600">*</span>
                                                </label>
                                                <input type="text" name="last_name"
                                                    value="{{ old('last_name', $user->last_name) }}"
                                                    class="form-control radius-8 @error('last_name') is-invalid @enderror"
                                                    placeholder="Enter Last Name" required>
                                                @error('last_name')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                    Email <span class="text-danger-600">*</span>
                                                </label>
                                                <input type="email" name="email"
                                                    value="{{ old('email', $user->email) }}"
                                                    class="form-control radius-8 @error('email') is-invalid @enderror"
                                                    placeholder="Enter email address" required>
                                                @error('email')
                                                  <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Status
                                            </label>
                                            <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="statusSwitch"
                                                    {{ $user->status === 'active' ? 'checked' : '' }}
                                                    onchange="
                                                        document.getElementById('statusInput').value = this.checked ? 'active' : 'inactive';
                                                        document.getElementById('statusLabel').textContent = this.checked ? 'Activated' : 'Deactivated';
                                                    ">
                                                <input type="hidden" name="status" id="statusInput"
                                                    value="{{ $user->status === 'active' ? 'active' : 'inactive' }}">
                                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                                    for="statusSwitch" id="statusLabel">
                                                    {{ $user->status === 'active' ? 'Activated' : 'Deactivated' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                                        <button type="submit"
                                            class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                                            Save Changes
                                        </button>
                                        <a href="{{ route('users.index') }}"
                                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 text-decoration-none">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>

                            {{-- Change Password Tab --}}
                            <div class="tab-pane fade" id="pills-change-password" role="tabpanel" tabindex="0">
                                <form action="{{ route('users.password', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            New Password <span class="text-danger-600">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" name="password" class="form-control radius-8"
                                                id="your-password" placeholder="Enter New Password*" required>
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#your-password"></span>
                                        </div>
                                        @error('password')
                                          <div class="text-danger-600 text-sm mt-4">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Confirm Password <span class="text-danger-600">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation"
                                                class="form-control radius-8" id="confirm-password"
                                                placeholder="Confirm Password*" required>
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#confirm-password"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="submit"
                                            class="btn btn-primary-600 border border-primary-600 text-md px-56 py-12 radius-8">
                                            Save Changes
                                        </button>
                                        <a href="{{ route('users.index') }}"
                                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 text-decoration-none">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-auth-footer></x-auth-footer>
</main>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').style.backgroundImage = `url('${e.target.result}')`;
            document.getElementById('sidePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</x-auth-dashboard>