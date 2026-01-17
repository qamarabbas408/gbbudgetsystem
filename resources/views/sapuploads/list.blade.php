@extends('layouts.app')

@section('title', 'SAP Dump History')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">SAP Dump History</h2>
                <p class="text-sm text-gray-500 mt-1">Manage and view historical budget snapshots imported from SAP.</p>
            </div>

            <a href="{{ route('sap.upload') }}"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 flex items-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Import New Dump
            </a>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px] relative">
                <input type="text" placeholder="Search by file name or financial year..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select class="px-4 py-2 border border-gray-200 rounded-lg bg-white font-medium text-gray-600 outline-none">
                <option>All Financial Years</option>
                <option>2024-25</option>
                <option>2023-24</option>
            </select>
        </div>

        {{-- List Table --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider">Batch ID</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider">Financial Year</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider">As Of Date</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider">File Details</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-center">Projects</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($uploads as $upload)
                        {{-- Row for existing snapshots --}}
                        <tr class="hover:bg-blue-50/30 transition-colors {{ $upload->is_active ? 'bg-green-50/50' : '' }}">
                            <td class="px-6 py-5">
                                <input type="checkbox" name="snapshot_ids" value="{{ $upload->id }}"
                                    onchange="handleSelection()"
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </td>
                            <td class="px-6 py-5 font-mono font-bold text-blue-600">
                                #{{ str_pad($upload->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-5 font-bold text-gray-900">{{ $upload->report_date->format('d-M-Y') }}</td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-medium text-gray-900">{{ $upload->file_name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">FY:
                                    {{ $upload->financial_year }}</div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-bold">{{ number_format($upload->sap_dumps_count) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                @if ($upload->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                        ACTIVE DEFAULT
                                    </span>
                                @else
                                    <form action="{{ route('sap.activate', $upload->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-gray-400 hover:text-blue-600 font-bold uppercase tracking-widest transition-colors">
                                            Set as Active
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <form action="{{ route('sap.destroy', $upload->id) }}" method="POST"
                                    onsubmit="return confirm('⚠️ Delete this snapshot and all linked project data?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Fallback: No files found --}}
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    {{-- Large subtle icon --}}
                                    <div
                                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6 ring-8 ring-gray-50">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>

                                    <h3 class="text-xl font-bold text-gray-800">No SAP Snapshots Found</h3>
                                    <p class="text-sm text-gray-500 max-w-sm mt-2 leading-relaxed">
                                        The history list is currently empty. Please import a SAP Excel dump to begin your
                                        financial analysis and reporting.
                                    </p>

                                    <a href="{{ route('sap.upload') }}"
                                        class="mt-8 px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
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
@endsection
