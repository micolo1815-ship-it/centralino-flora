<x-auth-dashboard>

    <title>Analytics - Centralino Flora</title>

    <body>
        <x-auth-sidebar></x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header></x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>Analytics</x-auth-navbar-right>

                <div class="row gy-4">

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

                    <div class="col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                    <h6 class="text-lg mb-0">Viewer Statistics</h6>
                                    {{-- ✅ Added id="chartFilter" --}}
                                    <select id="chartFilter" class="form-select bg-base form-select-sm w-auto radius-8">
                                        <option value="monthly" selected>Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mt-8">
                                    {{-- ✅ Added IDs for JS to update --}}
                                    <h6 class="mb-0" id="statTotal">{{ $monthlyTotal }}</h6>
                                    <span
                                        class="text-sm fw-semibold rounded-pill bg-success-focus text-success-main border br-success px-8 py-4 line-height-1 d-flex align-items-center gap-1">
                                        <span id="statPercent">
                                            @if($monthlyTotal > 0)
                                                {{ round(($monthlyTotal / max($yearlyTotal, 1)) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        </span>
                                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                                    </span>
                                    {{-- ✅ Dynamic per day + id --}}
                                    <span class="text-xs fw-medium" id="statLabel">+ {{ round($monthlyTotal / 30) }}
                                        Viewer Per Day</span>
                                </div>
                                <div id="chart" class="pt-28 apexcharts-tooltip-style-1"></div>
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

                });
            </script>

            <x-auth-footer></x-auth-footer>
        </main>
</x-auth-dashboard>