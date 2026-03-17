
<x-auth-dashboard>
  <title>About - Centralino Flora</title>

<body>
    <x-auth-sidebar>

    </x-auth-sidebar>

    <main class="dashboard-main">
        <x-auth-navbar-header>

        </x-auth-navbar-header>

        <div class="dashboard-main-body">
            <x-auth-navbar-right>
                About
            </x-auth-navbar-right>

            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="card h-100 radius-12 bg-gradient-success">
                        <div class="card-body p-24">
                            <div
                                class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-success text-success-600 mb-16 radius-12">
                                <iconify-icon icon="material-symbols:person-edit" class="h5 mb-0"></iconify-icon>
                            </div>
                            <h6 class="mb-8">Edit Current Officers</h6>
                            <p class="card-text mb-8 text-secondary-light">Edit names and pictures of the current
                                officers this S.Y.</p>
                            <button onclick="window.location.href='/about/current_officers'"
                                class="btn btn-primary-600 px-12 py-10 d-inline-flex align-items-center gap-2">
                                <iconify-icon icon="iconamoon:arrow-left-2" class="text-xl"></iconify-icon>
                                Edit Current Officers
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100 radius-12 bg-gradient-purple text-center">
                        <div class="card-body p-24">
                            <div
                                class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-danger text-danger-600 mb-16 radius-12">
                                <iconify-icon icon="material-symbols:person-add-rounded"
                                    class="h5 mb-0"></iconify-icon>
                            </div>
                            <h6 class="mb-8">Add new S.Y. Officers</h6>
                            <p class="card-text mb-8 text-secondary-light">Add new and replace existing S.Y. officers.
                                <span class="text-sm">(for every new school year only.)</span>
                            </p>
                            {{-- Adding new set of officers every school year --}}
                            <button onclick="window.location.href='/about/create'"
                                class="btn btn-danger-600 px-12 py-10 d-inline-flex align-items-center gap-2">
                                Add New S.Y. Officers
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100 radius-12 bg-gradient-primary text-end">
                        <div class="card-body p-24">
                            <div
                                class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-primary text-info-600 text-white mb-16 radius-12">
                                <iconify-icon icon="f7:person-3-fill" class="h5 mb-0"></iconify-icon>
                            </div>
                            <h6 class="mb-8">View History of Previous S.Y. Officers</h6>
                            <p class="card-text mb-8 text-secondary-light">View or edit all previous S.Y. officers.</p>
                            <button onclick="window.location.href='/about/previous_officers'"
                                class="btn btn-info-600 px-12 py-10 d-inline-flex align-items-center gap-2">
                                View History of Previous S.Y. Officers
                                <iconify-icon icon="iconamoon:arrow-right-2" class="text-xl"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <x-auth-footer>

        </x-auth-footer>
    </main>

</x-auth-dashboard>