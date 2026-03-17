<x-auth-dashboard>
  <title>Previous Officers - Centralino Flora</title>

  <x-auth-sidebar></x-auth-sidebar>

  <main class="dashboard-main">
    <x-auth-navbar-header></x-auth-navbar-header>

    <div class="dashboard-main-body">
      <x-auth-navbar-right>History of Previous S.Y. Officers</x-auth-navbar-right>

      <div class="card h-100 p-0 radius-12">
        <div
          class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
          <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <form method="GET" action="{{ request()->url() }}" id="filterForm" class="d-flex align-items-center gap-3">
              <input type="hidden" name="page" value="1">

              <select name="per_page" onchange="this.form.submit()"
                class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
              </select>

              <div class="navbar-search">
                <input type="text" name="search" value="{{ $search }}" class="bg-base h-40-px w-auto"
                  placeholder="Search school year...">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
              </div>

              <button type="submit" class="btn btn-primary-600 h-40-px px-16 radius-8">Search</button>
            </form>
          </div>
        </div>

        <div class="card-body p-24">
          <div class="table-responsive scroll-md">
            <table class="table bordered-table sm-table mb-0">
              <thead>
                <tr>
                  <th>School Year</th>
                  <th>Program Chair</th>
                  <th>Adviser</th>
                  <th>President</th>
                  <th>VP Internal</th>
                  <th>VP External</th>
                  <th>Secretary</th>
                  <th>Treasurer</th>
                  <th>Auditor</th>
                  <th>PRO</th>
                  <th>1st Rep</th>
                  <th>2nd Rep</th>
                  <th>3rd Rep</th>
                  <th>4th Rep</th>
                  <th>Last Updated</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pagedYears as $year)
                  @php
                    $officers = $allOfficers->get($year, collect());

                    // ✅ Map officers by position for easy lookup
                    $byPosition = $officers->keyBy('position');

                    $positions = [
                      'Program Chair',
                      'Adviser',
                      'President',
                      'Vice President Internal',
                      'Vice President External',
                      'Secretary',
                      'Treasurer',
                      'Auditor',
                      'PRO',
                      '1st Year Representative',
                      '2nd Year Representative',
                      '3rd Year Representative',
                      '4th Year Representative',
                    ];
                  @endphp
                  <tr>
                    <td class="fw-semibold text-primary-light">{{ $year }}</td>

                    @foreach($positions as $pos)
                      @php
                        $officer = $byPosition->get($pos);
                        $linkedUser = $officer ? $usersMap->get($officer->id) : null;
                        $imgPath = $linkedUser?->profile_image ?? $officer?->image_path ?? null;
                        $fullName = $officer
                          ? trim($officer->firstname . ' ' . ($officer->middle_initial ? $officer->middle_initial . '. ' : '') . $officer->lastname)
                          : '—';
                      @endphp
                      <td>
                        @if($officer)
                          <div class="d-flex align-items-center gap-2">
                            <span class="text-sm">{{ $fullName }}</span>
                          </div>
                        @else
                          <span class="text-secondary-light">—</span>
                        @endif
                      </td>
                    @endforeach

                    <td>
                      @php $lastUpdated = $lastUpdatedMap->get($year); @endphp
                      @if($lastUpdated)
                        <span class="text-sm text-primary-light fw-medium">
                          {{ \Carbon\Carbon::parse($lastUpdated)->format('M j, Y') }}
                        </span>
                        <span class="text-xs text-secondary-light d-block">
                          {{ \Carbon\Carbon::parse($lastUpdated)->format('g:i A') }}
                        </span>
                      @else
                        <span class="text-secondary-light">—</span>
                      @endif
                    </td>

                    <td class="text-center">
                      <div class="d-flex align-items-center gap-10 justify-content-center">
                        {{-- <a href="{{ route('home.historical_officers') }}"
                          class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                          title="View">
                          <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                        </a> --}}
                        @if(in_array(auth()->user()->position, ['Program Chair', 'IT', 'Adviser']))
                          <a href="{{ route('about.previous_edit', ['school_year' => $year]) }}"
                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                            title="Edit">
                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="15" class="text-center py-20">No previous officers found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Pagination --}}
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>
              Showing {{ ($page - 1) * $perPage + 1 }}–{{ min($page * $perPage, $total) }} of {{ $total }} school
              year(s)
            </span>
            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">

              {{-- Prev --}}
              <li class="page-item">
                <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md {{ $page <= 1 ? 'opacity-50 pe-none' : '' }}"
                  href="{{ $page > 1 ? request()->fullUrlWithQuery(['page' => $page - 1]) : 'javascript:void(0)' }}">
                  <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                </a>
              </li>

              {{-- Page numbers --}}
              @for($i = 1; $i <= $lastPage; $i++)
                <li class="page-item">
                  <a class="page-link fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md {{ $i === $page ? 'bg-primary-600 text-white' : 'bg-neutral-200 text-secondary-light' }}"
                    href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                    {{ $i }}
                  </a>
                </li>
              @endfor

              {{-- Next --}}
              <li class="page-item">
                <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md {{ $page >= $lastPage ? 'opacity-50 pe-none' : '' }}"
                  href="{{ $page < $lastPage ? request()->fullUrlWithQuery(['page' => $page + 1]) : 'javascript:void(0)' }}">
                  <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </div>

    <x-auth-footer></x-auth-footer>
  </main>

</x-auth-dashboard>