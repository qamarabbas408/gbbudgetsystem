@extends('layouts.app')

@section('title', 'ADP Formulation 2025-26')
@section('hide_floating_btn', true)

@section('content')
    <div class="max-w-[1800px] mx-auto">
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    ADP Formulation <span class="text-blue-600">2025-26</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Comprehensive list of approved and un-approved development schemes.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button id="filterBtn"
                    class="px-4 py-2.5 bg-white border-2 border-gray-300 rounded-xl text-sm font-semibold shadow-sm hover:bg-gray-50 hover:border-blue-400 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filters
                </button>
                <button id="exportBtn"
                    class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:from-green-700 hover:to-green-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export to Excel
                </button>
                <button
                    class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-semibold shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Scheme
                </button>
            </div>
        </div>

        {{-- Filter Panel (Hidden by default) --}}
        {{-- Filter Panel --}}
        <div id="filterPanel" class="hidden mb-6 bg-white rounded-xl border-2 border-gray-200 shadow-lg p-6">
            <form action="{{ route('adp.formulation') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">Status</label>
                        <select name="status" class="w-full px-3 py-2.5 border rounded-lg text-sm bg-white">
                            <option value="">All Statuses</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="unapproved" {{ request('status') == 'unapproved' ? 'selected' : '' }}>Un-Approved
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase">District</label>
                        <select name="district" class="w-full px-3 py-2.5 border rounded-lg text-sm bg-white">
                            <option value="">All Districts</option>
                            @foreach ($districts as $dist)
                                <option value="{{ $dist }}" {{ request('district') == $dist ? 'selected' : '' }}>
                                    {{ $dist }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Repeat for Sector using $sectors loop --}}

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold">Apply</button>
                        <a href="{{ route('adp.formulation') }}"
                            class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        @php
            $totalSchemes = $stats['total'] ?? 0;
            $approvedSchemes = $stats['approved'] ?? 0;
            $unapprovedSchemes = $stats['unapproved'] ?? 0;

            $approvedSchemesPercentage =
                $totalSchemes > 0 ? number_format(($approvedSchemes / $totalSchemes) * 100, 2) : 0;

            $unapprovedSchemesPercentage =
                $totalSchemes > 0 ? number_format(($unapprovedSchemes / $totalSchemes) * 100, 2) : 0;

        @endphp
        {{-- Stats Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-blue-50 via-white to-blue-50/50 p-6 rounded-2xl border-2 border-blue-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-wider mb-1">Total Schemes</p>
                        <p class="text-4xl font-black text-gray-800 mb-1">{{ number_format($stats['total']) }}</p>
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-gray-500">All projects</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 via-white to-green-50/50 p-6 rounded-2xl border-2 border-green-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-wider mb-1">Approved</p>
                        <p class="text-4xl font-black text-green-600 mb-1">{{ number_format($stats['approved']) }}</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-green-600 font-bold">{{ $approvedSchemesPercentage }}%</span>
                            <span class="text-xs text-gray-500">of total</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 via-white to-red-50/50 p-6 rounded-2xl border-2 border-red-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-wider mb-1">Un-Approved</p>
                        <p class="text-4xl font-black text-red-500 mb-1">{{ number_format($stats['unapproved']) }}</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-red-600 font-bold">{{ $unapprovedSchemesPercentage }}%</span>
                            <span class="text-xs text-gray-500">pending</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 via-white to-purple-50/50 p-6 rounded-2xl border-2 border-purple-100 shadow-md hover:shadow-xl transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-wider mb-1">Total Allocation
                        </p>
                        <p class="text-4xl font-black text-purple-600 mb-1">{{ number_format($stats['allocation']) }}</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                </path>
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-purple-600 font-bold">FY 2025-26</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and View Options --}}
        <div
            class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="relative flex-1 max-w-md">
                <input type="text" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search by scheme name, ADP#, district..."
                    class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 font-bold">Rows per page:</span>
                <select
                    class="px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option>10</option>
                    <option>25</option>
                    <option selected>50</option>
                    <option>100</option>
                    <option>All</option>
                </select>
            </div>
        </div>

        {{-- Main Table Container --}}
        <div class="bg-white rounded-2xl shadow-2xl border-2 border-gray-200 overflow-hidden">
            <div
                class="overflow-x-auto overflow-y-auto max-h-[700px] scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100">
                <table class="w-full text-left text-[11px] border-collapse min-w-[2000px]">
                    <thead class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white sticky top-0 z-20">
                        {{-- Main Header Row --}}
                        <tr class="border-b-2 border-gray-700">
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 sticky left-0 bg-gray-900 z-30 w-20">
                                <div class="flex items-center gap-2">
                                    ADP#
                                    <svg class="w-3 h-3 text-gray-400 cursor-pointer hover:text-white transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 sticky left-20 bg-gray-900 z-30 min-w-[320px]">
                                <div class="flex items-center gap-2">
                                    Scheme Name
                                    <svg class="w-3 h-3 text-gray-400 cursor-pointer hover:text-white transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Sector</th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">District</th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">MLA/Halqa
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Appr. Date</th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">Targeted</th>
                            <th colspan="2"
                                class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-blue-900/50">
                                Est./Appr. Cost
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Exp. Upto<br>06/2025
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center">
                                Throw-<br>forward
                            </th>
                            <th colspan="2"
                                class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-green-900/50">
                                Allocation<br>2025-26
                            </th>
                            <th colspan="2"
                                class="px-3 py-3 font-black uppercase border-r-2 border-gray-700 text-center bg-purple-900/50">
                                Revised<br>Allocation
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Progressive<br>Release
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Progressive<br>Expenditure
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Utilization
                            </th>
                            <th rowspan="2"
                                class="px-3 py-4 font-black uppercase border-r-2 border-gray-700 text-center whitespace-nowrap">
                                Exp. Beyond<br>2025-26
                            </th>
                            <th rowspan="2" class="px-3 py-4 font-black uppercase text-center">Remarks</th>
                        </tr>
                        {{-- Sub Header Row --}}
                        <tr class="text-[10px]">
                            <th class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-blue-900/30">
                                Total</th>
                            <th
                                class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-blue-900/30">
                                F.Aid</th>
                            <th class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-green-900/30">
                                Total</th>
                            <th
                                class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-green-900/30">
                                F.Aid</th>
                            <th
                                class="px-2 py-2 font-bold uppercase border-r border-gray-700 text-center bg-purple-900/30">
                                Total</th>
                            <th
                                class="px-2 py-2 font-bold uppercase border-r-2 border-gray-700 text-center bg-purple-900/30">
                                F.Aid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($schemes as $scheme)
                            @php
                                // Efficient logic: if SAP sum exists (>0), use it. Else use manual column.
                                $rawBudget =
                                    $scheme->sap_final_budget > 0
                                        ? $scheme->sap_final_budget
                                        : $scheme->final_budget * 1000000;
                                $rawReleases =
                                    $scheme->sap_releases > 0
                                        ? $scheme->sap_releases
                                        : $scheme->total_releases * 1000000;
                                $rawExp =
                                    $scheme->sap_expenditure > 0
                                        ? $scheme->sap_expenditure
                                        : $scheme->total_expenditure * 1000000;

                                // 2. Convert to Millions for display
                                $reviseAllocationM = $rawBudget / 1000000; //finalbudget in sap
                                $totalReleasesM = $rawReleases / 1000000;
                                $totalExpM = $rawExp / 1000000;
                                $totalExpBeyond = $scheme->throw_forward - $reviseAllocationM;
                                $utilization = $totalExpM > 0 ? $totalReleasesM - $totalExpM : 0;
                                $isTargetted = $totalExpBeyond <= 0 ? true : false;

                                // $reviseAllocation = finalBudgetM

                            @endphp
                            <tr
                                class="hover:bg-blue-50/70 transition-all group {{ $scheme->is_approved ? '' : 'bg-red-50/20' }}">
                                <td
                                    class="px-3 py-4 font-bold text-blue-700 sticky left-0 {{ $scheme->is_approved ? 'bg-white' : 'bg-red-50/20' }} group-hover:bg-blue-50/70 z-10 border-r border-gray-200">
                                    {{ $scheme->adp_no }}
                                </td>
                                <td
                                    class="px-3 py-4 font-semibold text-gray-900 sticky left-20 {{ $scheme->is_approved ? 'bg-white' : 'bg-red-50/20' }} group-hover:bg-blue-50/70 z-10 border-r border-gray-200">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="line-clamp-2"
                                            title="{{ $scheme->scheme_name }}">{{ $scheme->scheme_name }}</span>
                                        <button class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600 hover:text-blue-800" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg text-[10px] font-bold whitespace-nowrap">
                                        {{ $scheme->sector_code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-gray-800 font-semibold text-center">
                                    {{ $scheme->district_name ?? '-' }}</td>
                                <td class="px-3 py-4 text-gray-700 font-mono text-xs text-center">
                                    {{ $scheme->halqa_code ?? '-' }}</td>
                                <td class="px-3 py-4 text-center text-gray-700 font-mono text-xs">
                                    {{ $scheme->approval_date }}
                                </td>
                                <td class="px-3 py-4 text-center">
                                    @if ($isTargetted)
                                        <span
                                            class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full font-black text-[9px] uppercase inline-flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            Yes
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full font-black text-[9px] uppercase inline-flex items-center gap-1.5 shadow-sm">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-gray-900 font-bold bg-blue-50/30">
                                    {{ number_format($scheme->estimated_cost, 3) }}
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-gray-900 font-bold bg-blue-50/30">0</td>
                                <td class="px-3 py-4 text-right font-mono text-blue-700 font-semibold bg-blue-50/50">
                                    {{ number_format($scheme->exp_upto_june, 3) }}
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-gray-700">
                                    {{ number_format($scheme->throw_forward, 3) }}
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-green-600 font-bold bg-green-50/30">
                                    {{ number_format($scheme->original_allocation + $scheme->allocated_faid, 3) }}
                                </td>
                                <td class="px-3 py-4 text-right font-mono text-green-600 font-bold bg-green-50/30">
                                    {{ number_format($scheme->allocated_faid, 3) }}
                                </td>

                                <td
                                    class="px-3 py-4 text-right font-mono text-purple-600 font-bold bg-purple-50/30 font-semibold">
                                    {{ number_format($reviseAllocationM, 3) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-gray-700">
                                    0
                                </td>
                                <td
                                    class="px-3 py-4 text-right font-mono text-orange-600 font-bold bg-orange-50/30 font-semibold">
                                    {{ number_format($totalReleasesM, 3) }}</td>
                                <td
                                    class='px-3 py-4 text-right font-mono text-orange-600 font-bold bg-orange-50/30 font-semibold'>
                                    {{ number_format($totalExpM, 3) }}</td>
                                <td
                                    class='px-3 py-4 text-right font-mono text-orange-600 font-bold bg-orange-50/30 font-semibold'>
                                    {{ number_format($utilization, 3) }}</td>
                                <td
                                    class="px-3 py-4 text-right font-mono text-orange-600 font-bold bg-orange-50/30 font-semibold">
                                    {{ number_format($totalExpBeyond, 3) }}</td>
                                <td class="px-3 py-4 text-gray-700 text-xs">
                                    {{ $scheme->remarks ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No Schemes Found</p>
                                    <p class="text-sm">Upload ADP data or create a new scheme to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Enhanced Pagination --}}
            <div class="bg-gray-50 px-6 py-4 border-t-2 border-gray-200">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700 font-medium">
                        Showing <span class="font-bold text-blue-600">{{ $schemes->firstItem() }}</span> to
                        <span class="font-bold text-blue-600">{{ $schemes->lastItem() }}</span> of
                        <span class="font-bold text-blue-600">{{ number_format($schemes->total()) }}</span> schemes
                    </div>

                    {{-- Laravel Pagination Links --}}
                    <div class="flex">
                        {{ $schemes->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let searchTimer;
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value;

            // 1. Clear the timer if the user is still typing
            clearTimeout(searchTimer);

            // 2. Wait 500ms after typing stops before searching
            searchTimer = setTimeout(() => {
                applySearch(searchTerm);
            }, 500);
        });

        function applySearch(term) {
            // Get current URL and parameters
            let url = new URL(window.location.href);

            // Update the 'search' parameter
            if (term.length > 0) {
                url.searchParams.set('search', term);
            } else {
                url.searchParams.delete('search');
            }

            // Reset to page 1 when searching
            url.searchParams.set('page', 1);

            // Reload the page with the new search query
            window.location.href = url.toString();
        }

        // Allow pressing "Enter" to search immediately
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applySearch(this.value);
            }
        });
    </script>
@endpush
@push('scripts')
    <script>
        // Toggle Filter Panel with animation
        document.getElementById('filterBtn').addEventListener('click', function() {
            const filterPanel = document.getElementById('filterPanel');
            filterPanel.classList.toggle('hidden');
        });

        // Clear all filters
        document.getElementById('clearFilters').addEventListener('click', function() {
            document.querySelectorAll('#filterPanel select').forEach(select => {
                select.selectedIndex = 0;
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // Implement search logic here
        });

        // Export functionality
        document.getElementById('exportBtn').addEventListener('click', function() {
            console.log('Exporting to Excel...');
            // Implement Excel export here
            alert('Export to Excel functionality will be implemented');
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + F to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
            // Ctrl/Cmd + K to toggle filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('filterBtn').click();
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

        /* Slide down animation for filter panel */
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

        /* Line clamp for long text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Pulse animation for approved status */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
@endpush
