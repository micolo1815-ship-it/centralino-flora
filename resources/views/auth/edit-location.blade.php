<x-auth-dashboard>
    <title>Edit Location - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Edit Location
                </x-auth-navbar-right>

                <div class="col-lg-12">
                    <div class="card">
                        <form action="{{ route('location.update', $location->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label class="form-label">Location Name</label>
                                        <input type="text" name="location_name"
                                            class="form-control @error('location_name') is-invalid @enderror"
                                            placeholder="Enter Location Name"
                                            value="{{ old('location_name', $location->name) }}" required>

                                        @error('location_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Trees</label>
                                        <select name="trees[]" id="trees" class="selectpicker form-control" multiple
                                            data-live-search="true" data-actions-box="true" title="Select Trees">
                                            @foreach ($trees as $tree)
                                                <option value="{{ $tree->id }}"
                                                    {{ in_array($tree->id, $selectedTrees) ? 'selected' : '' }}>
                                                    {{ $tree->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Main Image Upload</label>

                                        <div id="main-drop-area" class="border border-success rounded p-4 text-center">
                                            <section class="upload-form">
                                                <input type="file" name="image" id="mainFileElem" accept="image/*"
                                                    hidden>
                                                <label for="mainFileElem" id="mainFileLabel" class="d-block">
                                                    <i class="ri-upload-cloud-2-line"
                                                        style="font-size: 2rem; color: #4caf50;"></i>
                                                    <p class="mt-2 mb-0">Drag & Drop your main image here<br>
                                                        <span class="text-success fw-semibold">or click to browse</span>
                                                    </p>
                                                </label>
                                            </section>
                                        </div>

                                        <!-- Hidden input for image removal (initial false, boolean flag) -->
                                        <input type="hidden" name="remove_image" id="removeCoverInput" value="0">

                                        {{-- Display existing main image --}}
                                        <div id="mainImagePreview" class="row mt-4 g-3"
                                            style="display: {{ !empty($location->image) ? 'block' : 'none' }};">
                                            @if (!empty($location->image))
                                                <div class="col-md-3 position-relative" id="imageContainer">
                                                    <img src="{{ asset('storage/' . $location->image) }}"
                                                        class="img-fluid rounded shadow-sm border" alt="Main Image"
                                                        id="currentMainImage">
                                                    <button type="button"
                                                        class="close-btn position-absolute top-0 end-0 btn-close btn-close-white m-1"
                                                        onclick="removeCoverImage(this, '{{ $location->image }}')"
                                                        data-image="{{ $location->image }}">
                                                        &times;
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Confirmation message after removal --}}
                                        <div id="imageRemovedMsg" class="alert alert-info mt-2" style="display: none;">
                                            Main image removed. You can upload a new one above.
                                        </div>
                                    </div>

                                    <div class="col-12" data-visible-for="program-chair,advisor,admin-it">
                                        <label class="form-label">Status</label>

                                        <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                            <input type="hidden" name="status" value="archive">

                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="switch1" name="status" value="active"
                                                {{ old('status', $location->status ?? 'archive') === 'active' ? 'checked' : '' }}
                                                aria-checked="{{ old('status', $location->status ?? 'archive') === 'active' ? 'true' : 'false' }}">

                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="switch1" id="statusLabel">
                                                {{-- Initial value --}}
                                                {{ old('status', $location->status ?? 'archive') === 'active' ? 'Active' : 'Archived' }}
                                            </label>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            // Your existing checkbox toggle (unchanged)
                                            const checkbox = document.getElementById('switch1');
                                            const statusLabel = document.getElementById('statusLabel');

                                            if (checkbox && statusLabel) {
                                                function updateLabel() {
                                                    if (checkbox.checked) {
                                                        statusLabel.textContent = 'Active';
                                                        checkbox.setAttribute('aria-checked', 'true');
                                                    } else {
                                                        statusLabel.textContent = 'Archived';
                                                        checkbox.setAttribute('aria-checked', 'false');
                                                    }
                                                }

                                                updateLabel(); // run on load
                                                checkbox.addEventListener('change', updateLabel);
                                            }

                                            // Updated removeCoverImage function (now takes buttonElement and imagePath)
                                            window.removeCoverImage = function(buttonElement, imagePath) {
                                                try {
                                                    console.log('Removing image:', imagePath); // Debug log

                                                    // Remove the specific image container (using passed buttonElement)
                                                    const container = buttonElement.closest('.col-md-3') || document.getElementById(
                                                        'imageContainer');
                                                    if (container) {
                                                        container
                                                            .remove(); // Or: container.style.display = 'none'; for hiding instead of removing
                                                    }

                                                    // Hide entire preview if empty
                                                    const preview = document.getElementById('mainImagePreview');
                                                    if (preview && preview.children.length === 0) {
                                                        preview.style.display = 'none';
                                                    }

                                                    // Flag removal for backend: Set to '1' (boolean true, triggers filled('remove_image'))
                                                    const removeInput = document.getElementById('removeCoverInput');
                                                    if (removeInput) {
                                                        removeInput.value = '1';
                                                    }

                                                    // Show confirmation message
                                                    const removedMsg = document.getElementById('imageRemovedMsg');
                                                    if (removedMsg) {
                                                        removedMsg.style.display = 'block';
                                                        // Auto-hide after 3 seconds
                                                        setTimeout(() => {
                                                            removedMsg.style.display = 'none';
                                                        }, 3000);
                                                    }

                                                    // Optional: Highlight upload area to encourage re-upload
                                                    const dropArea = document.getElementById('main-drop-area');
                                                    if (dropArea) {
                                                        dropArea.classList.add('border-warning');
                                                    }

                                                    console.log('Image removal flagged (remove_image set to "1").');
                                                } catch (error) {
                                                    console.error('Error in removeCoverImage:', error);
                                                    alert('Error removing image. Please refresh and try again.');
                                                }
                                            };

                                            // Drag-and-Drop + File Browse Support (new addition)
                                            const dropArea = document.getElementById('main-drop-area');
                                            const fileInput = document.getElementById('mainFileElem');
                                            const preview = document.getElementById('mainImagePreview');
                                            const removeInput = document.getElementById('removeCoverInput');

                                            if (dropArea && fileInput && preview && removeInput) {
                                                // Prevent default drag behaviors
                                                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                                                    dropArea.addEventListener(eventName, preventDefaults, false);
                                                });

                                                function preventDefaults(e) {
                                                    e.preventDefault();
                                                    e.stopPropagation();
                                                }

                                                // Visual feedback on drag
                                                ['dragenter', 'dragover'].forEach(eventName => {
                                                    dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
                                                });

                                                ['dragleave', 'drop'].forEach(eventName => {
                                                    dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'),
                                                        false);
                                                });

                                                // Handle file drop
                                                dropArea.addEventListener('drop', handleDrop, false);

                                                function handleDrop(e) {
                                                    const files = e.dataTransfer.files;
                                                    handleFiles(files);
                                                }

                                                // Handle file selection (browse/click)
                                                fileInput.addEventListener('change', function(e) {
                                                    handleFiles(e.target.files);
                                                });

                                                function handleFiles(files) {
                                                    if (files.length > 0) {
                                                        const file = files[0]; // Single file
                                                        if (file && file.type.startsWith('image/')) {
                                                            // Generate preview
                                                            const imgUrl = URL.createObjectURL(file);
                                                            preview.innerHTML = `
                        <div class="col-md-3 position-relative" id="imageContainer">
                            <img src="${imgUrl}" class="img-fluid rounded shadow-sm border" alt="Preview Image" id="previewMainImage">
                            <button type="button" class="close-btn position-absolute top-0 end-0 btn-close btn-close-white m-1" onclick="removePreviewImage()">
                                &times;
                            </button>
                        </div>
                    `;
                                                            preview.style.display = 'block';

                                                            // Reset removal flag (upload overrides)
                                                            removeInput.value = '0';

                                                            // Hide removal message
                                                            const removedMsg = document.getElementById('imageRemovedMsg');
                                                            if (removedMsg) removedMsg.style.display = 'none';

                                                            // Reset styles
                                                            dropArea.classList.remove('border-warning', 'highlight');
                                                            dropArea.classList.add('border-success');

                                                            console.log('New image previewed. Removal flag reset to "0".');
                                                        } else {
                                                            alert('Please select a valid image file (jpg, jpeg, png, webp).');
                                                            fileInput.value = ''; // Clear invalid
                                                        }
                                                    }
                                                }

                                                // Function to remove preview (for new uploads)
                                                window.removePreviewImage = function() {
                                                    const container = document.getElementById('imageContainer');
                                                    if (container) container.remove();
                                                    preview.style.display = 'none';
                                                    fileInput.value = ''; // Clear file
                                                    removeInput.value = '0'; // Reset flag
                                                    dropArea.classList.remove('border-success', 'border-warning', 'highlight');
                                                    dropArea.classList.add('border-secondary');
                                                    console.log('Preview cleared. Flag reset to "0".');
                                                };
                                            } else {
                                                console.warn('Image handling elements not found. Check IDs.');
                                            }
                                        });

                                        // Inline CSS for styling
                                        const style = document.createElement('style');
                                        preview.innerHTML = `<button type="button" class="close-btn position-absolute top-0 end-0 btn-close btn-close-white m-1" onclick="removePreviewImage()">
                                &times;
                            </button>
                    `;
                                        if (!document.querySelector('style[data-location-js-css]')) {
                                            style.setAttribute('data-location-js-css', 'true');
                                            document.head.appendChild(style);
                                        }
                                    </script>

                                    <div class="col-12 mt-5">
                                        <button type="submit" class="btn btn-primary-600">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>

            <x-auth-footer>

            </x-auth-footer>
        </main>
        <script src="js/add-loc-image.js"></script>
</x-auth-dashboard>
