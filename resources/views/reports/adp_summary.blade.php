@extends('layouts.app')

@section('title', 'ADP Summary Report')
@section('hide_floating_btn', true)
@section('content')

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">ADP Summary Report</h2>
            <p class="text-sm text-gray-500">Data as of: <span
                    class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($latestDate)->format('d-M-Y') }}</span></p>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <div class="flex items-center bg-white border border-gray-300 rounded-lg px-3 py-1.5 shadow-sm">
                <span class="text-xs font-bold text-gray-400 uppercase mr-2">Units:</span>
                <select id="unitSelector" onchange="toggleCurrencyUnits()"
                    class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-blue-600 p-0 bg-transparent">
                    <option value="1">Actual (PKR)</option>
                    <option value="1000000">Millions (M)</option>
                </select>
            </div>
            <button
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export PDF
            </button>
            <button
                class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Export Excel
            </button>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- LOCAL SPINNER OVERLAY -->
    <div class="overflow-x-auto p-4">
        <table id="adpReportTable" class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-700 cursor-pointer hover:bg-gray-100">
                            ADP # <span class="text-gray-300">↕</span>
                        </th>
                        <th class="px-6 py-4 font-bold text-gray-700">FY</th>
                        <th class="px-6 py-4 font-bold text-gray-700 cursor-pointer hover:bg-gray-100">
                            Project Name <span class="text-gray-300">↕</span>
                        </th>
                        <!-- Added 'data-type="num"' to help with sorting large numbers -->
                        <th class="px-6 py-4 font-bold text-gray-700 text-right cursor-pointer hover:bg-gray-100">
                            Allocation <span class="text-gray-300">↕</span>
                        </th>
                        <th class="px-6 py-4 font-bold text-gray-700 text-right cursor-pointer hover:bg-gray-100">
                            Releases <span class="text-gray-300">↕</span>
                        </th>
                        <th class="px-6 py-4 font-bold text-gray-700 text-right cursor-pointer hover:bg-gray-100">
                            Expenditure <span class="text-gray-300">↕</span>
                        </th>
                        <th class="px-6 py-4 font-bold text-gray-700 text-center cursor-pointer hover:bg-gray-100">
                            Utilization <span class="text-gray-300">↕</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($projects as $project)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-blue-800">{{ $project->adp_no }}</td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $project->financial_year }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 max-w-md truncate"
                                    title="{{ $project->project_description }}">
                                    {{ $project->project_description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-gray-900 curr-cell"
                                data-raw="{{ $project->total_allocation }}">
                                {{ number_format($project->total_allocation) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-green-700 curr-cell"
                                data-raw="{{ $project->total_releases }}">
                                {{ number_format($project->total_releases) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-blue-700 curr-cell"
                                data-raw="{{ $project->total_expenditure }}">
                                {{ number_format($project->total_expenditure) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $percent =
                                        $project->total_releases > 0
                                            ? ($project->total_expenditure / $project->total_releases) * 100
                                            : 0;
                                    $color =
                                        $percent > 80
                                            ? 'bg-green-500'
                                            : ($percent > 40
                                                ? 'bg-yellow-500'
                                                : 'bg-red-500');
                                @endphp
                                <div class="flex items-center justify-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-1.5 mr-2">
                                        <div class="{{ $color }} h-1.5 rounded-full"
                                            style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">{{ round($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <!-- Footer with Totals -->
                <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right">TOTALS:</td>
                        <td class="px-6 py-4 text-right curr-cell" data-raw="{{ $projects->sum('total_allocation') }}">
                            {{ number_format($projects->sum('total_allocation')) }}
                        </td>
                        <td class="px-6 py-4 text-right text-green-700 curr-cell"
                            data-raw="{{ $projects->sum('total_releases') }}">
                            {{ number_format($projects->sum('total_releases')) }}</td>
                        <td class="px-6 py-4 text-right text-blue-700 curr-cell"
                            data-raw="{{ $projects->sum('total_expenditure') }}">
                            {{ number_format($projects->sum('total_expenditure')) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        // 1. Global state to remember the selected unit
        let currentDivisor = 1;

        $(document).ready(function() {

            const table = $('#adpReportTable').DataTable({
                "pageLength": 50,
                "order": [
                    [0, "asc"]
                ],
                "dom": '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                    "search": "", // This removes the "Search:" text label
                    "searchPlaceholder": "Search ADP#, Project Name or WBS...", // The placeholder text
                    "lengthMenu": "Show _MENU_ records",
                    "info": "Showing _START_ to _END_ of _TOTAL_ projects",
                },
                // 2. This runs EVERY time the table changes (Sort, Filter, Page)
                "drawCallback": function(settings) {
                    // Apply Tailwind styles to pagination
                    $('.dataTables_paginate .paginate_button').addClass(
                        'px-3 py-1 border border-gray-200 rounded-md ml-1 hover:bg-blue-50 text-sm');
                    $('.dataTables_paginate .paginate_button.current').addClass(
                        'bg-blue-600 text-white border-blue-600');

                    // CRITICAL: Re-apply the currency formatting to the new rows
                    applyCurrencyFormatting();
                },

                "initComplete": function(settings, json) {

                    // Since we removed the "Search:" label, we should adjust the margin
                    $('.dataTables_filter input').addClass(
                        'px-4 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 w-80 outline-none ml-0 shadow-sm'
                    );
                    $('.dataTables_length select').addClass(
                        'mx-2 px-3 py-1.5 border border-gray-300 rounded-lg bg-white outline-none');
                   
                }
            });

            // 3. The Formatting Engine
            function applyCurrencyFormatting() {
                const isMillions = (currentDivisor === 1000000);

                const format = (val) => {
                    if (isMillions) {
                        return (val / 1000000).toFixed(2) + ' M';
                    }
                    return new Intl.NumberFormat('en-PK').format(val);
                };

                // Format Body Cells
                $('.curr-cell').each(function() {
                    const rawValue = parseFloat($(this).attr('data-raw'));
                    if (!isNaN(rawValue)) {
                        $(this).text(format(rawValue));
                    }
                });

                // Format Footer (Total) Cells
                $('.footer-curr-cell').each(function() {
                    const rawValue = parseFloat($(this).attr('data-raw'));
                    if (!isNaN(rawValue)) {
                        $(this).text(format(rawValue));
                    }
                });
            }

            // 4. The Toggle Function (Triggered by Dropdown)
            window.toggleCurrencyUnits = function() {
                currentDivisor = parseFloat(document.getElementById('unitSelector').value);

                // This triggers the 'drawCallback' automatically
                table.draw(false);

                Toast.fire({
                    icon: 'info',
                    title: currentDivisor === 1000000 ? 'Units: Millions' : 'Units: Actual PKR'
                });
            };
        });
    </script>
@endpush
