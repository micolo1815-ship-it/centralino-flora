<x-auth-dashboard>
    <title>Generate Report - Centralino Flora</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <x-auth-sidebar></x-auth-sidebar>

    <main class="dashboard-main">
        <x-auth-navbar-header></x-auth-navbar-header>

        <div class="dashboard-main-body">
            <x-auth-navbar-right>Generate Report</x-auth-navbar-right>

            {{-- Loading Overlay --}}
            <div id="loadingOverlay"
                style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
                <div class="text-center text-white">
                    <div class="spinner-border text-white mb-3" role="status" style="width:3rem; height:3rem;"></div>
                    <div class="fw-semibold fs-5">Generating Report...</div>
                </div>
            </div>

            <div class="row gy-4 mb-50">
                <div class="col-lg-12">

                    {{-- Filter Form --}}
                    <form method="POST" id="reportForm">
                        @csrf
                        <input type="hidden" name="report_type" id="inputReportType">
                        <input type="hidden" name="status" id="inputStatus">

                        <div class="card">
                            <div class="card-body">

                                {{-- Report Type --}}
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label class="form-label">Report Type</label>
                                        <select id="reportType" class="selectpicker form-control"
                                            data-live-search="true" title="Select Report Type">
                                            <option value="">Select</option>
                                            <option value="about">About</option>
                                            <option value="activity-log">Activity Log</option>
                                            <option value="analytics">Analytics</option>
                                            <option value="locations">Locations</option>
                                            <option value="trees">Trees</option>
                                            <option value="users">Users</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Status (trees, locations, users) --}}
                                <div class="row gy-3 mt-1" id="filterStatusRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Status</label>
                                        <select id="filterStatus" class="selectpicker form-control">
                                            <option value="all" selected>All</option>
                                            <option value="active">Active</option>
                                            <option value="archive">Archive</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Users: School Year --}}
                                <div class="row gy-3 mt-1" id="filterPositionRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">School Year</label>
                                        <select id="filterSchoolYear" class="selectpicker form-control"
                                            data-live-search="true" title="All School Years">
                                            <option value="all">All School Years</option>
                                            @foreach($schoolYears as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Items (trees, locations, users) --}}
                                <div class="row gy-3 mt-1" id="filterItemRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label" id="filterItemLabel">Select Items</label>
                                        <select id="filterItem" class="selectpicker form-control"
                                            data-live-search="true" data-actions-box="true"
                                            title="Select (All if empty)" multiple>
                                        </select>
                                    </div>
                                </div>

                                {{-- Activity Log: User --}}
                                <div class="row gy-3 mt-1" id="filterActivityUserRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Select User</label>
                                        <select id="filterActivityUser" class="selectpicker form-control"
                                            data-live-search="true" title="All Users">
                                            <option value="all">All Users</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->first_name }}
                                                    {{ $user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Activity Log: Type --}}
                                <div class="row gy-3 mt-1" id="filterActivityTypeRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Activity Type</label>
                                        <select id="filterActivityType" class="selectpicker form-control"
                                            data-actions-box="true" title="All Types" multiple>
                                            @foreach($activityTypes as $type)
                                                <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Activity Log: Event --}}
                                <div class="row gy-3 mt-1" id="filterActivityEventRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Event</label>
                                        <select id="filterActivityEvent" class="selectpicker form-control"
                                            data-actions-box="true" title="All Events" multiple>
                                            <option value="updated">Update</option>
                                            <option value="login">Login</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Analytics: Select Trees --}}
                                <div class="row gy-3 mt-1" id="filterAnalyticsTreeRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Select Trees</label>
                                        <select id="filterAnalyticsTree" class="selectpicker form-control"
                                            data-live-search="true" data-actions-box="true" title="All Trees" multiple>
                                            @foreach($treesWithVisits as $tree)
                                                <option value="{{ $tree->id }}">{{ $tree->name }} ({{ $tree->views_count }}
                                                    visits)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Analytics: Select Locations --}}
                                <div class="row gy-3 mt-1" id="filterAnalyticsLocationRow" style="display:none;">
                                    <div class="col-12">
                                        <label class="form-label">Select Locations</label>
                                        <select id="filterAnalyticsLocation" class="selectpicker form-control"
                                            data-live-search="true" data-actions-box="true" title="All Locations"
                                            multiple>
                                            @foreach($locationsWithVisits as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}
                                                    ({{ $location->views_count }} visits)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Date Range --}}
                                <div class="row gy-3 mt-1">
                                    <div class="col-xxl-2 col-lg-3 col-md-3 col-sm-4 col-12">
                                        <label class="form-label">Start Date <span
                                                class="text-muted text-sm fw-normal">(optional)</span></label>
                                        <input type="date" name="start_date" id="startDate" class="form-control">
                                    </div>
                                    <div class="col-xxl-2 col-lg-3 col-md-3 col-sm-4 col-12">
                                        <label class="form-label">End Date <span
                                                class="text-muted text-sm fw-normal">(optional)</span></label>
                                        <input type="date" name="end_date" id="endDate" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="button" id="clearBtn"
                                        class="btn btn-outline-secondary radius-8 px-20 py-11">Clear</button>
                                    <button type="submit" class="btn btn-success-600 mt-0">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Analytics Charts (hidden by default) --}}
                    <div id="analyticsChartSection" style="display:none;" class="mt-4">
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <div class="card radius-8 border p-24">
                                    <div id="analyticsBarChart"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card radius-8 border p-24">
                                    <div id="analyticsLineChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Results Table --}}
                    <div class="card h-100 p-0 radius-12 mt-4">
                        <div
                            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                            <div class="d-flex justify-content-start gap-3">
                                <button type="button" id="downloadBtn" class="btn btn-primary-500">Download PDF</button>
                            </div>
                        </div>
                        <div class="card-body p-24">
                            <div class="table-responsive scroll-md">
                                <table id="reportTable" class="table bordered-table sm-table mb-0">
                                    <thead id="reportTableHead"></thead>
                                    <tbody id="reportTableBody">
                                        <tr>
                                            <td colspan="10" class="text-center py-20 text-secondary-light">
                                                Select a report type and click Generate Report to view data.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>{{-- end dashboard-main-body --}}

        <x-auth-footer></x-auth-footer>
    </main>

    <script>
        const reportData = {
            trees: { items: @json($treesData), label: 'Select Trees' },
            locations: { items: @json($locationsData), label: 'Select Locations' },
            users: { items: @json($usersData), label: 'Select Users' },
        };

        const tableHeaders = {
            trees: `<tr><th>Name</th><th>Scientific Name</th><th>Common Name</th><th>Status</th><th>Created At</th></tr>`,
            locations: `<tr><th>Image</th><th>Name</th><th>Status</th><th>Trees</th><th>Created At</th></tr>`,
            users: `<tr><th>Name</th><th>Email</th><th>Position</th><th>Status</th><th>School Year</th><th>Created At</th></tr>`,
            'activity-log': `<tr><th>Type</th><th>Event</th><th>Action</th><th>User</th><th>Date</th><th>Time</th></tr>`,
            analytics: `<tr><th>Tree</th><th>Location</th><th>Total Visits</th><th>Last Visit</th></tr>`,
        };

        function renderRow(type, item) {
            const statusBadge = s =>
                `<span class="badge bg-${s === 'active' ? 'success' : 'warning'}-focus text-${s === 'active' ? 'success' : 'warning'}-main px-12 py-4 rounded-pill">${s}</span>`;

            switch (type) {
                case 'trees':
                    return `<tr>
                <td>${item.name}</td>
                <td>${item.scientific_name ?? '—'}</td>
                <td>${item.common_name ?? '—'}</td>
                <td>${statusBadge(item.status)}</td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
            </tr>`;

                case 'locations':
                    const locImg = item.image
                        ? `<img src="/storage/${item.image}" class="w-40-px h-40-px rounded object-fit-cover">`
                        : '—';
                    const trees = item.trees && item.trees.length
                        ? item.trees.map(t => {
                            const pivotStatus = t.pivot && t.pivot.status == 1
                                ? `<span class="badge bg-success-focus text-success-main px-8 py-2 rounded-pill">Active</span>`
                                : `<span class="badge bg-warning-focus text-warning-main px-8 py-2 rounded-pill">Inactive</span>`;
                            return `<span class="fw-medium">${t.name}</span> ${pivotStatus}`;
                        }).join('<br>')
                        : '—';
                    return `<tr>
        <td>${locImg}</td>
        <td>${item.name}</td>
        <td>${statusBadge(item.status)}</td>
        <td>${trees}</td>
        <td>${new Date(item.created_at).toLocaleString()}</td>
    </tr>`;

                case 'users':
                    const avatar = item.image_path ? `/storage/${item.image_path}` : '/images/avatar/blank-profile.png';
                    return `<tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <img src="${avatar}" alt="" class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        <span class="fw-bolder text-primary-light">
                            ${item.first_name} ${item.middle_initial ? item.middle_initial + '.' : ''} ${item.last_name}
                        </span>
                    </div>
                </td>
                <td>${item.email ?? '—'}</td>
                <td>${item.position ?? '—'}</td>
                <td>${statusBadge(item.status)}</td>
                <td>${item.school_year ?? '—'}</td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
            </tr>`;

                case 'activity-log':
                    return `<tr>
                <td><span class="text-success-main">${item.subject_type ? item.subject_type.split('\\').pop() : '—'}</span></td>
                <td>${item.event}</td>
                <td>${item.action}</td>
                <td>
                    <span class="fw-bolder text-primary-light d-block">${item.user ? item.user.first_name + ' ' + item.user.last_name : '—'}</span>
                    <span class="text-sm text-secondary-light">${item.user ? item.user.email : ''}</span>
                </td>
                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                <td>${new Date(item.created_at).toLocaleTimeString()}</td>
            </tr>`;

                case 'analytics':
                    return `<tr>
                <td>${item.tree ? item.tree.name : '—'}</td>
                <td>${item.location ? item.location.name : '—'}</td>
                <td><span class="bg-primary-focus text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">${item.total}</span></td>
                <td>${item.last_visit ? new Date(item.last_visit).toLocaleDateString() : '—'}</td>
            </tr>`;

                default:
                    return '';
            }
        }

        function renderAnalyticsCharts(data) {
            // Bar chart — visits per tree
            const treeLabels = data.map(d => d.tree ? d.tree.name : 'Unknown');
            const treeTotals = data.map(d => parseInt(d.total));

            if (window.barChartInstance) window.barChartInstance.destroy();
            window.barChartInstance = new ApexCharts(document.querySelector('#analyticsBarChart'), {
                series: [{ name: 'Visits', data: treeTotals }],
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                colors: ['#45B369'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
                dataLabels: { enabled: true },
                xaxis: { categories: treeLabels, labels: { rotate: -30, style: { fontSize: '12px' } } },
                yaxis: { labels: { formatter: val => Math.round(val) } },
                title: { text: 'Total Visits per Tree', style: { fontSize: '14px', fontWeight: 600 } },
                grid: { borderColor: '#D1D5DB', strokeDashArray: 3 },
            });
            window.barChartInstance.render();

            // Line chart — visits per location
            const locationMap = {};
            data.forEach(d => {
                const loc = d.location ? d.location.name : 'Unknown';
                locationMap[loc] = (locationMap[loc] || 0) + parseInt(d.total);
            });

            const locLabels = Object.keys(locationMap);
            const locTotals = Object.values(locationMap);

            if (window.lineChartInstance2) window.lineChartInstance2.destroy();
            window.lineChartInstance2 = new ApexCharts(document.querySelector('#analyticsLineChart'), {
                series: [{ name: 'Visits', data: locTotals }],
                chart: { type: 'line', height: 300, toolbar: { show: false } },
                colors: ['#487FFF'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 5 },
                dataLabels: { enabled: true },
                xaxis: { categories: locLabels, labels: { rotate: -30, style: { fontSize: '12px' } } },
                yaxis: { labels: { formatter: val => Math.round(val) } },
                title: { text: 'Total Visits per Location', style: { fontSize: '14px', fontWeight: 600 } },
                grid: { borderColor: '#D1D5DB', strokeDashArray: 3 },
            });
            window.lineChartInstance2.render();
        }

        document.addEventListener('DOMContentLoaded', function () {

            if (window._reportFormInitialized) return;
            window._reportFormInitialized = true;

            // Init all selectpickers once cleanly
            $('.selectpicker').each(function () {
                if (!$(this).data('selectpicker')) {
                    $(this).selectpicker();
                }
            });

            let currentType = null;

            const allFilterRows = [
                'filterItemRow', 'filterStatusRow', 'filterPositionRow',
                'filterActivityUserRow', 'filterActivityTypeRow', 'filterActivityEventRow',
                'filterAnalyticsTreeRow', 'filterAnalyticsLocationRow',
            ];

            const hideAll = () => allFilterRows.forEach(id => document.getElementById(id).style.display = 'none');
            const show = id => document.getElementById(id).style.display = 'block';

            function populateSelect(selectId, items) {
                const select = document.getElementById(selectId);
                select.innerHTML = [...items]
                    .sort((a, b) => a.name.localeCompare(b.name))
                    .map(item => `<option value="${item.id}">${item.name}</option>`)
                    .join('');

                if ($(select).data('selectpicker')) {
                    $(select).selectpicker('refresh');
                } else {
                    $(select).selectpicker();
                }
            }

            // ✅ resetTable defined at top level so both clear button and submit can use it
            function resetTable() {
                document.getElementById('reportTableHead').innerHTML = '';
                document.getElementById('reportTableBody').innerHTML =
                    `<tr><td colspan="10" class="text-center py-20 text-secondary-light">Select a report type and click Generate Report to view data.</td></tr>`;
                document.getElementById('analyticsChartSection').style.display = 'none';
                if (window.barChartInstance) window.barChartInstance.destroy();
                if (window.lineChartInstance2) window.lineChartInstance2.destroy();
            }

            // Report type change
            document.getElementById('reportType').addEventListener('change', function () {
                currentType = this.value;
                hideAll();

                // Reset items
                const filterItem = document.getElementById('filterItem');
                filterItem.innerHTML = '';
                if ($(filterItem).data('selectpicker')) $(filterItem).selectpicker('refresh');
                else $(filterItem).selectpicker();

                document.getElementById('filterStatus').value = 'all';

                if (['trees', 'locations', 'users'].includes(currentType)) {
                    // Swap Archive/Inactive label
                    const archiveOption = document.querySelector('#filterStatus option[value="archive"], #filterStatus option[value="inactive"]');
                    if (archiveOption) {
                        archiveOption.textContent = currentType === 'users' ? 'Inactive' : 'Archive';
                        archiveOption.value = currentType === 'users' ? 'inactive' : 'archive';
                    }
                    $('#filterStatus').selectpicker('destroy');
                    $('#filterStatus').selectpicker();
                    show('filterStatusRow');

                    if (currentType === 'users') {
                        document.getElementById('filterSchoolYear').value = 'all';
                        $('#filterSchoolYear').selectpicker('destroy');
                        $('#filterSchoolYear').selectpicker();
                        show('filterPositionRow');
                        populateSelect('filterItem', reportData.users.items);
                        document.getElementById('filterItemLabel').textContent = 'Select Users';
                        show('filterItemRow');
                    } else {
                        populateSelect('filterItem', reportData[currentType].items);
                        document.getElementById('filterItemLabel').textContent = reportData[currentType].label;
                        show('filterItemRow');
                    }
                }

                if (currentType === 'analytics') {
                    ['filterAnalyticsTree', 'filterAnalyticsLocation'].forEach(id => {
                        $('#' + id).selectpicker('destroy');
                        $('#' + id).selectpicker();
                    });
                    show('filterAnalyticsTreeRow');
                    show('filterAnalyticsLocationRow');
                }

                if (currentType === 'activity-log') {
                    ['filterActivityUser', 'filterActivityType', 'filterActivityEvent'].forEach(id => {
                        $('#' + id).selectpicker('destroy');
                        $('#' + id).selectpicker();
                    });
                    show('filterActivityUserRow');
                    show('filterActivityTypeRow');
                    show('filterActivityEventRow');
                }
            });

            // Status change
            document.getElementById('filterStatus').addEventListener('change', function () {
                if (!currentType || !reportData[currentType]) return;

                const selectedStatus = this.value;
                let filtered = selectedStatus === 'all' || selectedStatus === ''
                    ? reportData[currentType].items
                    : reportData[currentType].items.filter(item => item.status === selectedStatus);

                if (currentType === 'users') {
                    const selectedYear = document.getElementById('filterSchoolYear').value;
                    if (selectedYear && selectedYear !== 'all') {
                        filtered = filtered.filter(item => item.school_year === selectedYear);
                    }
                }

                if (filtered.length === 0) {
                    document.getElementById('filterItem').innerHTML = '';
                    $('#filterItem').selectpicker('refresh');
                    document.getElementById('filterItemRow').style.display = 'none';
                    return;
                }

                populateSelect('filterItem', filtered);
                document.getElementById('filterItemLabel').textContent = currentType === 'users' ? 'Select Users' : reportData[currentType].label;
                show('filterItemRow');
            });

            // School year change
            document.getElementById('filterSchoolYear').addEventListener('change', function () {
                if (currentType !== 'users') return;

                const selectedYear = this.value;
                const selectedStatus = document.getElementById('filterStatus').value;
                let allUsers = reportData.users.items;

                if (selectedStatus && selectedStatus !== 'all') {
                    allUsers = allUsers.filter(u => u.status === selectedStatus);
                }

                const filtered = selectedYear === 'all' || selectedYear === ''
                    ? allUsers
                    : allUsers.filter(u => u.school_year === selectedYear);

                if (filtered.length === 0) {
                    document.getElementById('filterItem').innerHTML = '';
                    $('#filterItem').selectpicker('refresh');
                    document.getElementById('filterItemRow').style.display = 'none';
                    return;
                }

                populateSelect('filterItem', filtered);
                document.getElementById('filterItemLabel').textContent = 'Select Users';
                show('filterItemRow');
            });

            // Clear button
            document.getElementById('clearBtn').addEventListener('click', function () {
                currentType = null;

                document.getElementById('reportType').value = '';
                $('#reportType').selectpicker('refresh');

                $('#filterStatus').selectpicker('destroy');
                document.getElementById('filterStatus').value = 'all';
                $('#filterStatus').selectpicker();

                $('#filterSchoolYear').selectpicker('destroy');
                document.getElementById('filterSchoolYear').value = 'all';
                $('#filterSchoolYear').selectpicker();

                hideAll();

                ['filterItem', 'filterActivityUser', 'filterActivityType', 'filterActivityEvent',
                    'filterAnalyticsTree', 'filterAnalyticsLocation'
                ].forEach(id => {
                    document.getElementById(id).innerHTML = '';
                    const el = document.getElementById(id);
                    if ($(el).data('selectpicker')) $(el).selectpicker('refresh');
                });

                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';

                resetTable();
            });

            // Form submit
            document.getElementById('reportForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const reportType = document.getElementById('reportType').value;
                const status = document.getElementById('filterStatus').value;

                if (!reportType) {
                    alert('Please select a report type.');
                    return;
                }

                document.getElementById('inputReportType').value = reportType;
                document.getElementById('inputStatus').value = status;

                // ✅ formData declared first
                const formData = new FormData(this);

                // Trees/Locations/Users items
                const filterItems = [...document.getElementById('filterItem').selectedOptions].map(o => o.value);
                filterItems.forEach(v => formData.append('filter_items[]', v));

                // School year (single select)
                const schoolYear = document.getElementById('filterSchoolYear').value;
                if (schoolYear && schoolYear !== 'all') {
                    formData.append('filter_school_years[]', schoolYear);
                }

                // Activity log user (single select)
                const activityUser = document.getElementById('filterActivityUser').value;
                if (activityUser && activityUser !== 'all') {
                    formData.append('filter_activity_user', activityUser);
                }

                // Analytics trees
                [...document.getElementById('filterAnalyticsTree').selectedOptions]
                    .forEach(o => formData.append('filter_items[]', o.value));

                // Analytics locations
                [...document.getElementById('filterAnalyticsLocation').selectedOptions]
                    .forEach(o => formData.append('filter_location[]', o.value));

                document.getElementById('loadingOverlay').style.display = 'flex';

                fetch("{{ route('reports.export') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Server error: ' + res.status);
                        return res.json();
                    })
                    .then(({ type, data }) => {
                        const head = document.getElementById('reportTableHead');
                        const body = document.getElementById('reportTableBody');

                        document.getElementById('analyticsChartSection').style.display =
                            type === 'analytics' ? 'block' : 'none';

                        head.innerHTML = tableHeaders[type] ?? '';

                        if (!data || data.length === 0) {
                            body.innerHTML = `<tr><td colspan="10" class="text-center py-20">No data found.</td></tr>`;
                            return;
                        }

                        const sorted = [...data].sort((a, b) => {
                            if (type === 'users') return (a.first_name + ' ' + a.last_name).localeCompare(b.first_name + ' ' + b.last_name);
                            if (type === 'activity-log') return new Date(b.created_at) - new Date(a.created_at);
                            if (type === 'analytics') return b.total - a.total;
                            return (a.name ?? '').localeCompare(b.name ?? '');
                        });

                        body.innerHTML = sorted.map(item => renderRow(type, item)).join('');

                        if (type === 'analytics') {
                            renderAnalyticsCharts(sorted);
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        document.getElementById('reportTableBody').innerHTML =
                            `<tr><td colspan="10" class="text-center py-20 text-danger">Something went wrong. Check console.</td></tr>`;
                    })
                    .finally(() => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                    });
            });
            document.getElementById('downloadBtn').addEventListener('click', function () {
                const reportType = document.getElementById('reportType').value;

                if (!reportType) {
                    alert('Please generate a report first.');
                    return;
                }

                // ✅ Build form data same as submit
                const formData = new FormData(document.getElementById('reportForm'));
                formData.set('report_type', reportType);
                formData.set('status', document.getElementById('filterStatus').value);

                const filterItems = [...document.getElementById('filterItem').selectedOptions].map(o => o.value);
                filterItems.forEach(v => formData.append('filter_items[]', v));

                const schoolYear = document.getElementById('filterSchoolYear').value;
                if (schoolYear && schoolYear !== 'all') formData.append('filter_school_years[]', schoolYear);

                const activityUser = document.getElementById('filterActivityUser').value;
                if (activityUser && activityUser !== 'all') formData.append('filter_activity_user', activityUser);

                [...document.getElementById('filterAnalyticsTree').selectedOptions].forEach(o => formData.append('filter_items[]', o.value));
                [...document.getElementById('filterAnalyticsLocation').selectedOptions].forEach(o => formData.append('filter_location[]', o.value));

                // ✅ Submit as a real form to trigger file download
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('reports.pdf') }}";

                for (const [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

        });
    </script>

</x-auth-dashboard>