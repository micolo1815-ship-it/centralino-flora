<x-auth-dashboard>
<title>Edit tree - Centralino Flora</title>
<style>
    .bootstrap-select .dropdown-menu {
        overflow: hidden !important;
    }
    .bootstrap-select .dropdown-menu .inner.show {
        max-height: 200px !important;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        display: block !important;
    }
</style>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">

<x-auth-sidebar></x-auth-sidebar>

<main class="dashboard-main">
    <x-auth-navbar-header></x-auth-navbar-header>

    <div class="dashboard-main-body">
        <x-auth-navbar-right>Trees</x-auth-navbar-right>

        <form action="{{ route('tree.update', $tree->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Tree Name</label>
                                <input value="{{ old('name', $tree->name) }}" type="text" name="name"
                                    class="form-control" placeholder="Enter Tree Name">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Scientific Name</label>
                                <input value="{{ old('scientific_name', $tree->scientific_name) }}" type="text"
                                    name="scientific_name" class="form-control" placeholder="Enter Scientific Name">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Common Name</label>
                                <input value="{{ old('common_name', $tree->common_name) }}" type="text"
                                    name="common_name" class="form-control" placeholder="Enter Common Name">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Local Name</label>
                                <input value="{{ old('local_name', $tree->local_name) }}" type="text"
                                    name="local_name" class="form-control" placeholder="Enter Local Name">
                            </div>

                            <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label">Location</label>
                                <select name="location_id[]" id="location" class="selectpicker form-control"
                                    multiple data-live-search="true" data-size="false" data-actions-box="true" title="Select Location">
                                    @php
                                        $selectedLocations = collect(old('location_id', $selectedLocations ?? []));
                                    @endphp
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->id }}"
                                            {{ $selectedLocations->contains($loc->id) ? 'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                        {{-- ✅ Store content in hidden input, NOT in the div --}}
                                        <div id="editor-2"></div>
                                        <input type="hidden" name="description" id="editor-2-input"
                                            value="{{ old('description', $tree->description) }}">
                                    </div>
                                </div>
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
                                        {{-- ✅ Store content in hidden input, NOT in the div --}}
                                        <div id="editor-3"></div>
                                        <input type="hidden" name="uses_filipino" id="editor-3-input"
                                            value="{{ old('uses_filipino', $tree->uses_filipino) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tree Facts (One Line White Space, One Bullet)</label>
                                <textarea name="tree_facts" class="form-control" rows="4"
                                    placeholder="Enter Tree Facts...">{{ old('tree_facts', $tree->tree_facts) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tagged Trees(One Line White Space, One Bullet)</label>
                                <textarea name="tagged_trees" class="form-control" rows="4"
                                    placeholder="Enter Tagged Trees...">{{ old('tagged_trees', $tree->tagged_trees) }}</textarea>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Domain</label>
                                <input value="{{ old('domain', $tree->domain) }}" type="text" name="domain"
                                    class="form-control" placeholder="Enter Domain">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Kingdom</label>
                                <input value="{{ old('kingdom', $tree->kingdom) }}" type="text" name="kingdom"
                                    class="form-control" placeholder="Enter Kingdom">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Phylum</label>
                                <input value="{{ old('phylum', $tree->phylum) }}" type="text" name="phylum"
                                    class="form-control" placeholder="Enter Phylum">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Class</label>
                                <input value="{{ old('class', $tree->class) }}" type="text" name="class"
                                    class="form-control" placeholder="Enter Class">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Order</label>
                                <input value="{{ old('order', $tree->order) }}" type="text" name="order"
                                    class="form-control" placeholder="Enter Order">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Family</label>
                                <input value="{{ old('family', $tree->family) }}" type="text" name="family"
                                    class="form-control" placeholder="Enter Family">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Genus</label>
                                <input value="{{ old('genus', $tree->genus) }}" type="text" name="genus"
                                    class="form-control" placeholder="Enter Genus">
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Species</label>
                                <input value="{{ old('species', $tree->species) }}" type="text" name="species"
                                    class="form-control" placeholder="Enter Species">
                            </div>

                            {{-- Cover Image --}}
                            <div class="col-12">
                                <label class="form-label">Main Image Upload</label>
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

                                {{-- ✅ remove_cover uses value="1" for boolean() to work --}}
                                <input type="hidden" name="remove_cover" id="removeCoverInput" value="0">

                                <div id="mainImagePreview" class="row mt-4 g-3">
                                    @if (!empty($tree->cover_image))
                                        <div class="col-md-3 position-relative" id="coverPreviewItem">
                                            <img src="{{ asset('storage/' . $tree->cover_image) }}"
                                                class="img-fluid rounded shadow-sm border" alt="Main Image">
                                            <button type="button" class="close-btn" onclick="removeCoverImage()">
                                                &times;
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Gallery --}}
                            <div class="col-12">
                                <label class="form-label">Image Gallery Upload</label>
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

                                @php
    $gallery = is_array($tree->image_gallery)
        ? $tree->image_gallery
        : json_decode($tree->image_gallery, true) ?? [];
    $gallery = array_filter($gallery);
@endphp

<div id="galleryPreview" class="row mt-4 g-3">
    @forelse ($gallery as $image)
        <div class="col-md-3 position-relative">
            <img src="{{ asset('storage/' . $image) }}"
                class="img-fluid rounded shadow-sm border" alt="Gallery Image">
            <button type="button" class="close-btn remove-gallery-btn"
                data-image="{{ $image }}">&times;</button>
        </div>
    @empty
        <p>No images in the gallery.</p>
    @endforelse
</div>

                                <div id="removedGallery"></div>
                            </div>

                            
                                    <div class="col-12" data-visible-for="program-chair,advisor,admin-it">
                                        <label class="form-label">Status</label>

                                        <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                            <input type="hidden" name="status" value="archive">

                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="switch1" name="status" value="active"
                                                {{ old('status', $tree->status ?? 'archive') === 'active' ? 'checked' : '' }}
                                                aria-checked="{{ old('status', $tree->status ?? 'archive') === 'active' ? 'true' : 'false' }}">

                                            <label
                                                class="form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="switch1" id="statusLabel">
                                                {{ old('status', $tree->status ?? 'archive') === 'active' ? 'Active' : 'Archived' }}
                                            </label>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const checkbox = document.getElementById('switch1');
                                            const statusLabel = document.getElementById('statusLabel');

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
                                        });
                                    </script>

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

{{-- Load Quill FIRST before other scripts --}}
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('js/add-tree-image.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Editor config — content comes from hidden input value, NOT the div innerHTML
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

        // ✅ Read existing content from hidden input (set via blade value attribute)
        const existingContent = hiddenInput ? hiddenInput.value : '';

        // Init Quill
        const quill = new Quill('#' + config.editorId, {
            theme:   'snow',
            modules: { toolbar: '#' + config.toolbarId },
            placeholder: config.placeholder
        });

        // ✅ Restore content into Quill after init
        if (existingContent && existingContent.trim() !== '') {
            quill.root.innerHTML = existingContent;
        }

        // Keep hidden input in sync on every keystroke
        quill.on('text-change', function () {
            if (hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });

        quillInstances[config.editorId] = quill;
    });

    // ✅ Final sync on form submit
    document.querySelector('form').addEventListener('submit', function () {
        editors.forEach(function (config) {
            const quill       = quillInstances[config.editorId];
            const hiddenInput = document.getElementById(config.inputId);
            if (quill && hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });
    });

    // Gallery remove button
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-gallery-btn')) {
            const image = e.target.getAttribute('data-image');
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'remove_gallery[]';
            input.value = image;
            document.getElementById('removedGallery').appendChild(input);
            e.target.closest('.col-md-3').remove();
        }
    });

});

// ✅ Set remove_cover to "1" so $request->boolean('remove_cover') returns true
function removeCoverImage() {
    document.getElementById('removeCoverInput').value = '1';
    const preview = document.getElementById('coverPreviewItem');
    if (preview) preview.remove();
}
</script>

</x-auth-dashboard>