@extends('layouts.app')

@section('title', 'Sector & Department Analysis')
@section('hide_floating_btn', true)

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            {{-- Title Section --}}
            <div class="flex items-start space-x-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Sector & Department Analysis</h2>
                    <p class="text-sm text-gray-500 mt-1.5 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Snapshot Date:
                        <span class="font-bold text-indigo-600 ml-1">
                            {{ \Carbon\Carbon::parse($latestDate)->format('d-M-Y') }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Filter Controls --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Funding Stream Filter --}}
                <form action="{{ route('reports.sectorDeptAnalysis') }}" method="GET" id="filterForm">
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-30 group-hover:opacity-50 blur transition-opacity">
                        </div>
                        <div
                            class="relative flex items-center bg-white border-2 border-gray-200 rounded-xl px-4 py-2.5 shadow-sm hover:border-indigo-300 transition-all">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="text-xs font-bold text-gray-500 uppercase mr-3">Stream:</span>
                            <select name="type" onchange="this.form.submit()"
                                class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-indigo-600 bg-transparent p-0 pr-6 appearance-none">
                                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Projects</option>
                                <option value="adp" {{ $type == 'adp' ? 'selected' : '' }}>ADP Only</option>
                                <option value="sdg" {{ $type == 'sdg' ? 'selected' : '' }}>SDG Only</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </form>

                {{-- Unit Selector --}}
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl opacity-30 group-hover:opacity-50 blur transition-opacity">
                    </div>
                    <div
                        class="relative flex items-center bg-white border-2 border-gray-200 rounded-xl px-4 py-2.5 shadow-sm hover:border-blue-300 transition-all">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-bold text-gray-500 uppercase mr-3">Units:</span>
                        <select id="unitSelector" onchange="toggleCurrency()"
                            class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-blue-600 bg-transparent p-0 pr-6 appearance-none">
                            <option value="1">Actual PKR</option>
                            <option value="1000000">Millions (M)</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Export Button --}}
                <div class="flex space-x-3">
                    {{-- PDF Export --}}
                    <a id = 'exportPdfBtn' href="{{ route('reports.export', ['format' => 'pdf', 'type' => $type, 'batch_id' => request('batch_id')]) }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        PDF Export
                    </a>

                    {{-- Excel Export --}}
                   <a id="exportExcelBtn" href="{{ route('reports.export', ['format' => 'excel', 'type' => $type, 'batch_id' => request('batch_id')]) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 flex items-center shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Excel Export
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Table Header --}}
                <thead class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-gray-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Sector / Department
                            </div>
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-center text-gray-100">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Projects
                            </div>
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-right text-gray-100">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Final Budget
                            </div>
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-right text-gray-100">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Releases
                            </div>
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-right text-gray-100">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Expenditure
                            </div>
                        </th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-center text-gray-100">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Util %
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @php
                        // Initialize Grand Totals
                        $gtBudget = 0;
                        $gtRel = 0;
                        $gtExp = 0;
                        $gtProj = 0;
                    @endphp

                    @foreach ($sectors as $sector)
                        @php
                            // Calculate Sector Sub-Totals
                            $secBudget = $sector->departments->sum('sap_dumps_sum_final_budget') ?? 0;
                            $secRel = $sector->departments->sum('sap_dumps_sum_releases') ?? 0;
                            $secExp = $sector->departments->sum('sap_dumps_sum_expenditure') ?? 0;
                            $secProj = $sector->departments->sum('sap_dumps_count') ?? 0;
                            $secUtil = $secRel > 0 ? ($secExp / $secRel) * 100 : 0;

                            // Add to Grand Totals
                            $gtBudget += $secBudget;
                            $gtRel += $secRel;
                            $gtExp += $secExp;
                            $gtProj += $secProj;
                        @endphp

                        {{-- SECTOR HEADER ROW --}}
                        <tr
                            class="bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 border-t-2 border-indigo-200">
                            <td colspan="6" class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="font-black text-indigo-900 uppercase tracking-wide text-base">
                                        {{ $sector->name }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        {{-- DEPARTMENT ROWS --}}
                        @foreach ($sector->departments as $dept)
                            @php
                                $budget = $dept->sap_dumps_sum_final_budget ?? 0;
                                $rel = $dept->sap_dumps_sum_releases ?? 0;
                                $exp = $dept->sap_dumps_sum_expenditure ?? 0;
                                $util = $rel > 0 ? ($exp / $rel) * 100 : 0;
                                $utilColor =
                                    $util > 80 ? 'text-green-600' : ($util > 40 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <tr class="hover:bg-indigo-50/30 transition-all duration-200 group">
                                <td
                                    class="px-12 py-4 border-l-4 border-indigo-200 group-hover:border-indigo-400 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-indigo-400 rounded-full mr-3"></div>
                                        <div>
                                            <span class="font-semibold text-gray-900">{{ $dept->name }}</span>
                                            <span
                                                class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded">
                                                {{ $dept->abbreviation }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg font-bold text-gray-700">
                                        {{ $dept->sap_dumps_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-gray-900 curr-val"
                                    data-raw="{{ $budget }}">
                                    {{ number_format($budget) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-green-700 curr-val"
                                    data-raw="{{ $rel }}">
                                    {{ number_format($rel) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-blue-700 curr-val"
                                    data-raw="{{ $exp }}">
                                    {{ number_format($exp) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="font-bold {{ $utilColor }} text-base mb-1">
                                            {{ round($util, 1) }}%
                                        </span>
                                        <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full {{ $util > 80 ? 'bg-green-500' : ($util > 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                style="width: {{ min($util, 100) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- SECTOR TOTAL ROW --}}
                        <tr class="bg-gradient-to-r from-indigo-100 to-purple-100 font-bold border-b-2 border-indigo-300">
                            <td class="px-6 py-4">
                                <div class="flex items-center text-indigo-900">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="uppercase text-xs tracking-wider">Subtotal: {{ $sector->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-indigo-900">{{ $secProj }}</td>
                            <td class="px-6 py-4 text-right font-mono text-indigo-900 curr-val"
                                data-raw="{{ $secBudget }}">
                                {{ number_format($secBudget) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-green-800 curr-val"
                                data-raw="{{ $secRel }}">
                                {{ number_format($secRel) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-blue-800 curr-val"
                                data-raw="{{ $secExp }}">
                                {{ number_format($secExp) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-200 text-indigo-900 font-black rounded-lg">
                                    {{ round($secUtil, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- GRAND TOTAL FOOTER --}}
                <tfoot class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-t-4 border-indigo-600">
                    <tr>
                        <td class="px-6 py-5">
                            <div class="flex items-center text-white">
                                <div
                                    class="w-10 h-10 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-black uppercase text-base tracking-widest">Grand Total (Province)</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center font-black text-base text-white">{{ $gtProj }}</td>
                        <td class="px-6 py-5 text-right font-mono font-black text-base text-white curr-val"
                            data-raw="{{ $gtBudget }}">
                            {{ number_format($gtBudget) }}
                        </td>
                        <td class="px-6 py-5 text-right font-mono font-black text-green-400 text-base curr-val"
                            data-raw="{{ $gtRel }}">
                            {{ number_format($gtRel) }}
                        </td>
                        <td class="px-6 py-5 text-right font-mono font-black text-blue-300 text-base curr-val"
                            data-raw="{{ $gtExp }}">
                            {{ number_format($gtExp) }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span
                                class="inline-flex items-center justify-center px-4 py-2 bg-white/10 backdrop-blur text-white font-black text-base rounded-xl border border-white/20">
                                {{ $gtRel > 0 ? round(($gtExp / $gtRel) * 100, 1) : 0 }}%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            let currentDivisor = 1;

            /**
             * Format currency based on divisor
             */
            function formatCurrency(value) {
                if (isNaN(value)) return '0';

                if (currentDivisor === 1000000) {
                    return (value / 1000000).toFixed(2) + ' M';
                }

                return new Intl.NumberFormat('en-PK').format(value);
            }

            /**
             * Apply currency formatting to all cells
             */
            function applyCurrencyFormatting() {
                document.querySelectorAll('.curr-val').forEach(cell => {
                    const rawValue = parseFloat(cell.dataset.raw);
                    if (!isNaN(rawValue)) {
                        cell.textContent = formatCurrency(rawValue);
                    }
                });
            }

            /**
             * Toggle currency units
             */
            window.toggleCurrency = function() {
                const selector = document.getElementById('unitSelector');
                currentDivisor = parseFloat(selector.value);

                applyCurrencyFormatting();

                // 2. NEW: Update Export Button Links
                const exportExcelBtn = document.getElementById('exportExcelBtn');
                const exportPdfBtn = document.getElementById('exportPdfBtn');

                // This helper updates the 'unit' parameter in the URL
                updateExportLink(exportExcelBtn, currentDivisor);
                updateExportLink(exportPdfBtn, currentDivisor);



                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'info',
                        title: currentDivisor === 1000000 ? 'Units: Millions' : 'Units: Actual PKR'
                    });
                }
            };

            function updateExportLink(btn, unit) {
                if (!btn) return;
                let url = new URL(btn.href);
                url.searchParams.set('unit', unit);
               
                btn.href = url.toString();
                
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                applyCurrencyFormatting();
            });
        })();
    </script>
@endpush
