<x-auth-dashboard>
    <title>Add Location - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Create Location
                </x-auth-navbar-right>

                <div class="col-lg-12">
                    <div class="card">
                        <form action="{{ route('location.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label class="form-label">Location Name</label>
                                        <input type="text" name="location_name" class="form-control"
                                            placeholder="Enter Location Name" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Trees</label>
                                        <select name="trees[]" id="trees" class="selectpicker form-control" multiple
                                            data-live-search="true" data-actions-box="true" title="Select Trees">
                                            @foreach ($trees as $tree)
                                                <option value="{{ $tree->id }}">
                                                    {{ $tree->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Main Image Upload (File size: 5mb below only)</label>
                                        <div id="main-loc-drop-area"
                                            class="border border-success rounded p-4 text-center">
                                            <section class="upload-form">
                                                <input name="image" type="file" id="MainLocImage" accept="image/*"
                                                    hidden>
                                                <label for="MainLocImage" id="MainLocLabel" class="d-block">
                                                    <i class="ri-upload-cloud-2-line"
                                                        style="font-size: 2rem; color: #4caf50;"></i>
                                                    <p class="mt-2 mb-0">Drag & Drop your main image here<br><span
                                                            class="text-success fw-semibold">or click to browse</span>
                                                    </p>
                                                </label>
                                            </section>
                                        </div>
                                        <div id="mainLocImagePreview" class="row mt-4 g-3"></div>
                                    </div>

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
        <script src="{{ asset('js/add-loc-image.js') }}"></script>
</x-auth-dashboard>
