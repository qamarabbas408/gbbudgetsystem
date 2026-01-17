@extends('layouts.app')

@section('title', 'SAP Dump History')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                {{-- Title Section --}}
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">SAP Dump History</h2>
                        <p class="text-sm text-gray-500 mt-1.5">Manage and view historical budget snapshots imported from SAP</p>
                    </div>
                </div>

                {{-- Action Button --}}
                <a href="{{ route('sap.upload') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-cyan-700 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Import New Dump
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                {{-- Search Input --}}
                <div class="flex-1 min-w-[300px] relative group">
                    <input 
                        type="text" 
                        placeholder="Search by file name or financial year..."
                        class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3.5 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Financial Year Filter --}}
                <div class="relative">
                    <select class="px-5 py-3 border-2 border-gray-200 rounded-xl bg-white font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none pr-10 cursor-pointer transition-all">
                        <option>All Financial Years</option>
                        <option>2024-25</option>
                        <option>2023-24</option>
                        <option>2022-23</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                {{-- Bulk Actions (Hidden when no selection) --}}
                <div id="bulkActions" class="hidden items-center gap-2 px-4 py-2 bg-blue-50 border-2 border-blue-200 rounded-xl">
                    <span class="text-sm font-bold text-blue-700"><span id="selectedCount">0</span> selected</span>
                    <button class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors">
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    {{-- Table Header --}}
                    <thead class="bg-gradient-to-r from-gray-50 via-purple-50 to-gray-50 border-b-2 border-purple-100">
                        <tr>
                            <th class="px-6 py-4 w-12">
                                <input 
                                    type="checkbox" 
                                    id="selectAll"
                                    onchange="toggleSelectAll(this)"
                                    class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
                                >
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    Batch ID
                                </div>
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Report Date
                                </div>
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    File Details
                                </div>
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Projects
                                </div>
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wider text-right">
                                <div class="flex items-center justify-end">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($uploads as $upload)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-pink-50/50 transition-all duration-200 group {{ $upload->is_active ? 'bg-gradient-to-r from-green-50/50 to-emerald-50/50' : '' }}">
                                {{-- Checkbox --}}
                                <td class="px-6 py-5">
                                    <input 
                                        type="checkbox" 
                                        name="snapshot_ids" 
                                        value="{{ $upload->id }}"
                                        onchange="handleSelection()"
                                        class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
                                    >
                                </td>

                                {{-- Batch ID --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">#</span>
                                        </div>
                                        <span class="font-mono font-bold text-purple-700">
                                            {{ str_pad($upload->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Report Date --}}
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900">{{ $upload->report_date->format('d-M-Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $upload->report_date->diffForHumans() }}</span>
                                    </div>
                                </td>

                                {{-- File Details --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900 truncate" title="{{ $upload->file_name }}">
                                                {{ $upload->file_name }}
                                            </div>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded">
                                                    FY: {{ $upload->financial_year }}
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    Uploaded {{ $upload->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Projects Count --}}
                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl">
                                        <svg class="w-4 h-4 text-gray-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <span class="font-bold text-gray-800">{{ number_format($upload->sap_dumps_count) }}</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-5">
                                    @if ($upload->is_active)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-2 border-green-300 shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            ACTIVE DEFAULT
                                        </span>
                                    @else
                                        <form action="{{ route('sap.activate', $upload->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-purple-100 border-2 border-gray-200 hover:border-purple-300 text-gray-600 hover:text-purple-700 text-xs font-bold rounded-xl transition-all duration-200"
                                            >
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Set as Active
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end space-x-2">
                                        {{-- View Button --}}
                                        <button 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors group/btn"
                                            title="View Details"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        {{-- Delete Button --}}
                                        <form 
                                            action="{{ route('sap.destroy', $upload->id) }}" 
                                            method="POST"
                                            onsubmit="return confirm('⚠️ Delete this snapshot and all linked project data? This action cannot be undone.')"
                                        >
                                            @csrf 
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors group/btn"
                                                title="Delete Snapshot"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="7" class="px-6 py-24">
                                    <div class="flex flex-col items-center justify-center">
                                        {{-- Animated Icon --}}
                                        <div class="relative mb-8">
                                            <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full opacity-20 blur-xl animate-pulse"></div>
                                            <div class="relative w-32 h-32 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center ring-8 ring-purple-50">
                                                <svg class="w-16 h-16 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No SAP Snapshots Found</h3>
                                        <p class="text-gray-500 max-w-md text-center mb-8 leading-relaxed">
                                            The history list is currently empty. Import your first SAP Excel dump to begin tracking and analyzing your financial data.
                                        </p>

                                        <a 
                                            href="{{ route('sap.upload') }}"
                                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-pink-700 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            Import Your First Dump
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination (if needed)
        @if($uploads->hasPages())
            <div class="mt-6">
                {{ $uploads->links() }}
            </div>
        @endif --}}
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            /**
             * Handle individual checkbox selection
             */
            window.handleSelection = function() {
                const checkboxes = document.querySelectorAll('input[name="snapshot_ids"]:checked');
                const count = checkboxes.length;
                const bulkActions = document.getElementById('bulkActions');
                const selectedCount = document.getElementById('selectedCount');

                if (count > 0) {
                    bulkActions.classList.remove('hidden');
                    bulkActions.classList.add('flex');
                    selectedCount.textContent = count;
                } else {
                    bulkActions.classList.add('hidden');
                    bulkActions.classList.remove('flex');
                }

                // Update select all checkbox state
                const allCheckboxes = document.querySelectorAll('input[name="snapshot_ids"]');
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = count === allCheckboxes.length;
                    selectAll.indeterminate = count > 0 && count < allCheckboxes.length;
                }
            };

            /**
             * Toggle select all checkboxes
             */
            window.toggleSelectAll = function(checkbox) {
                const checkboxes = document.querySelectorAll('input[name="snapshot_ids"]');
                checkboxes.forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                handleSelection();
            };
        })();
    </script>
@endpush