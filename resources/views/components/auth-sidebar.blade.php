<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="/dashboard" class="sidebar-logo">
            <img src="{{ asset('images/Logo/Centralino Flora.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('images/Logo/Centralino Flora White.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('images/Logo/Logo.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active-page' : '' }}">
                <a href="/dashboard">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('trees') || request()->is('trees/*') ? 'active-page' : '' }}">
                <a href="/trees">
                    <iconify-icon icon="entypo:tree" class="menu-icon"></iconify-icon>
                    <span>Trees</span>
                </a>
            </li>
            <li class="{{ request()->is('locations') || request()->is('locations/*') ? 'active-page' : '' }}">
                <a href="/locations">
                    <iconify-icon icon="mingcute:location-2-line" class="menu-icon"></iconify-icon>
                    <span>Locations</span>
                </a>
            </li>
            <li class="{{ request()->is('analytics') ? 'active-page' : '' }}">
                <a href="/analytics">
                    <iconify-icon icon="material-symbols:analytics-outline" class="menu-icon"></iconify-icon>
                    <span>Analytics</span>
                </a>
            </li>

            {{-- For Program Chair, Adviser and IT --}}
            @if(in_array(auth()->user()->position, ['Program Chair', 'IT', 'Adviser']))
                <li class="{{ request()->is('about') || request()->is('about/*') ? 'active-page' : '' }}">
                    <a href="/about">
                        <iconify-icon icon="streamline:information-desk" class="menu-icon"></iconify-icon>
                        <span>About</span>
                    </a>
                </li>
                <li class="{{ request()->is('users') || request()->is('users/*') ? 'active-page' : '' }}">
                    <a href="/users">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                </li>
            @endif

            {{-- For Program Chair and IT --}}
            @if(in_array(auth()->user()->position, ['Program Chair', 'IT']))
                <li class="{{ request()->is('activity-log') ? 'active-page' : '' }}">
                    <a href="/activity-log">
                        <iconify-icon icon="streamline:information-desk" class="menu-icon"></iconify-icon>
                        <span>Activity Log</span>
                    </a>
                </li>
            @endif

            {{-- For Program Chair --}}
            @if(in_array(auth()->user()->position, ['Program Chair']))
                <li class="{{ request()->is('report') ? 'active-page' : '' }}">
                    <a href="/report">
                        <iconify-icon icon="carbon:report" class="menu-icon"></iconify-icon>
                        <span>Generate Report</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>