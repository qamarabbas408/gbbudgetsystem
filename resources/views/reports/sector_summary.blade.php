@extends('layouts.app')

@section('title', 'Sector-wise Budget Summary')

@section('content')
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            {{-- Title Section --}}
            <div class="flex items-start space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Sector-wise Analysis</h2>
                    <p class="text-sm text-gray-500 mt-1.5 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Snapshot: 
                        <span class="ml-1.5 px-2.5 py-1 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 rounded-lg font-bold text-xs">
                            {{ $latestDate->format('d-M-Y') }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Filter Controls --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Funding Stream Filter --}}
                <form action="{{ route('reports.sectorSummary') }}" method="GET" id="filterForm">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-30 group-hover:opacity-50 blur transition-opacity"></div>
                        <div class="relative flex items-center bg-white border-2 border-gray-200 rounded-xl px-4 py-2.5 shadow-sm hover:border-indigo-300 transition-all">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="text-xs font-bold text-gray-500 uppercase mr-3">Stream:</span>
                            <select 
                                name="type" 
                                onchange="this.form.submit()" 
                                class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-indigo-600 bg-transparent p-0 pr-6 appearance-none"
                            >
                                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Projects</option>
                                <option value="adp" {{ $type == 'adp' ? 'selected' : '' }}>ADP Only</option>
                                <option value="sdg" {{ $type == 'sdg' ? 'selected' : '' }}>SDG Only</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Keep batch_id if present --}}
                    @if(request('batch_id'))
                        <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
                    @endif
                </form>

                {{-- Unit Selector --}}
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl opacity-30 group-hover:opacity-50 blur transition-opacity"></div>
                    <div class="relative flex items-center bg-white border-2 border-gray-200 rounded-xl px-4 py-2.5 shadow-sm hover:border-blue-300 transition-all">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-bold text-gray-500 uppercase mr-3">Units:</span>
                        <select 
                            id="unitSelector" 
                            onchange="toggleCurrency()" 
                            class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-blue-600 bg-transparent p-0 pr-6 appearance-none"
                        >
                            <option value="1">Actual PKR</option>
                            <option value="1000000">Millions (M)</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Export Button --}}
                <button 
                    type="button"
                    class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Table Header --}}
                <thead class="bg-gradient-to-r from-gray-50 via-blue-50 to-gray-50 border-b-2 border-blue-100">
                    <tr>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Sector Name
                            </div>
                        </th>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Projects
                            </div>
                        </th>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider text-right">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Final Budget
                            </div>
                        </th>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider text-right">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Total Releases
                            </div>
                        </th>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider text-right">
                            <div class="flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Expenditure
                            </div>
                        </th>
                        <th class="px-6 py-5 font-bold text-gray-700 uppercase tracking-wider text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Util %
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($sectors as $index => $sector)
                        @php
                            $budget = $sector->sap_dumps_sum_final_budget ?? 0;
                            $rel = $sector->sap_dumps_sum_releases ?? 0;
                            $exp = $sector->sap_dumps_sum_expenditure ?? 0;
                            $util = $rel > 0 ? ($exp / $rel) * 100 : 0;
                            $utilColor = $util > 80 ? 'text-green-600' : ($util > 60 ? 'text-yellow-600' : 'text-red-600');
                            $progressColor = $util > 80 ? 'bg-green-500' : ($util > 60 ? 'bg-yellow-500' : 'bg-red-500');
                            
                            // Different gradient colors for variety
                            $gradients = [
                                'from-blue-500 to-cyan-600',
                                'from-purple-500 to-pink-600',
                                'from-green-500 to-emerald-600',
                                'from-orange-500 to-red-600',
                                'from-indigo-500 to-purple-600',
                                'from-teal-500 to-green-600',
                            ];
                            $gradient = $gradients[$index % count($gradients)];
                        @endphp

                        <tr class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-cyan-50/50 transition-all duration-200 group">
                            <td class="px-6 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-gray-900 text-base">{{ $sector->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl font-bold text-gray-700 shadow-sm">
                                    {{ $sector->sap_dumps_count }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right font-mono font-bold text-gray-900 curr-val" data-raw="{{ $budget }}">
                                {{ number_format($budget) }}
                            </td>
                            <td class="px-6 py-5 text-right font-mono font-bold text-green-700 curr-val" data-raw="{{ $rel }}">
                                {{ number_format($rel) }}
                            </td>
                            <td class="px-6 py-5 text-right font-mono font-bold text-blue-700 curr-val" data-raw="{{ $exp }}">
                                {{ number_format($exp) }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col items-center space-y-2">
                                    <span class="font-black {{ $utilColor }} text-sm">
                                        {{ round($util, 1) }}%
                                    </span>
                                    <div class="w-20 bg-gray-200 h-2 rounded-full overflow-hidden shadow-inner">
                                        <div class="{{ $progressColor }} h-full rounded-full transition-all duration-500" 
                                             style="width: {{ min($util, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Grand Total Footer --}}
                <tfoot class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-t-4 border-blue-600">
                    <tr>
                        <td class="px-6 py-6">
                            <div class="flex items-center text-white">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-black uppercase text-base tracking-wider">Grand Total</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-flex items-center justify-center px-4 py-2 bg-white/10 backdrop-blur text-white font-black rounded-xl border border-white/20">
                                {{ $sectors->sum('sap_dumps_count') }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-right font-mono font-black text-base text-white curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_final_budget') }}">
                            {{ number_format($sectors->sum('sap_dumps_sum_final_budget')) }}
                        </td>
                        <td class="px-6 py-6 text-right font-mono font-black text-green-400 text-base curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_releases') }}">
                            {{ number_format($sectors->sum('sap_dumps_sum_releases')) }}
                        </td>
                        <td class="px-6 py-6 text-right font-mono font-black text-blue-300 text-base curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_expenditure') }}">
                            {{ number_format($sectors->sum('sap_dumps_sum_expenditure')) }}
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $gtRel = $sectors->sum('sap_dumps_sum_releases');
                                $gtExp = $sectors->sum('sap_dumps_sum_expenditure');
                                $gtUtil = $gtRel > 0 ? round(($gtExp / $gtRel) * 100, 1) : 0;
                            @endphp
                            <span class="inline-flex items-center justify-center px-4 py-2 bg-white/10 backdrop-blur text-white font-black text-base rounded-xl border border-white/20">
                                {{ $gtUtil }}%
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

                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'info',
                        title: currentDivisor === 1000000 ? 'Units: Millions' : 'Units: Actual PKR'
                    });
                }
            };

            // Initialize on page load
            window.addEventListener('load', function() {
                applyCurrencyFormatting();
            });
        })();
    </script>
@endpush