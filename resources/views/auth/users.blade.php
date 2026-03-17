<x-auth-dashboard>
    <title>Users - Centralino Flora</title>

    <body>

        <x-auth-sidebar>

        </x-auth-sidebar>

        <main class="dashboard-main">
            <x-auth-navbar-header>

            </x-auth-navbar-header>

            <div class="dashboard-main-body">
                <x-auth-navbar-right>
                    Users
                </x-auth-navbar-right>

                <div class="card h-100 p-0 radius-12">
                    <div
                        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <form id="filterForm" method="GET" action="{{ request()->url() }}"
                                class="d-flex align-items-center flex-wrap gap-3">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <input type="hidden" name="ypage" value="{{ request('ypage', 0) }}">

                                <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                                <select name="per_page" onchange="this.form.submit()"
                                    class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>

                                <div class="navbar-search">
                                    <input type="text" name="search" value="{{ $search }}"
                                        class="bg-base h-40-px w-auto" placeholder="Search">
                                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                                </div>

                                <select name="status" onchange="this.form.submit()"
                                    class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                                    <option value="">All Status</option>
                                    <option value="Activated" {{ $status === 'Activated' ? 'selected' : '' }}>Activated
                                    </option>
                                    <option value="Deactivated" {{ $status === 'Deactivated' ? 'selected' : '' }}>
                                        Deactivated</option>
                                </select>

                                <button type="submit" class="btn btn-primary-600 h-40-px px-16 radius-8">Search</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-24">
                        <div class="table-responsive scroll-md">
                            <table id="treeTable" class="table bordered-table sm-table mb-0">
                                <thead>
                                    <tr>
                                        <th data-column="0" class="sortable">User <span class="sort-icon"></span></th>
                                        <th data-column="1" class="sortable">Position <span class="sort-icon"></span>
                                        </th>
                                        <th data-column="2" class="sortable text-center">Status <span
                                                class="sort-icon"></span></th>
                                        <th data-column="3" class="sortable">Role <span class="sort-icon"></span></th>
                                        <th data-column="4" class="text-center">Action <span class="sort-icon"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @forelse($officers as $officer)
                                                                        @php $user = $officer->user; @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="{{ $user?->profile_image
                                            ? asset('storage/' . $user->profile_image)
                                            : ($officer->image_path
                                                ? asset('storage/' . $officer->image_path)
                                                : asset('images/avatar/blank-profile.png')) }}" alt=""
                                                                                        class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"
                                                                                        style="width:40px; height:40px; object-fit:cover; min-width:40px;">
                                                                                    <div class="flex-grow-1">
                                                                                        <span class="text-md mb-0 fw-bolder text-primary-light d-block">
                                                                                            {{ $officer->firstname }}
                                                                                            {{ $officer->middle_initial ? $officer->middle_initial . '.' : '' }}
                                                                                            {{ $officer->lastname }}
                                                                                        </span>
                                                                                        <span class="text-sm mb-0 fw-normal text-secondary-light d-block">
                                                                                            {{ $officer->email ?? '—' }}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>{{ $officer->position }}</td>
                                                                            <td class="text-center">
                                                                                @if($user?->status === 'active')
                                                                                    <span
                                                                                        class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">Activated</span>
                                                                                @elseif($user)
                                                                                    <span
                                                                                        class="bg-warning-focus text-warning-main px-32 py-4 rounded-pill fw-medium text-sm">Deactivated</span>
                                                                                @else
                                                                                    <span
                                                                                        class="bg-neutral-200 text-secondary-light px-32 py-4 rounded-pill fw-medium text-sm">No
                                                                                        Account</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ match ($officer->position) {
                                            'Program Chair' => 'Program Chair',
                                            'Adviser' => 'Adviser',
                                            default => 'Officer'
                                        } }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                @php
                                                                                    $pos = strtolower(str_replace(' ', '-', auth()->user()->position ?? ''));
                                                                                @endphp
                                                                                @if($user && in_array($pos, ['admin-it', 'program-chair', 'adviser']))
                                                                                    {{-- ✅ Only render route when $user exists --}}
                                                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle mx-auto">
                                                                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                                                                    </a>
                                                                                @elseif(!$user)
                                                                                    <span
                                                                                        class="bg-neutral-200 text-secondary-light px-12 py-4 rounded-pill text-sm">No
                                                                                        Account</span>
                                                                                @else
                                                                                    —
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-20">No officers found for
                                                {{ $selectedYear }}.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                            <span>
                                @if($status)
                                    Showing {{ $officers->count() }} {{ $status }} officer(s) across all years
                                @else
                                    Showing {{ $officers->count() }} officer(s) for {{ $selectedYear }}
                                @endif
                            </span>

                            @php
                                $years = $schoolYears->values();
                                $currentIndex = $years->search($selectedYear);
                                $totalYears = $years->count();
                                $pageSize = 5;

                                // ✅ Get ypage from URL — not calculated from currentIndex
                                $yearPage = (int) request('ypage', (int) floor($currentIndex / $pageSize));
                                $startIdx = $yearPage * $pageSize;
                                $endIdx = min($startIdx + $pageSize, $totalYears);
                                $visibleYears = $years->slice($startIdx, $pageSize);

                                $prevPageYear = $startIdx > 0;
                                $nextPageYear = $endIdx < $totalYears;
                            @endphp

                            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">

                                {{-- ✅ Prev --}}
                                <li class="page-item">
                                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md {{ !$prevPageYear ? 'opacity-50 pe-none' : '' }}"
                                        href="{{ $prevPageYear ? request()->fullUrlWithQuery(['ypage' => $yearPage - 1, 'year' => $selectedYear]) : 'javascript:void(0)' }}">
                                        <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                    </a>
                                </li>

                                {{-- ✅ Show 5 visible years --}}
                                @foreach($visibleYears as $year)
                                    <li class="page-item">
                                        <a class="page-link fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px px-12 text-md
                                                {{ $year === $selectedYear ? 'bg-primary-600 text-white' : 'bg-neutral-200 text-secondary-light' }}"
                                            href="{{ request()->fullUrlWithQuery(['year' => $year, 'ypage' => $yearPage]) }}">
                                            {{ $year }}
                                        </a>
                                    </li>
                                @endforeach

                                {{-- ✅ Next --}}
                                <li class="page-item">
                                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md {{ !$nextPageYear ? 'opacity-50 pe-none' : '' }}"
                                        href="{{ $nextPageYear ? request()->fullUrlWithQuery(['ypage' => $yearPage + 1, 'year' => $selectedYear]) : 'javascript:void(0)' }}">
                                        <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <x-auth-footer>

            </x-auth-footer>
        </main>
</x-auth-dashboard>