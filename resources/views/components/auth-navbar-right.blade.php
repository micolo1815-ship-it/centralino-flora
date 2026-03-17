<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0"> {{ $slot }} </h6>
    <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
            <div class="d-flex align-items-center gap-1">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </div>
        </li>
        @if (trim(strtolower($slot)) !== 'dashboard')
            <li>-</li>
            <li class="fw-medium">
                {{ $slot }}
                @if (request()->is('localhost:8000/locations/create'))
                    - Create
                @endif

                {{-- 
                Problem here
                --}}
                
            </li>
        @endif
    </ul>
</div>
