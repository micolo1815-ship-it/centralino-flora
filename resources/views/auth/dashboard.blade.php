<x-auth-dashboard>

    <title>Dashboard - Centralino Flora</title>

    <body>
        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Dashboard
                </x-auth-navbar-right>

                <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">

                    <x-div-col>
                        <div class="card shadow-none border bg-gradient-start-1 h-100">
                            <a href="/trees">
                                <x-div-card-body>
                                    <div>
                                        {{-- Shows the number of all trees active and archive status --}}
                                        <p class="fw-medium text-primary-light mb-1">Total Trees</p>
                                        <h6 class="mb-0">{{ $treesCount }}</h6>
                                    </div>
                                    <div
                                        class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="mdi:tree" class="text-white text-2xl mb-0"></iconify-icon>
                                    </div>
                                </x-div-card-body>
                            </a>
                        </div>
                    </x-div-col>

                    <x-div-col>
                        <div class="card shadow-none border bg-gradient-start-2 h-100">
                            <a href="/locations">
                                <x-div-card-body>
                                    <div>
                                        {{-- Shows the number of all locations active and archive status hihi --}}
                                        <p class="fw-medium text-primary-light mb-1">Total Locations</p>
                                        <h6 class="mb-0">{{ $locationCount }}</h6>
                                    </div>
                                    <div
                                        class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="mdi:location"
                                            class="text-white text-2xl mb-0"></iconify-icon>
                                    </div>
                                </x-div-card-body>
                            </a>
                        </div>
                    </x-div-col>

                    <x-div-col>
                        <div class="card shadow-none border bg-gradient-start-3 h-100">
                            <a href="#" disabled>
                                <x-div-card-body>
                                    <div>
                                        <p class="fw-medium text-primary-light mb-1">Total Species (Unavailable)</p>
                                        <h6 class="mb-0">-</h6>
                                    </div>
                                    <div
                                        class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="tabler:trees"
                                            class="text-white text-2xl mb-0"></iconify-icon>
                                    </div>
                                </x-div-card-body>
                            </a>
                        </div>
                    </x-div-col>

                    <x-div-col>
                        <div class="card shadow-none border bg-gradient-start-4 h-100">
                            <a href="/trees">
                                <x-div-card-body>
                                    <div>
                                        {{-- Shows the number of archive trees --}}
                                        <p class="fw-medium text-primary-light mb-1">Total Archive Trees</p>
                                        <h6 class="mb-0">{{ $archiveTreesCount }}</h6>
                                    </div>
                                    <div
                                        class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="material-symbols:archive"
                                            class="text-white text-2xl mb-0"></iconify-icon>
                                    </div>
                                </x-div-card-body>
                            </a>
                        </div><!-- card end -->
                    </x-div-col>

                    <x-div-col>
                        <div class="card shadow-none border bg-gradient-start-5 h-100">
                            <a href="/analytics">
                                <x-div-card-body>
                                    <div>
                                        <p class="fw-medium text-primary-light mb-1">Website Views</p>
                                        <h6 class="mb-0">{{ number_format($totalWebsiteViews) }}</h6>
                                    </div>
                                    <div
                                        class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
                                        <iconify-icon icon="mingcute:eye-2-fill"
                                            class="text-white text-2xl mb-0"></iconify-icon>
                                    </div>
                                </x-div-card-body>
                            </a>
                        </div><!-- card end -->
                    </x-div-col>
                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-0">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <h5 class="card-title mb-0">Trees</h5>
                                    <button type="button" data-visible-for="program-chair,advisor,admin-it"
                                        onclick="window.location.href='/trees/create'"
                                        class="btn btn-primary-600 radius-8 px-20 py-11 d-flex align-items-center gap-2">
                                        <iconify-icon icon="gg:add" class="text-xl"></iconify-icon> Add New Tree
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tree Name</th>
                                                <th scope="col">Locations</th>
                                                <th scope="col">Species</th>
                                                <th class="text-center" scope="col">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trees as $tree)
                                                <tr>
                                                    <td>{{ $tree->name }}</td>

                                                    <!-- Locations (comma-separated with links) -->
                                                    <td>
                                                        @php
                                                            $maxLength = 40; // Adjust this to your desired max characters (e.g., 30 for shorter, 100 for longer)
                                                            $allNames = $tree->locations->pluck('name')->toArray(); // Get all names as array
                                                            $fullList = implode(', ', $allNames); // Join with comma + space (e.g., "Main Park, Side Path")
                                                            $isTruncated = strlen($fullList) > $maxLength;

                                                            if ($isTruncated) {
                                                                // Truncate to limit
                                                                $truncated = substr($fullList, 0, $maxLength);
                                                                // Try to end on last comma for better flow (e.g., "Item1, Item2...")
                                                                if (($lastComma = strrpos($truncated, ',')) !== false) {
                                                                    $truncated =
                                                                        substr($truncated, 0, $lastComma) . '...';
                                                                } else {
                                                                    $truncated .= '...'; // Fallback: just add "..."
                                                                }
                                                                $displayList = $truncated;
                                                            } else {
                                                                $displayList = $fullList;
                                                            }
                                                        @endphp

                                                        {{ $displayList }}
                                                    </td>

                                                    <td>{{ $tree->species }}</td>

                                                    <!-- Status with color -->
                                                    <td class="text-center">
                                                        @if ($tree->status === 'active')
                                                            <span
                                                                class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">Active</span>
                                                        @else
                                                            <span
                                                                class="bg-warning-focus text-warning-main px-32 py-4 rounded-pill fw-medium text-sm">Archived</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div
                                                            class="d-flex align-items-center gap-10 justify-content-center">
                                                            <button type="button"
                                                                onclick="window.location.href='../Centralino Flora/Forestry/Trees/blackboard-tree.html'"
                                                                class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                                <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                                            </button>
                                                            <button type="button"
                                                                onclick="window.location.href='/trees/{{ $tree->id }}/edit'"
                                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                                <iconify-icon icon="lucide:edit"
                                                                    class="menu-icon"></iconify-icon>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer border-top-0">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <h5 class="card-title mb-0"></h5>
                                    <a href="/trees"
                                        class="btn btn-link text-secondary-light text-sm float-end d-inline-flex align-items-center gap-1">
                                        View All Trees
                                        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>

                    <div class="col-lg-12" data-visible-for="program-chair,admin-it">
                        <div class="card">
                            <div class="card-header border-bottom-0">
                                <h5 class="card-title mb-0">Activity Log</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Event</th>
                                                <th scope="col">Subject</th>
                                                <th scope="col">User</th>
                                                <th scope="col">Date & Time</th>
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
                                                                    data-bs-placement="right"
                                                                    data-bs-content="{{ $log->action }}"
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
                            </div>
                            <div class="card-footer border-top-0">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <h5 class="card-title mb-0"></h5>
                                    <a href="activity-log.html"
                                        class="btn btn-link text-secondary-light text-sm float-end d-inline-flex align-items-center gap-1">
                                        View More
                                        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                    <div class="col-lg-12" data-visible-for="Program Chair,Adviser,IT">
                        <div class="card">
                            <div class="card-header border-bottom-0">
                                <h5 class="card-title mb-0">Active Users</h5>
                            </div>
                            <div class="card-body"> {{-- ✅ removed p-0 --}}
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead> {{-- ✅ removed sticky style --}}
                                            <tr>
                                                <th scope="col">User</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($activeUsers as $user)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/avatar/blank-profile.png') }}"
                                                                                                        alt=""
                                                                                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"
                                                                                                        style="width:40px; height:40px; object-fit:cover; min-width:40px;">
                                                                                                    <div class="flex-grow-1">
                                                                                                        <span
                                                                                                            class="text-md mb-0 fw-bolder text-primary-light d-block">
                                                                                                            {{ $user->first_name }}
                                                                                                            {{ $user->middle_initial ? $user->middle_initial . '.' : '' }}
                                                                                                            {{ $user->last_name }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td>{{ $user->position }}</td>
                                                                                            <td>
                                                                                                <span
                                                                                                    class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">
                                                                                                    Active
                                                                                                </span>
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ match ($user->position) {
                                                    'Program Chair' => 'Program Chair',
                                                    'Adviser' => 'Adviser',
                                                    'IT' => 'IT',
                                                    default => 'Officer'
                                                } }}
                                                                                            </td>
                                                                                        </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-20">No active users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer border-top-0">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <h5 class="card-title mb-0"></h5>
                                    <a href="{{ route('users.index', ['status' => 'Activated']) }}"
                                        class="btn btn-link text-secondary-light text-sm float-end d-inline-flex align-items-center gap-1">
                                        View All Users
                                        <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card h-100 radius-8 border">
                            <div class="card-body p-24">
                                <h6 class="mb-12 fw-semibold text-lg mb-16">Weekly Viewer</h6>
                                <div class="d-flex align-items-center gap-2 mb-20">
                                    <h6 class="fw-semibold mb-0">{{ $totalViews }}</h6>
                                    <p class="text-sm mb-0">
                                        <span
                                            class="bg-danger-focus border br-danger px-8 py-2 rounded-pill fw-semibold text-danger-main text-sm d-inline-flex align-items-center gap-1">
                                            @if($totalViews > 0)
                                                {{ round(($perDayAverage / $totalViews) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                            <iconify-icon icon="iconamoon:arrow-down-2-fill"
                                                class="icon"></iconify-icon>
                                        </span>
                                        - {{ $perDayAverage }} Per Day
                                    </p>
                                </div>
                                <div id="barChart" class="barChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const weeklyData = @json($weeklyViews);
                const monthlyData = @json($monthlyData);
                const yearlyData = @json($yearlyData);
                const monthlyTotal = {{ $monthlyTotal }};
                const yearlyTotal = {{ $yearlyTotal }};

                document.addEventListener('DOMContentLoaded', function () {

                    // Bar chart
                    var barChart = new ApexCharts(document.querySelector("#barChart"), {
                        series: [{ name: "Views", data: weeklyData }],
                        chart: { type: 'bar', height: 235, toolbar: { show: false } },
                        plotOptions: { bar: { borderRadius: 6, horizontal: false, columnWidth: '52%' } },
                        dataLabels: { enabled: false },
                        fill: {
                            type: 'gradient',
                            colors: ['#dae5ff'],
                            gradient: {
                                shade: 'light', type: 'vertical', shadeIntensity: 0.5,
                                gradientToColors: ['#dae5ff'], inverseColors: false,
                                opacityFrom: 1, opacityTo: 1, stops: [0, 100],
                            },
                        },
                        grid: { show: false },
                        xaxis: { type: 'category', categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] },
                        yaxis: { show: false },
                    });
                    barChart.render();

                    // Line chart
                    var lineChart = new ApexCharts(document.querySelector("#chart"), {
                        series: [{ name: "Views", data: monthlyData }],
                        chart: {
                            height: 264, type: 'line',
                            toolbar: { show: false }, zoom: { enabled: false },
                            dropShadow: { enabled: true, top: 6, left: 0, blur: 4, color: "#000", opacity: 0.1 },
                        },
                        colors: ['#487FFF'],
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', colors: ['#487FFF'], width: 3 },
                        markers: { size: 0, strokeWidth: 3, hover: { size: 8 } },
                        tooltip: { enabled: true, x: { show: true }, y: { show: false } },
                        grid: { borderColor: '#D1D5DB', strokeDashArray: 3, row: { colors: ['transparent'], opacity: 0.5 } },
                        yaxis: { labels: { formatter: val => val, style: { fontSize: "14px" } } },
                        xaxis: {
                            categories: monthlyData.map(d => d.x),
                            tooltip: { enabled: false },
                            labels: { style: { fontSize: "14px" } },
                            axisBorder: { show: false },
                            crosshairs: { show: true, width: 20, stroke: { width: 0 }, fill: { type: 'solid', color: '#487FFF40' } }
                        }
                    });
                    lineChart.render();

                    // ✅ Dropdown now works — select has id="chartFilter"
                    document.getElementById('chartFilter').addEventListener('change', function () {
                        const isYearly = this.value === 'yearly';
                        const data = isYearly ? yearlyData : monthlyData;
                        const total = isYearly ? yearlyTotal : monthlyTotal;
                        const perDay = isYearly ? Math.round(total / 365) : Math.round(total / 30);
                        const percent = yearlyTotal > 0 ? Math.round((monthlyTotal / yearlyTotal) * 100) : 0;

                        lineChart.updateOptions({
                            series: [{ name: "Views", data: data }],
                            xaxis: { categories: data.map(d => d.x) }
                        });

                        document.getElementById('statTotal').textContent = total;
                        document.getElementById('statPercent').textContent = percent + '%';
                        document.getElementById('statLabel').textContent = `+ ${perDay} Viewer Per Day`;
                    });
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
            <x-auth-footer></x-auth-footer>
        </main>
</x-auth-dashboard>