

                // ✅ Defined outside DOMContentLoaded so clear button can access it
                const today = new Date().toISOString().split('T')[0];
                const past = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

                document.addEventListener('DOMContentLoaded', function () {

                    if (window._reportFormInitialized) return;
                    window._reportFormInitialized = true;
                    let currentType = null;
                    document.getElementById('startDate').value = '';
                    document.getElementById('endDate').value = '';

                    const allFilterRows = [
                        'filterItemRow', 'filterStatusRow', 'filterPositionRow',
                        'filterActivityUserRow', 'filterActivityTypeRow', 'filterActivityEventRow'
                    ];

                    function hideAll() {
                        allFilterRows.forEach(id => document.getElementById(id).style.display = 'none');
                    }

                    function show(id) {
                        document.getElementById(id).style.display = 'block';
                    }

                    function populateSelect(selectId, items) {
                        const select = document.getElementById(selectId);

                        // ✅ Destroy first to prevent duplication
                        $('#' + selectId).selectpicker('destroy');

                        select.innerHTML = '';

                        // ✅ Sort alphabetically
                        const sorted = [...items].sort((a, b) => a.name.localeCompare(b.name));

                        sorted.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;       // ✅ always use item.id
                            opt.textContent = item.name;     // ✅ always use item.name
                            select.appendChild(opt);
                        });

                        // ✅ Reinitialize fresh
                        $('#' + selectId).selectpicker();
                    }
                    document.getElementById('clearBtn').addEventListener('click', function () {
                        currentType = null;

                        document.getElementById('reportType').value = '';
                        $('#reportType').selectpicker('refresh');
                        $('#filterStatus').selectpicker('destroy');
                        document.getElementById('filterStatus').value = 'all';
                        $('#filterStatus').selectpicker();

                        hideAll();

                        ['filterItem', 'filterPosition',
                            'filterActivityUser', 'filterActivityType', 'filterActivityEvent'
                        ].forEach(id => {
                            document.getElementById(id).innerHTML = '';
                            $('#' + id).selectpicker('refresh');
                        });

                        document.getElementById('startDate').value = '';
                        document.getElementById('endDate').value = '';

                        // ✅ Reset table
                        document.getElementById('reportTableHead').innerHTML = '';
                        document.getElementById('reportTableBody').innerHTML = `
        <tr>
            <td colspan="10" class="text-center py-20 text-secondary-light">
                Select a report type and click Generate Report to view data.
            </td>
        </tr>
    `;
                    });

                    // Report type change
                    document.getElementById('reportType').addEventListener('change', function () {
                        currentType = this.value;
                        hideAll();

                        document.getElementById('filterItem').innerHTML = '';
                        $('#filterItem').selectpicker('destroy');
                        $('#filterItem').selectpicker();
                        document.getElementById('filterStatus').value = 'all';

                        if (['trees', 'locations', 'users'].includes(currentType)) {

                            // Swap Archive/Inactive label
                            const archiveOption = document.querySelector(
                                '#filterStatus option[value="archive"], #filterStatus option[value="inactive"]'
                            );
                            if (archiveOption) {
                                archiveOption.textContent = currentType === 'users' ? 'Inactive' : 'Archive';
                                archiveOption.value = currentType === 'users' ? 'inactive' : 'archive';
                            }

                            $('#filterStatus').selectpicker('destroy');
                            $('#filterStatus').selectpicker();
                            show('filterStatusRow');

                            // ✅ Auto-populate items with ALL items immediately
                            const allItems = reportData[currentType].items;
                            const sorted = [...allItems].sort((a, b) => a.name.localeCompare(b.name));

                            $('#filterItem').selectpicker('destroy');
                            document.getElementById('filterItem').innerHTML = '';
                            sorted.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.id;
                                opt.textContent = item.name;
                                document.getElementById('filterItem').appendChild(opt);
                            });
                            $('#filterItem').selectpicker();

                            document.getElementById('filterItemLabel').textContent = reportData[currentType].label;
                            show('filterItemRow'); // ✅ show items right away

                            if (currentType === 'users') show('filterPositionRow');
                        }

                        if (currentType === 'activity-log') {
                            show('filterActivityUserRow');
                            show('filterActivityTypeRow');
                            show('filterActivityEventRow');
                        }
                    });

                    // Form submit with loading
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

                        const formData = new FormData(this);
                        const filterItems = [...document.getElementById('filterItem').selectedOptions].map(o => o.value);
                        filterItems.forEach(v => formData.append('filter_items[]', v));

                        // ✅ Show loading
                        const overlay = document.getElementById('loadingOverlay');
                        overlay.style.display = 'flex';

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

                                head.innerHTML = tableHeaders[type] ?? '';

                                if (!data || data.length === 0) {
                                    body.innerHTML = `<tr><td colspan="10" class="text-center py-20">No data found.</td></tr>`;
                                    return;
                                }

                                body.innerHTML = data.map(item => renderRow(type, item)).join('');
                            })
                            .catch(err => {
                                console.error('Fetch error:', err);
                                document.getElementById('reportTableBody').innerHTML =
                                    `<tr><td colspan="10" class="text-center py-20 text-danger">Something went wrong. Check console.</td></tr>`;
                            })
                            .finally(() => {
                                // ✅ Hide loading when done
                                overlay.style.display = 'none';
                            });
                    });

                    document.getElementById('filterStatus').addEventListener('change', function () {
                        if (!currentType || !reportData[currentType]) return;

                        const selectedStatus = this.value;

                        // ✅ Only use .items array, never the statuses array
                        const allItems = reportData[currentType].items;

                        const filtered = selectedStatus === 'all' || selectedStatus === ''
                            ? allItems
                            : allItems.filter(item => item.status === selectedStatus);

                        // ✅ Only show filterItemRow if there are actual items
                        if (filtered.length === 0) {
                            document.getElementById('filterItem').innerHTML = '';
                            $('#filterItem').selectpicker('destroy');
                            $('#filterItem').selectpicker();
                            document.getElementById('filterItemRow').style.display = 'none';
                            return;
                        }

                        populateSelect('filterItem', filtered);
                        document.getElementById('filterItemLabel').textContent = reportData[currentType].label;
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

                        hideAll();

                        ['filterItem', 'filterPosition',
                            'filterActivityUser', 'filterActivityType', 'filterActivityEvent'
                        ].forEach(id => {
                            document.getElementById(id).innerHTML = '';
                            $('#' + id).selectpicker('refresh');
                        });

                        document.getElementById('startDate').value = past;
                        document.getElementById('endDate').value = today;
                    });

                });
                const tableHeaders = {
                    trees: `
        <tr>
            <th>Name</th>
            <th>Scientific Name</th>
            <th>Common Name</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>`,
                    locations: `
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>`,
                    users: `
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>`,
                    'activity-log': `
        <tr>
            <th>Type</th>
            <th>Event</th>
            <th>Action</th>
            <th>User</th>
            <th>Logged At</th>
        </tr>`,
                };

                function renderRow(type, item) {
                    switch (type) {
                        case 'trees':
                            return `<tr>
                <td>${item.name}</td>
                <td>${item.scientific_name ?? '—'}</td>
                <td>${item.common_name ?? '—'}</td>
                <td><span class="badge bg-${item.status === 'active' ? 'success' : 'warning'}-focus text-${item.status === 'active' ? 'success' : 'warning'}-main px-12 py-4 rounded-pill">${item.status}</span></td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
            </tr>`;

                        case 'locations':
                            return `<tr>
                <td>${item.name}</td>
                <td><span class="badge bg-${item.status === 'active' ? 'success' : 'warning'}-focus text-${item.status === 'active' ? 'success' : 'warning'}-main px-12 py-4 rounded-pill">${item.status}</span></td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
            </tr>`;

                        case 'users':
                            return `<tr>
                <td>
                    <span class="fw-bolder text-primary-light d-block">${item.first_name} ${item.middle_initial ? item.middle_initial + '.' : ''} ${item.last_name}</span>
                    <span class="text-sm text-secondary-light">${item.email}</span>
                </td>
                <td>${item.position ?? '—'}</td>
                <td><span class="badge bg-${item.status === 'active' ? 'success' : 'warning'}-focus text-${item.status === 'active' ? 'success' : 'warning'}-main px-12 py-4 rounded-pill">${item.status}</span></td>
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
                <td>${new Date(item.created_at).toLocaleString()}</td>
            </tr>`;

                        default:
                            return '';
                    }
                }

                // Intercept form submit
                document.getElementById('reportForm').addEventListener('submit', function (e) {
                    e.preventDefault();

                    // ✅ Sync RIGHT BEFORE submit
                    const reportType = document.getElementById('reportType').value;
                    const status = document.getElementById('filterStatus').value;

                    if (!reportType) {
                        alert('Please select a report type.');
                        return;
                    }

                    document.getElementById('inputReportType').value = reportType;
                    document.getElementById('inputStatus').value = status;

                    const formData = new FormData(this);

                    const filterItems = [...document.getElementById('filterItem').selectedOptions].map(o => o.value);
                    filterItems.forEach(v => formData.append('filter_items[]', v));

                    // Debug
                    console.log('Sending report_type:', reportType);
                    console.log('Sending status:', status);

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

                            head.innerHTML = tableHeaders[type] ?? '';

                            if (!data || data.length === 0) {
                                body.innerHTML = `<tr><td colspan="10" class="text-center py-20">No data found.</td></tr>`;
                                return;
                            }

                            body.innerHTML = data.map(item => renderRow(type, item)).join('');
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            document.getElementById('reportTableBody').innerHTML =
                                `<tr><td colspan="10" class="text-center py-20 text-danger">Something went wrong. Check console.</td></tr>`;
                        });
                }, { once: false });