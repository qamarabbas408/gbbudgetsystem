@extends('layouts.app')

@section('title', 'Sector & Department Analysis')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Sector & Department Analysis</h2>
        <p class="text-sm text-gray-500 mt-1">Snapshot Date: <span class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($latestDate)->format('d-M-Y') }}</span></p>
    </div>

   <div class="flex items-center gap-4">
    {{-- NEW: Funding Stream Filter --}}
    <form action="{{ route('reports.sectorDeptAnalysis') }}" method="GET" id="filterForm">
        <div class="flex items-center bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
            <span class="text-xs font-black text-gray-400 uppercase mr-3">Stream:</span>
            <select name="type" onchange="this.form.submit()" class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-indigo-600 bg-transparent p-0">
                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Projects</option>
                <option value="adp" {{ $type == 'adp' ? 'selected' : '' }}>ADP Only</option>
                <option value="sdg" {{ $type == 'sdg' ? 'selected' : '' }}>SDG Only</option>
            </select>
        </div>
    </form>

    {{-- Existing Unit Selector (Millions/PKR) --}}
    <div class="flex items-center bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
        <span class="text-xs font-black text-gray-400 uppercase mr-3">Units:</span>
        <select id="unitSelector" onchange="toggleCurrency()" class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-blue-600 bg-transparent p-0">
            <option value="1">Actual PKR</option>
            <option value="1000000">Millions (M)</option>
        </select>
    </div>
</div>
</div>

<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-6 py-4 font-bold uppercase tracking-wider">Sector / Department</th>
                <th class="px-6 py-4 font-bold uppercase tracking-wider text-center">Projects</th>
                <th class="px-6 py-4 font-bold uppercase tracking-wider text-right">Reallocation</th>
                <th class="px-6 py-4 font-bold uppercase tracking-wider text-right">Releases</th>
                <th class="px-6 py-4 font-bold uppercase tracking-wider text-right">Expenditure</th>
                <th class="px-6 py-4 font-bold uppercase tracking-wider text-center">Util %</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($sectors as $sector)
                {{-- SECTOR HEADER ROW --}}
                <tr class="bg-blue-50/50">
                    <td colspan="6" class="px-6 py-4 font-black text-blue-900 uppercase tracking-widest text-base">
                        {{ $sector->name }}
                    </td>
                </tr>

                {{-- DEPARTMENT ROWS --}}
                @foreach($sector->departments as $dept)
                @php
                    $budget = $dept->sap_dumps_sum_final_budget ?? 0;
                    $rel = $dept->sap_dumps_sum_releases ?? 0;
                    $exp = $dept->sap_dumps_sum_expenditure ?? 0;
                    $util = $rel > 0 ? ($exp / $rel) * 100 : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-12 py-4 font-semibold text-gray-700 italic border-l-4 border-blue-200">
                        {{ $dept->name }} <span class="text-gray-400 font-normal text-xs ml-2">({{ $dept->abbreviation }})</span>
                    </td>
                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $dept->sap_dumps_count }}</td>
                    <td class="px-6 py-4 text-right font-mono font-bold curr-val" data-raw="{{ $budget }}">{{ number_format($budget) }}</td>
                    <td class="px-6 py-4 text-right font-mono font-bold text-green-600 curr-val" data-raw="{{ $rel }}">{{ number_format($rel) }}</td>
                    <td class="px-6 py-4 text-right font-mono font-bold text-blue-700 curr-val" data-raw="{{ $exp }}">{{ number_format($exp) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-[10px] font-black {{ $util > 70 ? 'text-green-600' : 'text-gray-500' }}">{{ round($util, 1) }}%</span>
                            <div class="w-12 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full" style="width: {{ min($util, 100) }}%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function toggleCurrency() {
        const divisor = parseFloat(document.getElementById('unitSelector').value);
        const isMillions = (divisor === 1000000);

        document.querySelectorAll('.curr-val').forEach(el => {
            const raw = parseFloat(el.getAttribute('data-raw'));
            if (isMillions) {
                el.innerText = (raw / 1000000).toFixed(2) + ' M';
            } else {
                el.innerText = new Intl.NumberFormat('en-PK').format(raw);
            }
        });
    }
    window.onload = toggleCurrency;
</script>
@endpush