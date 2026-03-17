<x-auth-dashboard>
    <title>Activity Log - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>
            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Activity Log
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
                                class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                                <option value="">All Record</option>
                                <option value="Access" {{ request('status') == 'Access' ? 'selected' : '' }}>Access
                                </option>
                                <option value="Resource" {{ request('status') == 'Resource' ? 'selected' : '' }}>
                                    Resource</option>
                            </select>

                            <script>
                                document.getElementById('statusFilter').addEventListener('change', function () {
                                    const status = this.value;
                                    const url = new URL(window.location.href);

                                    if (status === "") {
                                        // If "All Record" is selected, remove the status parameter
                                        url.searchParams.delete('status');
                                    } else {
                                        // Otherwise, set the status
                                        url.searchParams.set('status', status);
                                    }

                                    url.searchParams.set('logs_page', 1); // Always reset to page 1 on filter change
                                    window.location.href = url.href;
                                });
                                // Combine all filters into one function
                                function updateFilters() {
                                    const status = document.getElementById('statusFilter').value;
                                    const search = document.getElementById('searchInput').value;
                                    const entries = document.getElementById('entriesPerPage').value;

                                    const url = new URL(window.location.href);
                                    if (status) url.searchParams.set('status', status);
                                    else url.searchParams.delete('status');
                                    if (search) url.searchParams.set('search', search);
                                    else url.searchParams.delete('search');
                                    url.searchParams.set('per_page', entries);
                                    url.searchParams.set('logs_page', 1); // Reset to page 1

                                    window.location.href = url.href;
                                }

                                // Add listeners
                                document.getElementById('statusFilter').addEventListener('change', updateFilters);
                                document.getElementById('entriesPerPage').addEventListener('change', updateFilters);

                                // Handle Search Form Submit
                                document.getElementById('searchForm').addEventListener('submit', function (e) {
                                    e.preventDefault();
                                    updateFilters();
                                });
                            </script>
                        </div>
                    </div>

                    <div class="card-body p-24">
                        <div class="table-responsive scroll-md">
                            <table id="treeTable" class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th data-column="0" class="sortable">Type <span class="sort-icon"></span></th>
                                        <th data-column="1" class="sortable">Event <span class="sort-icon"></span></th>
                                        <th data-column="2" class="sortable">Subject <span class="sort-icon"></span>
                                        </th>
                                        <th data-column="3" class="sortable">User <span class="sort-icon"></span></th>
                                        <th data-column="4" class="sortable">Date & Time <span class="sort-icon"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($activityLogs as $log)
                                        <tr>
                                            @php
                                                // Determine type name and class
                                                switch ($log->type_id) {
                                                    case 1:
                                                    case 2:
                                                        $typeName = 'Resource';
                                                        $typeClass = 'text-success-main';
                                                        break;
                                                    case 3:
                                                        $typeName = 'Access';
                                                        $typeClass = 'text-danger-main';
                                                        break;
                                                    default:
                                                        $typeName = 'N/A';
                                                        $typeClass = 'text-warning-main';
                                                }

                                                // Capitalize event first letter
                                                $eventName = ucfirst($log->event);

                                                // Prepare subject display variables
                                                $subjectName = $log->subjectModel->name ?? null;
                                                $subjectUrl = null;

                                                if ($log->subjectModel) {
                                                    // Build URL based on subject type
                                                    if ($log->subject_type === 'App\Models\Location') {
                                                        $subjectUrl = url("/locations/{$log->subject_id}/edit");
                                                    } elseif ($log->subject_type === 'App\Models\Tree') {
                                                        $subjectUrl = url("/trees/{$log->subject_id}/edit");
                                                    }
                                                }
                                            @endphp

                                            <td><span class="{{ $typeClass }}">{{ $typeName }}</span></td>

                                            {{-- Ayusin ung design ng icon button sa update --}}
                                            <style>
                                                .popover {
                                                    max-width: 280px !important;
                                                    font-size: 0.8rem !important;
                                                }

                                                .popover-body {
                                                    padding: 8px 12px !important;
                                                    font-size: 0.8rem !important;
                                                    word-break: break-word;
                                                }
                                            </style>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    @if($eventName === 'Updated')
                                                        <span class="text-primary-light">{{ $eventName }}</span>
                                                        <button type="button"
                                                            class="btn p-0 border-0 bg-transparent d-flex align-items-center justify-content-center"
                                                            data-bs-toggle="popover" data-bs-trigger="click"
                                                            data-bs-placement="right" data-bs-content="{{ $log->action }}"
                                                            style="width:22px; height:22px;">
                                                            <iconify-icon icon="mdi:information-circle-outline"
                                                                style="font-size:18px; color:#3b82f6; cursor:pointer;"></iconify-icon>
                                                        </button>
                                                    @else
                                                        <span class="text-primary-light">{{ $eventName }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                @if ($subjectName && $subjectUrl)
                                                    <a href="{{ $subjectUrl }}"
                                                        class="text-decoration-underline">{{ $subjectName }}</a>
                                                @elseif ($log->type_id == 3)
                                                    &mdash;
                                                @else
                                                    Undentified
                                                @endif
                                            </td>

                                            <td>
                                                @if ($log->user)
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $log->user->avatar_url }}"
                                                            alt="{{ $log->user->full_name }}"
                                                            class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                                        <div class="flex-grow-1">
                                                            <span
                                                                class="text-md mb-0 fw-bolder text-primary-light d-block">{{ $log->user->full_name }}</span>
                                                            <span
                                                                class="text-sm mb-0 fw-normal text-secondary-light d-block">{{ $log->user->position ?? 'Undefined' }}</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    &mdash;
                                                @endif
                                            </td>
                                            <td>{{ $log->created_at->format('M j, D - g:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <x-div-page-item>
                            <span id="entriesInfo">
                                Showing {{ $activityLogs->firstItem() }}
                                to {{ $activityLogs->lastItem() }}
                                of {{ $activityLogs->total() }} entries
                            </span>
                            {{ $activityLogs->links('vendor.pagination.custom') }}
                        </x-div-page-item>
                    </div>
                </div>
            </div>

            <x-auth-footer>

            </x-auth-footer>
        </main>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
                    const pop = new bootstrap.Popover(el, {
                        trigger: 'click',
                        html: false,
                    });

                    // ✅ Auto hide after 3 seconds when shown
                    el.addEventListener('shown.bs.popover', function () {
                        setTimeout(() => {
                            pop.hide();
                        }, 3000);
                    });
                });

                // Close when clicking outside
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('[data-bs-toggle="popover"]')) {
                        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
                            const pop = bootstrap.Popover.getInstance(el);
                            if (pop) pop.hide();
                        });
                    }
                });
            });
        </script>
</x-auth-dashboard>