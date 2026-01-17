@extends('layouts.app')

@section('title', 'Sector-wise Budget Summary')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Sector-wise Analysis</h2>
        <p class="text-sm text-gray-500 mt-1">Snapshot: <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold">{{ \Carbon\Carbon::parse($latestDate)->format('d-M-Y') }}</span></p>
    </div>

    {{-- Unit Selector --}}
    <div class="flex items-center bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
        <span class="text-xs font-black text-gray-400 uppercase mr-3">Units:</span>
        <select id="unitSelector" onchange="toggleCurrency()" class="text-sm border-none focus:ring-0 cursor-pointer font-bold text-blue-600 bg-transparent p-0">
            <option value="1">Actual PKR</option>
            <option value="1000000">Millions (M)</option>
        </select>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 border-b-2 border-gray-100">
            <tr>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider">Sector Name</th>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider text-center">Projects</th>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider text-right">Final Budget</th>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider text-right">Total Releases</th>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider text-right">Expenditure</th>
                <th class="px-6 py-5 font-bold text-gray-600 uppercase tracking-wider text-center">Util %</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($sectors as $sector)
            @php
                $budget = $sector->sap_dumps_sum_final_budget ?? 0;
                $rel = $sector->sap_dumps_sum_releases ?? 0;
                $exp = $sector->sap_dumps_sum_expenditure ?? 0;
                $util = $rel > 0 ? ($exp / $rel) * 100 : 0;
            @endphp
            <tr class="hover:bg-blue-50/50 transition-colors">
                <td class="px-6 py-5 font-bold text-gray-900 text-lg">{{ $sector->name }}</td>
                <td class="px-6 py-5 text-center font-semibold text-gray-500">{{ $sector->sap_dumps_count }}</td>
                <td class="px-6 py-5 text-right font-mono font-bold curr-val" data-raw="{{ $budget }}">{{ number_format($budget) }}</td>
                <td class="px-6 py-5 text-right font-mono font-bold text-green-600 curr-val" data-raw="{{ $rel }}">{{ number_format($rel) }}</td>
                <td class="px-6 py-5 text-right font-mono font-bold text-blue-700 curr-val" data-raw="{{ $exp }}">{{ number_format($exp) }}</td>
                <td class="px-6 py-5">
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black {{ $util > 70 ? 'text-green-600' : 'text-gray-600' }}">{{ round($util, 1) }}%</span>
                        <div class="w-16 bg-gray-100 h-1.5 rounded-full mt-1 overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: {{ min($util, 100) }}%"></div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-900 text-white">
            <tr>
                <td class="px-6 py-6 font-black uppercase">Grand Total</td>
                <td class="px-6 py-6 text-center font-black">{{ $sectors->sum('sap_dumps_count') }}</td>
                <td class="px-6 py-6 text-right font-mono font-black curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_final_budget') }}">0</td>
                <td class="px-6 py-6 text-right font-mono font-black text-green-400 curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_releases') }}">0</td>
                <td class="px-6 py-6 text-right font-mono font-black text-blue-300 curr-val" data-raw="{{ $sectors->sum('sap_dumps_sum_expenditure') }}">0</td>
                <td></td>
            </tr>
        </tfoot>
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
    // Run once on load to initialize totals
    window.onload = toggleCurrency;
</script>
@endpush