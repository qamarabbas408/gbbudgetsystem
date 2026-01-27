@extends('layouts.app')

@section('title', 'ADP Formulation 2025-26')
@section('hide_floating_btn', true)

@section('content')
    <div class="max-w-[1800px] mx-auto" id="adpFormulationContainer">
        
        {{-- Page Loader Overlay --}}
        <div id="pageLoader" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm mx-4">
                <div class="flex flex-col items-center">
                    <div class="relative w-16 h-16 mb-4">
                        <div class="absolute inset-0 border-4 border-blue-200 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-t-blue-600 rounded-full animate-spin"></div>
                    </div>
                    <p class="text-lg font-bold text-gray-800 mb-1" id="loaderTitle">Loading Schemes...</p>
                    <p class="text-sm text-gray-500" id="loaderSubtitle">Please wait</p>
                </div>
            </div>
        </div>

        {{-- Header Section --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    ADP Formulation <span class="text-blue-600">2025-26</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Comprehensive list of approved and un-approved development schemes.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button id="filterToggleBtn"
                    class="px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold shadow-sm hover:bg-gray-50 hover:border-blue-400 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filters
                    <span id="activeFilterBadge" class="hidden ml-1 px-2 py-0.5 bg-blue-600 text-white rounded-full text-xs font-bold"></span>
                </button>
                
                <button id="exportBtn"
                    class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:from-green-700 hover:to-green-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export to Excel
                </button>
                
                <a href=""
                    class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Scheme
                </a>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="filterPanel" class="hidden mb-6 bg-white rounded-xl border-2 border-gray-200 shadow-lg p-6 animate-slideDown">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Filter Schemes</h3>
                <button id="clearFiltersBtn" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Clear All
                </button>
            </div>
            
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Status</label>
                    <select name="status" id="statusFilter"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✓ Approved</option>
                        <option value="unapproved" {{ request('status') == 'unapproved' ? 'selected' : '' }}>✗ Un-Approved</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">District</label>
                    <select name="district" id="districtFilter"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                        <option value="">All Districts</option>
                                        @foreach ($districts as $dist)
                                <option value="{{ $dist }}" {{ request('district') == $dist ? 'selected' : '' }}>
                                    {{ $dist }}</option>
                            @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Sector</label>
                    <select name="sector" id="sectorFilter"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                        <option value="">All Sectors</option>
                        <option value="infrastructure" {{ request('sector') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                        <option value="education" {{ request('sector') == 'education' ? 'selected' : '' }}>Education</option>
                        <option value="health" {{ request('sector') == 'health' ? 'selected' : '' }}>Health</option>
                        <option value="agriculture" {{ request('sector') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">MLA/Halqa</label>
                    <input type="text" name="halqa" id="halqaFilter" placeholder="e.g., GBLA-1" value="{{ request('halqa') }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-md">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Stats Row --}}
        @php
            $totalSchemes = $stats['total'] ?? 0;
            $approvedSchemes = $stats['approved'] ?? 0;
            $unapprovedSchemes = $stats['unapproved'] ?? 0;
            $approvedPercentage = $totalSchemes > 0 ? number_format(($approvedSchemes / $totalSchemes) * 100, 1) : 0;
            $unapprovedPercentage = $totalSchemes > 0 ? number_format(($unapprovedSchemes / $totalSchemes) * 100, 1) : 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Schemes Card --}}
            <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50/50 p-6 rounded-2xl border-2 border-blue-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-wider mb-1">Total Schemes</p>
                        <p class="text-4xl font-black text-gray-800 mb-1">{{ number_format($totalSchemes) }}</p>
                        <span class="text-xs text-gray-500">All projects</span>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Approved Schemes Card --}}
            <div class="bg-gradient-to-br from-green-50 via-white to-green-50/50 p-6 rounded-2xl border-2 border-green-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-wider mb-1">Approved</p>
                        <p class="text-4xl font-black text-green-600 mb-1">{{ number_format($approvedSchemes) }}</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-green-600 font-bold">{{ $approvedPercentage }}%</span>
                            <span class="text-xs text-gray-500">of total</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Un-Approved Schemes Card --}}
            <div class="bg-gradient-to-br from-red-50 via-white to-red-50/50 p-6 rounded-2xl border-2 border-red-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-wider mb-1">Un-Approved</p>
                        <p class="text-4xl font-black text-red-500 mb-1">{{ number_format($unapprovedSchemes) }}</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-red-600 font-bold">{{ $unapprovedPercentage }}%</span>
                            <span class="text-xs text-gray-500">pending</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Allocation Card --}}
            <div class="bg-gradient-to-br from-purple-50 via-white to-purple-50/50 p-6 rounded-2xl border-2 border-purple-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-wider mb-1">Total Allocation</p>
                        <p class="text-4xl font-black text-purple-600 mb-1">₨{{ number_format($stats['allocation'] ?? 0) }}</p>
                        <span class="text-xs text-purple-600 font-bold">FY 2025-26</span>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and View Options --}}
        <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="relative flex-1 max-w-md">
                <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search by scheme name, ADP#, district..."
                    class="w-full pl-10 pr-10 py-2.5 border-2 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                {{-- Search loader --}}
                <div id="searchLoader" class="hidden absolute right-3 top-3">
                    <div class="w-5 h-5 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 font-bold">Rows per page:</span>
                <select id="perPageSelect"
                    class="px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>

        {{-- Main Table Container --}}
        <div class="bg-white rounded-2xl shadow-2xl border-2 border-gray-200 overflow-hidden relative">
            {{-- Table Loading Overlay --}}
            <div id="tableLoader" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm z-40 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-sm font-semibold text-gray-700">Updating table...</p>
                </div>
            </div>

            <div class="overflow-x-auto overflow-y-auto max-h-[700px] scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100">
                <table class="w-full text-left text-[11px] border-collapse min-w-[2400px]">
                    <thead class="bg-linear-to-r from-gray-900 via-gray-800 to-gray-900 text-white sticky top-0 z-20">
                        {{-- Main Header Row --}}
                        <tr class="border-b-2 border-gray-700">
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 sticky left-0 bg-gray-900 z-30 w-20">
                                ADP#
                            </th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 sticky left-20 bg-gray-900 z-30 min-w-[320px]">
                                Scheme Name
                            </th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Sector</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">District</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">MLA/Halqa</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">Appr. Date</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Targeted</th>
                            <th colspan="2" class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-blue-900/50">Est./Appr. Cost</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">Exp. Upto<br>06/2025</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Throw-<br>forward</th>
                            <th colspan="2" class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-green-900/50">Allocation<br>2025-26</th>
                            <th colspan="2" class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-purple-900/50">Revised<br>Allocation</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">Progressive<br>Release</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">Progressive<br>Expenditure</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Utilization</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">Exp. Beyond<br>2025-26</th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase text-center">Remarks</th>
                        </tr>
                        {{-- Sub Header Row --}}
                        <tr class="text-[10px]">
                            <th class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-blue-900/30">Total</th>
                            <th class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-blue-900/30">F.Aid</th>
                            <th class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-green-900/30">Total</th>
                            <th class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-green-900/30">F.Aid</th>
                            <th class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-purple-900/30">Total</th>
                            <th class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-purple-900/30">F.Aid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($schemes as $scheme)
                            @php
                                // Calculate SAP values (in millions)
                                $rawBudget = $scheme->sap_final_budget > 0 ? $scheme->sap_final_budget : $scheme->final_budget * 1000000;
                                $rawReleases = $scheme->sap_releases > 0 ? $scheme->sap_releases : $scheme->total_releases * 1000000;
                                $rawExp = $scheme->sap_expenditure > 0 ? $scheme->sap_expenditure : $scheme->total_expenditure * 1000000;
                                
                                $reviseAllocationM = $rawBudget / 1000000;
                                $totalReleasesM = $rawReleases / 1000000;
                                $totalExpM = $rawExp / 1000000;
                                $totalExpBeyond = $scheme->throw_forward - $reviseAllocationM;
                                $utilization = $totalReleasesM - $totalExpM;
                                $isTargetted = $totalExpBeyond <= 0;
                            @endphp
                            
                            <tr class="hover:bg-blue-50/70 transition-all group {{ $scheme->is_approved ? '' : 'bg-red-50/20' }}">
                                <td class="px-3 py-4 font-bold text-blue-700 sticky left-0 {{ $scheme->is_approved ? 'bg-white' : 'bg-red-50/20' }} group-hover:bg-blue-50/70 z-10 border-r border-gray-200">
                                    {{ $scheme->adp_no }}
                                </td>
                                <td class="px-3 py-4 font-semibold text-gray-900 sticky left-20 {{ $scheme->is_approved ? 'bg-white' : 'bg-red-50/20' }} group-hover:bg-blue-50/70 z-10 border-r border-gray-200">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="line-clamp-2" title="{{ $scheme->scheme_name }}">{{ $scheme->scheme_name }}</span>
                                        <button class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" onclick="editScheme({{ $scheme->id }})">
                                            <svg class="w-4 h-4 text-blue-600 hover:text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg text-[10px] font-bold whitespace-nowrap">
                                        {{ $scheme->sector_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-gray-800 font-semibold text-center">{{ $scheme->district_name ?? '-' }}</td>
                                <td class="px-3 py-4 text-gray-700 font-mono text-xs text-center">{{ $scheme->halqa_code ?? '-' }}</td>
                                <td class="px-3 py-4 text-center text-gray-700 font-mono text-xs">{{ $scheme->approval_date ?? '-' }}</td>
                                <td class="px-3 py-4 text-center">
                                    @if($isTargetted)
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full font-black text-[9px] uppercase inline-flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full font-black text-[9px] uppercase inline-flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-gray-900 font-bold bg-blue-50/30">{{ number_format($scheme->estimated_cost, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-blue-700 bg-blue-50/50">0</td>
                                <td class="px-3 py-4 text-right font-mono text-gray-700">{{ number_format($scheme->exp_upto_june, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-orange-600 font-bold">{{ number_format($scheme->throw_forward, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-green-700 font-bold bg-green-50/30">{{ number_format($scheme->original_allocation + $scheme->allocated_faid, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-green-600 bg-green-50/50">{{ number_format($scheme->allocated_faid, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-purple-700 font-bold bg-purple-50/30">{{ number_format($reviseAllocationM, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-purple-600 bg-purple-50/50">0</td>
                                <td class="px-3 py-4 text-right font-mono text-teal-700 font-bold">{{ number_format($totalReleasesM, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-teal-600 font-semibold">{{ number_format($totalExpM, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-indigo-600 font-semibold">{{ number_format($utilization, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-gray-600">{{ number_format($totalExpBeyond, 3) }}</td>
                                <td class="px-3 py-4 text-gray-700 text-xs">{{ $scheme->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No Schemes Found</p>
                                    <p class="text-sm mt-2">Try adjusting your filters or search query.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t-2 border-gray-200">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700 font-medium">
                        Showing <span class="font-bold text-blue-600">{{ $schemes->firstItem() ?? 0 }}</span> to
                        <span class="font-bold text-blue-600">{{ $schemes->lastItem() ?? 0 }}</span> of
                        <span class="font-bold text-blue-600">{{ number_format($schemes->total()) }}</span> schemes
                    </div>
                    <div class="flex gap-2">
                        {{ $schemes->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
/**
 * ADP Formulation UI Manager
 * Handles search, filters, loaders, and user interactions
 */
const ADPFormulation = (function() {
    'use strict';

    // Configuration
    const CONFIG = {
        SEARCH_DELAY: 500,  // milliseconds
        LOADER_MIN_DISPLAY: 300  // minimum time to show loader
    };

    // State
    let searchTimer = null;
    let loadingStartTime = 0;

    // DOM Elements
    const elements = {
        pageLoader: document.getElementById('pageLoader'),
        tableLoader: document.getElementById('tableLoader'),
        searchInput: document.getElementById('searchInput'),
        searchLoader: document.getElementById('searchLoader'),
        filterToggleBtn: document.getElementById('filterToggleBtn'),
        filterPanel: document.getElementById('filterPanel'),
        filterForm: document.getElementById('filterForm'),
        clearFiltersBtn: document.getElementById('clearFiltersBtn'),
        activeFilterBadge: document.getElementById('activeFilterBadge'),
        perPageSelect: document.getElementById('perPageSelect'),
        exportBtn: document.getElementById('exportBtn'),
        loaderTitle: document.getElementById('loaderTitle'),
        loaderSubtitle: document.getElementById('loaderSubtitle')
    };

    // Initialize
    function initialize() {
        setupEventListeners();
        updateActiveFilterBadge();
        
        // Hide page loader if it's visible
        hidePageLoader();
    }

    // Setup all event listeners
    function setupEventListeners() {
        // Search with debounce
        if (elements.searchInput) {
            elements.searchInput.addEventListener('input', handleSearch);
            elements.searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimer);
                    applySearch(this.value);
                }
            });
        }

        // Filter toggle
        if (elements.filterToggleBtn) {
            elements.filterToggleBtn.addEventListener('click', toggleFilterPanel);
        }

        // Filter form submit
        if (elements.filterForm) {
            elements.filterForm.addEventListener('submit', handleFilterSubmit);
        }

        // Clear filters
        if (elements.clearFiltersBtn) {
            elements.clearFiltersBtn.addEventListener('click', clearAllFilters);
        }

        // Per page change
        if (elements.perPageSelect) {
            elements.perPageSelect.addEventListener('change', handlePerPageChange);
        }

        // Export button
        if (elements.exportBtn) {
            elements.exportBtn.addEventListener('click', handleExport);
        }

        // Keyboard shortcuts
        setupKeyboardShortcuts();
    }

    // Handle search with debounce
    function handleSearch(e) {
        const searchTerm = e.target.value;
        
        // Show search loader
        if (searchTerm.length > 0) {
            elements.searchLoader?.classList.remove('hidden');
        }
        
        // Clear previous timer
        clearTimeout(searchTimer);
        
        // Set new timer
        searchTimer = setTimeout(() => {
            applySearch(searchTerm);
        }, CONFIG.SEARCH_DELAY);
    }

    // Apply search
    function applySearch(term) {
        showPageLoader('Searching...', 'Finding matching schemes');
        
        const url = new URL(window.location.href);
        
        if (term.length > 0) {
            url.searchParams.set('search', term);
        } else {
            url.searchParams.delete('search');
        }
        
        url.searchParams.set('page', 1);
        
        window.location.href = url.toString();
    }

    // Toggle filter panel
    function toggleFilterPanel() {
        elements.filterPanel?.classList.toggle('hidden');
    }

    // Handle filter form submit
    function handleFilterSubmit(e) {
        e.preventDefault();
        showPageLoader('Applying Filters...', 'Filtering schemes');
        
        // Small delay to show loader
        setTimeout(() => {
            e.target.submit();
        }, 100);
    }

    // Clear all filters
    function clearAllFilters() {
        const url = new URL(window.location.href);
        const searchParams = new URLSearchParams();
        
        // Keep only the 'per_page' parameter if it exists
        if (url.searchParams.has('per_page')) {
            searchParams.set('per_page', url.searchParams.get('per_page'));
        }
        
        showPageLoader('Clearing Filters...', 'Resetting view');
        window.location.href = `${url.pathname}?${searchParams.toString()}`;
    }

    // Handle per page change
    function handlePerPageChange(e) {
        const perPage = e.target.value;
        const url = new URL(window.location.href);
        
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        
        showPageLoader('Updating View...', `Loading ${perPage} rows per page`);
        window.location.href = url.toString();
    }

    // Handle export
    function handleExport() {
        const url = new URL(window.location.href);
        url.searchParams.set('export', 'excel');
        
        showPageLoader('Exporting Data...', 'Preparing Excel file');
        
        // Trigger download
        window.location.href = url.toString();
        
        // Hide loader after a delay
        setTimeout(() => {
            hidePageLoader();
        }, 2000);
    }

    // Update active filter badge
    function updateActiveFilterBadge() {
        const url = new URL(window.location.href);
        const params = ['status', 'district', 'sector', 'halqa'];
        let activeCount = 0;
        
        params.forEach(param => {
            if (url.searchParams.has(param) && url.searchParams.get(param) !== '') {
                activeCount++;
            }
        });
        
        if (activeCount > 0) {
            elements.activeFilterBadge.textContent = activeCount;
            elements.activeFilterBadge.classList.remove('hidden');
        } else {
            elements.activeFilterBadge.classList.add('hidden');
        }
    }

    // Show page loader
    function showPageLoader(title = 'Loading...', subtitle = 'Please wait') {
        loadingStartTime = Date.now();
        
        if (elements.pageLoader) {
            elements.loaderTitle.textContent = title;
            elements.loaderSubtitle.textContent = subtitle;
            elements.pageLoader.classList.remove('hidden');
        }
    }

    // Hide page loader
    function hidePageLoader() {
        const elapsed = Date.now() - loadingStartTime;
        const remaining = Math.max(0, CONFIG.LOADER_MIN_DISPLAY - elapsed);
        
        setTimeout(() => {
            elements.pageLoader?.classList.add('hidden');
            elements.searchLoader?.classList.add('hidden');
        }, remaining);
    }

    // Show table loader
    function showTableLoader() {
        elements.tableLoader?.classList.remove('hidden');
    }

    // Hide table loader
    function hideTableLoader() {
        elements.tableLoader?.classList.add('hidden');
    }

    // Setup keyboard shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + F for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                elements.searchInput?.focus();
            }
            
            // Ctrl/Cmd + K for filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                toggleFilterPanel();
            }
        });
    }

    // Edit scheme (global function)
    window.editScheme = function(id) {
        window.location.href = `/adp/formulation/${id}/edit`;
    };

    // Public API
    return {
        initialize,
        showPageLoader,
        hidePageLoader,
        showTableLoader,
        hideTableLoader
    };
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ADPFormulation.initialize();
});

// Show loader for pagination clicks
document.addEventListener('click', function(e) {
    const paginationLink = e.target.closest('a[href*="page="]');
    if (paginationLink) {
        ADPFormulation.showPageLoader('Loading Page...', 'Fetching schemes');
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Enhanced scrollbar styling */
    .scrollbar-thin::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: linear-gradient(to bottom, #f1f5f9, #e2e8f0);
        border-radius: 6px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #60a5fa, #3b82f6);
        border-radius: 6px;
        border: 2px solid #f1f5f9;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #3b82f6, #2563eb);
    }
    
    /* Smooth transitions */
    table thead th,
    table tbody td {
        transition: all 0.2s ease;
    }
    
    /* Slide down animation */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
    
    /* Line clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Pulse animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Fade in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    #pageLoader {
        animation: fadeIn 0.2s ease-out;
    }
</style>
@endpush