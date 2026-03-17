<x-auth-dashboard>
    <title>Add New Officers - Centralino Flora</title>

    <x-auth-sidebar></x-auth-sidebar>

    <main class="dashboard-main">
        <x-auth-navbar-header></x-auth-navbar-header>

        <div class="dashboard-main-body">
            <x-auth-navbar-right>Add Officer</x-auth-navbar-right>
            @php
                $positions = [
                    'program_chair' => ['label' => 'Program Chair of Biology', 'hasRetain' => true],
                    'adviser' => ['label' => 'Adviser of Biology Society', 'hasRetain' => true],
                    'president' => ['label' => 'President', 'hasRetain' => false],
                    'viceP_internal' => ['label' => 'Vice President Internal', 'hasRetain' => false],
                    'viceP_external' => ['label' => 'Vice President External', 'hasRetain' => false],
                    'secretary' => ['label' => 'Secretary', 'hasRetain' => false],
                    'treasurer' => ['label' => 'Treasurer', 'hasRetain' => false],
                    'auditor' => ['label' => 'Auditor', 'hasRetain' => false],
                    'pro' => ['label' => 'PRO', 'hasRetain' => false],
                    '1st_rep' => ['label' => '1st Year Representative', 'hasRetain' => false],
                    '2nd_rep' => ['label' => '2nd Year Representative', 'hasRetain' => false],
                    '3rd_rep' => ['label' => '3rd Year Representative', 'hasRetain' => false],
                    '4th_rep' => ['label' => '4th Year Representative', 'hasRetain' => false],
                ];

                // Previous officer data for retain checkbox
                $prevData = [
                    'program_chair' => [
                        'officer' => $prevProgramChair,
                        'imgPath' => $prevPCUser?->profile_image ?? $prevProgramChair?->image_path ?? null,
                    ],
                    'adviser' => [
                        'officer' => $prevAdviser,
                        'imgPath' => $prevAdvUser?->profile_image ?? $prevAdviser?->image_path ?? null,
                    ],
                ];
            @endphp

            <form id="officersForm" action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- School Year --}}
                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div class="card p-0">
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8"
                                role="alert">
                                <div class="d-flex align-items-center justify-content-between text-lg text-uppercase">
                                    Warning!
                                    <button type="button" class="remove-button text-warning-600 text-xxl line-height-1">
                                        <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                                    </button>
                                </div>
                                <p class="fw-medium text-danger-600 text-sm mt-8 mb-3 text-uppercase">
                                    This is the first &amp; last time you can change the School Year.
                                </p>
                            </div>
                            <div class="card-body p-24">
                                <label class="form-label mb-2 fw-semibold">MCU Biological Society School Year</label>
                                <input type="text" name="school_year" value="{{ old('school_year') }}"
                                    class="form-control @error('school_year') is-invalid @enderror"
                                    placeholder="e.g. 2025-2026" pattern="^\d{4}-\d{4}$" required>
                                @error('school_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Officers --}}
                <div class="row gy-4 mb-3">
                    @foreach($positions as $key => $config)
                        @php
                            $idx = $loop->index + 1;
                            $label = $config['label'];
                            $retain = $config['hasRetain'];
                            $prev = $retain ? ($prevData[$key] ?? null) : null;
                            $officer = $prev['officer'] ?? null;
                            $imgPath = $prev['imgPath'] ?? null;
                        @endphp

                        <div class="col-md-6">
                            <div class="card h-100 p-0">
                                <div class="card-header">
                                    <label class="form-label mb-2 fw-semibold">{{ $label }}</label>
                                </div>
                                <div class="card-body h-100 p-24">
                                    <div class="d-flex align-items-start gap-4">

                                        {{-- Image Upload --}}
                                        <div class="upload-image-wrapper d-flex flex-column align-items-center gap-3">
                                            <div
                                                class="uploaded-img d-none position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                                <button type="button"
                                                    class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                                    <iconify-icon icon="radix-icons:cross-2"
                                                        class="text-xl text-danger-600"></iconify-icon>
                                                </button>
                                                <img id="uploaded-img__preview-{{ $idx }}"
                                                    class="w-100 h-100 object-fit-cover"
                                                    src="{{ asset('images/avatar/blank-profile.png') }}" alt="preview">
                                            </div>

                                            <label
                                                class="upload-file h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                                for="upload-file-{{ $idx }}">
                                                <iconify-icon icon="solar:camera-outline"
                                                    class="text-xl text-secondary-light"></iconify-icon>
                                                <span class="fw-semibold text-secondary-light">Upload</span>
                                                <input id="upload-file-{{ $idx }}" type="file" name="{{ $key }}_image"
                                                    accept="image/*" hidden>
                                            </label>

                                            {{-- Retain checkbox (only for Program Chair & Adviser) --}}
                                            @if($retain && $officer)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="retain_{{ $key }}"
                                                        name="retain_same_person_{{ $key }}" value="1" {{ old("retain_same_person_{$key}") ? 'checked' : '' }} onchange="retainOfficer(this,
                                                                                                                '{{ $officer->firstname }}',
                                                                                                                '{{ $officer->middle_initial }}',
                                                                                                                '{{ $officer->lastname }}',
                                                                                                                '{{ $officer->email }}',
                                                                                                                '{{ $imgPath ? asset('storage/' . $imgPath) : asset('images/avatar/blank-profile.png') }}',
                                                                                                                '{{ $key }}', {{ $idx }}
                                                                                                            )">
                                                    <label class="form-check-label text-sm" for="retain_{{ $key }}">
                                                        Retain same person
                                                    </label>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Name & Email Fields --}}
                                        <div class="d-flex flex-column flex-grow-1 gap-3">
                                            <input type="text" name="{{ $key }}_firstname"
                                                value="{{ old("{$key}_firstname") }}"
                                                class="form-control form-control-sm @error("{$key}_firstname") is-invalid @enderror"
                                                placeholder="First Name" required>

                                            <input type="text" name="{{ $key }}_middle_initial"
                                                value="{{ old("{$key}_middle_initial") }}"
                                                class="form-control form-control-sm" placeholder="M.I." maxlength="1"
                                                style="text-transform: uppercase;">

                                            <input type="text" name="{{ $key }}_lastname"
                                                value="{{ old("{$key}_lastname") }}"
                                                class="form-control form-control-sm @error("{$key}_lastname") is-invalid @enderror"
                                                placeholder="Last Name" required>

                                            <input type="email" name="{{ $key }}_email" value="{{ old("{$key}_email") }}"
                                                class="form-control form-control-sm @error("{$key}_email") is-invalid @enderror"
                                                placeholder="Email" required>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-12 mt-3">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger-600" data-bs-toggle="modal"
                                data-bs-target="#confirmModal">
                                Add &amp; Replace Current Officers
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Confirm Modal --}}
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content radius-16 bg-base">
                            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                                <h1 class="modal-title fs-5 text-danger-600">WARNING</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-24 text-center">
                                <iconify-icon icon="mdi:alert-circle-outline" class="text-danger-600"
                                    style="font-size:48px;"></iconify-icon>
                                <h6 class="mt-16 mb-8 text-uppercase">Are you sure you want to add &amp; replace the
                                    current S.Y. Officers?</h6>
                                <p class="text-secondary-light text-sm mb-24">This action will create new user accounts
                                    and deactivate old ones.</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <button type="button" onclick="showOfficerLoader()"
                                        class="btn btn-danger border border-danger-600 text-md px-48 py-12 radius-8">
                                        Yes, Proceed
                                    </button>
                                    <button type="button" data-bs-dismiss="modal"
                                        class="border border-success-600 bg-hover-success-200 text-success-600 text-md px-40 py-11 radius-8">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <x-auth-footer></x-auth-footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($positions as $key => $config)
                @if($config['hasRetain'])
                    @php
                        $wasRetained = old("retain_same_person_{$key}");
                        $prev = $prevData[$key] ?? null;
                        $officer = $prev['officer'] ?? null;
                        $imgPath = $prev['imgPath'] ?? null;
                        $idx = array_search($key, array_keys($positions)) + 1;
                    @endphp
                    @if($wasRetained && $officer)
                        (function () {
                            const prefix = '{{ $key }}';
                            const idx = {{ $idx }};
                            const imgUrl = '{{ $imgPath ? asset("storage/" . $imgPath) : asset("images/avatar/blank-profile.png") }}';

                            // ✅ Lock fields as readonly
                            ['firstname', 'middle_initial', 'lastname', 'email'].forEach(field => {
                                const input = document.querySelector(`input[name="${prefix}_${field}"]`);
                                if (input) input.readOnly = true;
                            });

                            // ✅ Show retained image
                            const previewImg = document.getElementById(`uploaded-img__preview-${idx}`);
                            const uploadedWrap = previewImg?.closest('.uploaded-img');
                            const uploadLabel = document.querySelector(`label[for="upload-file-${idx}"]`);
                            if (previewImg) previewImg.src = imgUrl;
                            uploadedWrap?.classList.remove('d-none');
                            uploadLabel?.classList.add('d-none');
                        })();
                    @endif
                @endif
            @endforeach
            // ✅ Image upload preview for all wrappers
            document.querySelectorAll('.upload-image-wrapper').forEach(wrapper => {
                const fileInput = wrapper.querySelector('input[type="file"]');
                const previewWrapper = wrapper.querySelector('.uploaded-img');
                const previewImage = previewWrapper?.querySelector('img');
                const removeBtn = previewWrapper?.querySelector('.uploaded-img__remove');
                const uploadLabel = wrapper.querySelector('label.upload-file');

                if (!fileInput || !previewWrapper || !previewImage || !removeBtn || !uploadLabel) return;

                fileInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        previewImage.src = e.target.result;
                        previewWrapper.classList.remove('d-none');
                        uploadLabel.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                });

                removeBtn.addEventListener('click', function () {
                    fileInput.value = '';
                    previewImage.src = '{{ asset("images/avatar/blank-profile.png") }}';
                    previewWrapper.classList.add('d-none');
                    uploadLabel.classList.remove('d-none');
                });
            });

            // ✅ School year format validation
            const syInput = document.querySelector('input[name="school_year"]');
            if (syInput) {
                syInput.addEventListener('input', function () {
                    const valid = /^\d{4}-\d{4}$/.test(this.value) || this.value === '';
                    this.setCustomValidity(valid ? '' : 'Format must be YYYY-YYYY, e.g. 2025-2026');
                });
            }
        });

        // ✅ Retain officer — fill fields and show image
        function retainOfficer(checkbox, fname, mi, lname, email, imgUrl, prefix, idx) {
            const checked = checkbox.checked;

            // ✅ Lock ALL fields when retained — only image is changeable
            ['firstname', 'middle_initial', 'lastname', 'email'].forEach(field => {
                const input = document.querySelector(`input[name="${prefix}_${field}"]`);
                if (!input) return;
                input.value = checked ? (field === 'firstname' ? fname : field === 'middle_initial' ? mi : field === 'lastname' ? lname : email) : '';
                input.readOnly = checked;
                input.style.backgroundColor = checked ? '' : ''; // keeps existing styling
            });

            // ✅ Image upload still allowed even when retained
            const previewImg = document.getElementById(`uploaded-img__preview-${idx}`);
            const uploadedWrap = previewImg?.closest('.uploaded-img');
            const uploadLabel = document.querySelector(`label[for="upload-file-${idx}"]`);

            if (checked) {
                previewImg.src = imgUrl;
                uploadedWrap?.classList.remove('d-none'); // ✅ show preview
                uploadLabel?.classList.add('d-none');     // ✅ hide upload box
            } else {
                previewImg.src = '{{ asset("images/avatar/blank-profile.png") }}';
                uploadedWrap?.classList.add('d-none');    // ✅ hide preview
                uploadLabel?.classList.remove('d-none'); // ✅ show upload box
            }
        }
        function showOfficerLoader() {
            const modalEl = document.getElementById('confirmModal');
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');

            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();

            const loader = document.getElementById('loadingOverlay');
            const message = document.getElementById('loadingMessage');
            const subMessage = document.getElementById('loadingSubMessage');

            if (message) message.textContent = 'Processing Officers...';
            if (subMessage) subMessage.textContent = 'This takes a few minutes, please wait.';
            if (loader) loader.style.display = 'flex';

            setTimeout(function () {
                // ✅ Target the specific form by ID
                document.getElementById('officersForm').submit();
            }, 300);
        }
    </script>

</x-auth-dashboard>