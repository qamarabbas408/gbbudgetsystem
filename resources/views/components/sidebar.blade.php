{{-- Sidebar Navigation --}}
<aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white flex-shrink-0 relative flex flex-col h-screen">
    {{-- Logo & Brand Section --}}
    <div class="p-6 border-b border-blue-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm">
                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold leading-tight">GB PND Budget</h1>
                <p class="text-blue-200 text-xs mt-0.5">Planning & Development</p>
            </div>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 overflow-y-auto py-4" aria-label="Main navigation">
        <ul class="space-y-1 px-3">
            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}"
                    aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            {{-- ADP Report --}}
            <li>
                <a href="{{ route('reports.adpSummary') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.adpSummary') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}"
                    aria-current="{{ request()->routeIs('reports.adpSummary') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.adpSummary') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">ADP Report</span>
                </a>
            </li>

            {{-- SDG Report --}}
            <l  i>
                <a href="{{ route('reports.sdgSummary') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.sdgSummary') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}"
                    aria-current="{{ request()->routeIs('reports.sdgSummary') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.sdgSummary') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="font-medium">SDG Report</span>
                </a>
            </li>
             {{-- Sector Wise --}}
            <li>
                <a href="{{ route('reports.sectorSummary') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.sectorSummary') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.sectorSummary') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <span class="font-medium text-sm">Sector Analysis</span>
                </a>
            </li>

            {{-- Dept Analysis --}}
            <li>
                <a href="{{ route('reports.sectorDeptAnalysis') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.sectorDeptAnalysis') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.sectorDeptAnalysis') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium text-sm">Depart Analysis</span>
                </a>
            </li>

            {{-- Divider --}}
            <li class="pt-4 pb-2">
                <div class="border-t border-blue-700/50"></div>
            </li>

            {{-- Settings Section Label --}}
            <li class="px-3 pt-2 pb-1">
                <span class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Settings</span>
            </li>

            {{-- Mapping Rules --}}
            <li>
                <a href="{{ route('mappings.index') }}"
                    class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('mappings.*') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}"
                    aria-current="{{ request()->routeIs('mappings.*') ? 'page' : 'false' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mappings.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span class="font-medium">Mapping Rules</span>
                </a>
            </li>
            {{-- SAP Dump Lists --}}
<li>
    <a href="{{ route('sap.list') }}"
        class="group flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('sap.list') ? 'bg-white/10 border-l-4 border-white shadow-lg' : 'hover:bg-white/5' }}">
        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('sap.list') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <span class="font-medium text-sm">SAP Dump History</span>
    </a>
</li>
        </ul>
    </nav>

    {{-- User Profile Section --}}
    <div class="border-t border-blue-700/50 p-4 bg-blue-950/50">
        <div class="flex items-center space-x-3">
            {{-- User Avatar --}}
            <div class="relative">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center ring-2 ring-blue-500/30">
                    <span class="text-sm font-bold text-white">AD</span>
                </div>
                {{-- Online Status Indicator --}}
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-blue-950"></div>
            </div>

            {{-- User Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">Admin User</p>
                <p class="text-xs text-blue-300 truncate">admin@gbpnd.gov</p>
            </div>

            {{-- Dropdown Menu Button --}}
            <button type="button" class="p-1.5 rounded-lg hover:bg-white/5 transition-colors" aria-label="User menu"
                onclick="toggleUserMenu()">
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>

        {{-- User Dropdown Menu (Hidden by default) --}}
        <div id="userMenu" class="hidden mt-2 py-1 bg-blue-900 rounded-lg shadow-xl border border-blue-700/50">
            <a href="#"
                class="flex items-center px-3 py-2 text-sm text-blue-100 hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
            <a href="#"
                class="flex items-center px-3 py-2 text-sm text-blue-100 hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>
            <div class="border-t border-blue-700/50 my-1"></div>
            <a href="#"
                class="flex items-center px-3 py-2 text-sm text-red-300 hover:bg-red-500/10 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </a>
        </div>
    </div>
</aside>

@push('scripts')
    <script>
        /**
         * Toggle user dropdown menu
         */
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const button = event.target.closest('button[onclick="toggleUserMenu()"]');

            if (menu && !menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>
@endpush
