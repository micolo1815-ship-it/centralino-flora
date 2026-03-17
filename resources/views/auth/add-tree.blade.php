<x-auth-dashboard>
<title>Add tree - Centralino Flora</title>

{{-- Quill CSS --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">
<style>
    .bootstrap-select .dropdown-menu .inner {
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    .bootstrap-select .dropdown-menu {
        overflow: hidden !important;
    }
</style>

<x-auth-sidebar></x-auth-sidebar>

<main class="dashboard-main">
    <x-auth-navbar-header></x-auth-navbar-header>

    <div class="dashboard-main-body">
        <x-auth-navbar-right>Trees</x-auth-navbar-right>

        <form action="{{ route('tree.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Tree Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter Tree Name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Scientific Name</label>
                                <input type="text" name="scientific_name" value="{{ old('scientific_name') }}"
                                    class="form-control @error('scientific_name') is-invalid @enderror"
                                    placeholder="Enter Scientific Name">
                                @error('scientific_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Common Name</label>
                                <input type="text" name="common_name" value="{{ old('common_name') }}"
                                    class="form-control @error('common_name') is-invalid @enderror"
                                    placeholder="Enter Common Name">
                                @error('common_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Local Name</label>
                                <input type="text" name="local_name" value="{{ old('local_name') }}"
                                    class="form-control @error('local_name') is-invalid @enderror"
                                    placeholder="Enter Local Name">
                                @error('local_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label">Location</label>
                                <select name="location_id[]" id="location" class="selectpicker form-control"
                                    multiple data-live-search="true" data-actions-box="true"
                                    data-size="5" title="Select Location">
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ collect(old('location_id', []))->contains($location->id) ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <div class="card basic-data-table radius-12 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div id="toolbar-container-2">
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-script" value="sub"></button>
                                                <button class="ql-script" value="super"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <button class="ql-indent" value="-1"></button>
                                                <button class="ql-indent" value="+1"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                        <div id="editor-2"></div>
                                        {{-- ✅ old value stored in hidden input --}}
                                        <input type="hidden" name="description" id="editor-2-input"
                                            value="{{ old('description') }}">
                                    </div>
                                </div>
                                @error('description') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Uses in Filipino Folklore --}}
                            <div class="col-12">
                                <label class="form-label">Uses in Filipino Folklore and Other Uses</label>
                                <div class="card basic-data-table radius-12 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div id="toolbar-container-3">
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-script" value="sub"></button>
                                                <button class="ql-script" value="super"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <button class="ql-indent" value="-1"></button>
                                                <button class="ql-indent" value="+1"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                        <div id="editor-3"></div>
                                        {{-- ✅ old value stored in hidden input --}}
                                        <input type="hidden" name="uses_filipino" id="editor-3-input"
                                            value="{{ old('uses_filipino') }}">
                                    </div>
                                </div>
                                @error('uses_filipino') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tree Facts (One Line White Space, One Bullet)
                                </label>
                                <textarea name="tree_facts" class="form-control @error('tree_facts') is-invalid @enderror"
                                    rows="4" placeholder="Enter Tree Facts...">{{ old('tree_facts') }}</textarea>
                                @error('tree_facts') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tagged Trees (One Line White Space, One Bullet)
                                </label>
                                <textarea name="tagged_trees" class="form-control @error('tagged_trees') is-invalid @enderror"
                                    rows="4" placeholder="Enter Tagged Trees...">{{ old('tagged_trees') }}</textarea>
                                @error('tagged_trees') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Domain</label>
                                <input type="text" name="domain" value="{{ old('domain') }}"
                                    class="form-control" placeholder="Enter Domain">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Kingdom</label>
                                <input type="text" name="kingdom" value="{{ old('kingdom') }}"
                                    class="form-control" placeholder="Enter Kingdom">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Phylum</label>
                                <input type="text" name="phylum" value="{{ old('phylum') }}"
                                    class="form-control" placeholder="Enter Phylum">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Class</label>
                                <input type="text" name="class" value="{{ old('class') }}"
                                    class="form-control" placeholder="Enter Class">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Order</label>
                                <input type="text" name="order" value="{{ old('order') }}"
                                    class="form-control" placeholder="Enter Order">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Family</label>
                                <input type="text" name="family" value="{{ old('family') }}"
                                    class="form-control" placeholder="Enter Family">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Genus</label>
                                <input type="text" name="genus" value="{{ old('genus') }}"
                                    class="form-control" placeholder="Enter Genus">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Species</label>
                                <input type="text" name="species" value="{{ old('species') }}"
                                    class="form-control" placeholder="Enter Species">
                            </div>

                            {{-- Cover Image --}}
                            <div class="col-12">
                                <label class="form-label">Main Image Upload (File size: 5MB below only)</label>
                                <div id="main-drop-area" class="border border-success rounded p-4 text-center">
                                    <section class="upload-form">
                                        <input type="file" name="cover_image" id="mainFileElem" accept="image/*" hidden>
                                        <label for="mainFileElem" id="mainFileLabel" class="d-block">
                                            <i class="ri-upload-cloud-2-line" style="font-size:2rem;color:#4caf50;"></i>
                                            <p class="mt-2 mb-0">Drag & Drop your main image here<br>
                                                <span class="text-success fw-semibold">or click to browse</span>
                                            </p>
                                        </label>
                                    </section>
                                </div>
                                @error('cover_image') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                                <div id="mainImagePreview" class="row mt-4 g-3"></div>
                            </div>

                            {{-- Gallery --}}
                            <div class="col-12">
                                <label class="form-label">Image Gallery Upload (File size: 5MB below only)</label>
                                <div id="drop-area" class="border border-success rounded p-4 text-center">
                                    <section class="upload-form">
                                        <input type="file" name="image_gallery[]" id="fileElem" multiple
                                            accept="image/*" hidden>
                                        <label for="fileElem" id="fileLabel" class="d-block">
                                            <i class="ri-upload-cloud-2-line" style="font-size:2rem;color:#4caf50;"></i>
                                            <p class="mt-2 mb-0">Drag & Drop your images here<br>
                                                <span class="text-success fw-semibold">or click to browse</span>
                                            </p>
                                        </label>
                                    </section>
                                </div>
                                @error('image_gallery.*') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
                                <div id="galleryPreview" class="row mt-4 g-3"></div>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary-600">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <x-auth-footer></x-auth-footer>
</main>

{{-- Quill JS loaded before init script --}}
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('js/add-tree-image.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const editors = [
        {
            editorId:    'editor-2',
            toolbarId:   'toolbar-container-2',
            inputId:     'editor-2-input',
            placeholder: 'Enter a Description...'
        },
        {
            editorId:    'editor-3',
            toolbarId:   'toolbar-container-3',
            inputId:     'editor-3-input',
            placeholder: 'Enter the Uses in Filipino Folklore and Other Uses...'
        }
    ];

    const quillInstances = {};

    editors.forEach(function (config) {
        const hiddenInput = document.getElementById(config.inputId);

        // ✅ Read old() value from hidden input before Quill init
        const existingContent = hiddenInput ? hiddenInput.value : '';

        const quill = new Quill('#' + config.editorId, {
            theme:   'snow',
            modules: { toolbar: '#' + config.toolbarId },
            placeholder: config.placeholder
        });

        // ✅ Restore old value into Quill after init
        if (existingContent && existingContent.trim() !== '') {
            quill.root.innerHTML = existingContent;
        }

        // Sync on every change
        quill.on('text-change', function () {
            if (hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });

        quillInstances[config.editorId] = quill;
    });

    // Final sync on submit
    document.querySelector('form').addEventListener('submit', function () {
        editors.forEach(function (config) {
            const quill       = quillInstances[config.editorId];
            const hiddenInput = document.getElementById(config.inputId);
            if (quill && hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });
    });

});
</script>

</x-auth-dashboard>