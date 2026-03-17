<x-auth-dashboard>
<title>Edit Previous Officers - Centralino Flora</title>

<x-auth-sidebar></x-auth-sidebar>

<main class="dashboard-main">
    <x-auth-navbar-header></x-auth-navbar-header>

    <div class="dashboard-main-body">
        <x-auth-navbar-right>Edit Previous Officers</x-auth-navbar-right>
        <form action="{{ route('about.previous_edit_update', ['school_year' => $school_year]) }}"
            method="POST" enctype="multipart/form-data"
            onsubmit="return confirmSave()">
            @csrf
            @method('POST')

            @php
                $officerMap = $officers->keyBy('position');

                $positions = [
                    'Program Chair'            => ['key' => 'program_chair',  'label' => "Program Chair of Biology's Name"],
                    'Adviser'                  => ['key' => 'adviser',        'label' => "Adviser of Biology Society's Name"],
                    'President'                => ['key' => 'president',      'label' => "President's Name"],
                    'Vice President Internal'  => ['key' => 'viceP_internal', 'label' => "Vice President Internal's Name"],
                    'Vice President External'  => ['key' => 'viceP_external', 'label' => "Vice President External's Name"],
                    'Secretary'                => ['key' => 'secretary',      'label' => "Secretary's Name"],
                    'Treasurer'                => ['key' => 'treasurer',      'label' => "Treasurer's Name"],
                    'Auditor'                  => ['key' => 'auditor',        'label' => "Auditor's Name"],
                    'PRO'                      => ['key' => 'pro',            'label' => "PRO's Name"],
                    '1st Year Representative'  => ['key' => '1st_rep',        'label' => "1st Year Representative's Name"],
                    '2nd Year Representative'  => ['key' => '2nd_rep',        'label' => "2nd Year Representative's Name"],
                    '3rd Year Representative'  => ['key' => '3rd_rep',        'label' => "3rd Year Representative's Name"],
                    '4th Year Representative'  => ['key' => '4th_rep',        'label' => "4th Year Representative's Name"],
                ];
            @endphp

            <input type="hidden" name="school_year" value="{{ $school_year }}">

            <div class="row gy-4 mb-3">

                {{-- School Year Display --}}
                <div class="col-12">
                    <div class="card p-0">
                        <div class="card-header">
                            <label class="form-label mb-0 fw-semibold">MCU Biological Society School Year</label>
                        </div>
                        <div class="card-body p-24">{{ $school_year }}</div>
                    </div>
                </div>

                @foreach($positions as $positionName => $config)
                    @php
                        $officer    = $officerMap->get($positionName);
                        $key        = $config['key'];
                        $label      = $config['label'];
                        $fname      = old("{$key}_firstname",      $officer?->firstname      ?? '');
                        $mi         = old("{$key}_middle_initial", $officer?->middle_initial ?? '');
                        $lname      = old("{$key}_lastname",       $officer?->lastname       ?? '');
                        $linkedUser = $officer ? $usersMap->get($officer->id) : null;
                        $imgPath    = $linkedUser?->profile_image ?? $officer?->image_path ?? null;
                        $idx        = $loop->index + 1;
                    @endphp

                    <div class="col-md-6">
                        <div class="card h-100 p-0">
                            <div class="card-header">
                                <label class="form-label mb-2 fw-semibold">{{ $label }}</label>
                                <div class="row g-2">
                                    <div class="col-5">
                                        <input type="text" name="{{ $key }}_firstname" value="{{ $fname }}"
                                            class="form-control form-control-sm" placeholder="First Name">
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name="{{ $key }}_middle_initial" value="{{ $mi }}"
                                            class="form-control form-control-sm" placeholder="M.I." maxlength="1">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" name="{{ $key }}_lastname" value="{{ $lname }}"
                                            class="form-control form-control-sm" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>

                            <div class="card-body h-100 p-24">
                                <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                    <label
                                        class="position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed d-flex align-items-center flex-column justify-content-center gap-1 cursor-pointer"
                                        for="upload-file-{{ $idx }}"
                                        id="preview-wrapper-{{ $idx }}"
                                        style="background-image: url('{{ $imgPath ? asset('storage/' . $imgPath) : asset('images/avatar/blank-profile.png') }}'); background-size: cover; background-position: center;">

                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center flex-column justify-content-center gap-1"
                                            style="background: rgba(0,0,0,0.35); border-radius: inherit;">
                                            <iconify-icon icon="solar:camera-outline" class="text-xl text-white"></iconify-icon>
                                            <span class="fw-semibold text-white text-sm">
                                                {{ $imgPath ? 'Change' : 'Upload' }}
                                            </span>
                                        </div>

                                        <input id="upload-file-{{ $idx }}" type="file"
                                            name="{{ $key }}_image" accept="image/*" hidden
                                            onchange="previewOfficerImage(this, {{ $idx }})">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                <div class="d-flex justify-content-end gap-3 mt-24">
                    <a href="{{ route('about.previous_view') }}"
                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8 text-decoration-none">
                        Cancel
                    </a>
                    <button type="submit"
                        class="btn btn-primary-600 border border-primary-600 text-md px-40 py-12 radius-8">
                        Save Changes
                    </button>
                </div>

            </div>
        </form>

        {{-- Confirm popup --}}
        <div id="confirmModal" class="position-fixed top-0 start-0 w-100 h-100 d-none"
            style="background: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="position-absolute top-50 start-50 translate-middle bg-base radius-12 p-32"
                style="width: 420px;">
                <div class="text-center mb-20">
                    <iconify-icon icon="mdi:information-circle-outline" class="text-primary-600"
                        style="font-size: 48px;"></iconify-icon>
                </div>
                <h5 class="text-center text-primary-light mb-8">Confirm Changes</h5>
                <p class="text-center text-secondary-light text-sm mb-24">
                    These changes will be <strong class="text-primary-light">visible to the public</strong>
                    on the About page of the website. Are you sure you want to save?
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button onclick="cancelSave()"
                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-32 py-11 radius-8">
                        Cancel
                    </button>
                    <button onclick="proceedSave()"
                        class="btn btn-primary-600 border border-primary-600 text-md px-32 py-12 radius-8">
                        Yes, Save
                    </button>
                </div>
            </div>
        </div>

    </div>

    <x-auth-footer></x-auth-footer>
</main>

<script>
    let _form = null;

    function confirmSave() {
        _form = event.target;
        document.getElementById('confirmModal').classList.remove('d-none');
        return false;
    }

    function cancelSave() {
        document.getElementById('confirmModal').classList.add('d-none');
        _form = null;
    }

    function proceedSave() {
        document.getElementById('confirmModal').classList.add('d-none');
        _form.submit();
    }

    function previewOfficerImage(input, index) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.getElementById('preview-wrapper-' + index);
                wrapper.style.backgroundImage = `url('${e.target.result}')`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</x-auth-dashboard>