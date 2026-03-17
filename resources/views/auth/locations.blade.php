<x-auth-dashboard>

    <title>Locations - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Locations
                </x-auth-navbar-right>

                <div class="card h-100 p-0 radius-12">
                    <div
                        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                            <select id="entriesPerPage"
                                class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>

                            <form id="searchForm" class="navbar-search">
                                <input type="text" id="searchInput" class="bg-base h-40-px w-auto" name="search"
                                    placeholder="Search">
                                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                            </form>

                            <select id="statusFilter"
                                class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                                onchange="window.location.href='?status=' + this.value">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archive" {{ $status === 'archive' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <a onclick="window.location.href='/locations/create'"
                            class="btn btn-primary-600 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                            Add New Location
                        </a>
                    </div>

                    <div class="card-body p-24">
                        <div class="table-responsive scroll-md">
                            <table id="treeTable" class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th data-column="0" class="sortable">Location Name <span
                                                class="sort-icon"></span></th>
                                        {{-- <th data-column="1" class="sortable">Trees <span class="sort-icon"></span>
                                        </th> --}}
                                        <th data-column="2" class="sortable text-center">Status <span
                                                class="sort-icon"></span></th>
                                        <th data-column="3" class="sortable"
                                            data-visible-for="program-chair,advisor,admin-it">Edited By <span
                                                class="sort-icon"></span></th>
                                        <th data-column="4" class="sortable"
                                            data-visible-for="program-chair,advisor,admin-it">Edited At <span
                                                class="sort-icon"></span></th>
                                        <th data-column="5" class="sortable"
                                            data-visible-for="program-chair,advisor,admin-it">Created By <span
                                                class="sort-icon"></span></th>
                                        <th data-column="6" class="sortable"
                                            data-visible-for="program-chair,advisor,admin-it">Created At <span
                                                class="sort-icon"></span></th>
                                        <th data-column="7" class="text-center">Action <span class="sort-icon"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($locations as $location)
                                        <tr>
                                            <td>{{ $location['name'] }}</td>
                                            {{-- <td> TREEEEES </td> --}}
                                            <td class="text-center">
                                                @if (optional($location)->status === 'active')
                                                    <span
                                                        class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm"
                                                        role="status" aria-label="Active">
                                                        Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="bg-warning-focus text-warning-main px-32 py-4 rounded-pill fw-medium text-sm"
                                                        role="status" aria-label="Archived">
                                                        Archived
                                                    </span>
                                                @endif
                                            </td>
                                            <td data-visible-for="program-chair,advisor,admin-it">
                                                {{ optional($location->updatedBy)->full_name ?? '—' }}
                                            </td>
                                            <td data-visible-for="program-chair,advisor,admin-it">
                                                {{ $location->updated_at ? $location->updated_at->format('n/j/Y - h:i A') : '—' }}
                                            </td>
                                            <td data-visible-for="program-chair,advisor,admin-it">
                                                {{ optional($location->createdBy)->full_name ?? '—' }}
                                            </td>
                                            <td data-visible-for="program-chair,advisor,admin-it">
                                                {{ $location->updated_at ? $location->updated_at->format('n/j/Y - h:i A') : '—' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                                    {{-- <button type="button"
                                                        onclick="window.location.href='../Centralino Flora/Forestry/bed.html'"
                                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                                    </button> --}}
                                                    <button type="button"
                                                        onclick="window.location.href='/locations/{{ $location['id'] }}/edit'"
                                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <x-div-page-item>

                        </x-div-page-item>
                    </div>
                </div>

            </div>

            <x-auth-footer>

            </x-auth-footer>
        </main>
</x-auth-dashboard>