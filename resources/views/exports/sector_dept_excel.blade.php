<table>
    <thead>
        <tr>
            <th style="font-weight: bold; font-size: 14px;">GB PND - Sector &amp; Dept Analysis</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Report Date: {{ $date->format('d-M-Y') }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff;">Sector / Department</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff;">Allocation
                ({{ $unit == 1000000 ? 'M' : 'PKR' }})</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff;">Releases</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff;">Expenditure</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff;">Util %</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sectors as $sector)
            <tr style="background-color: #dbeafe;">
                <td colspan="5" style="font-weight: bold;">{{ $sector->name }}</td>
            </tr>
            @foreach ($sector->departments as $dept)
                @php
                    $budget = ($dept->sap_dumps_sum_final_budget ?? 0) / $unit;
                    $rel = ($dept->sap_dumps_sum_releases ?? 0) / $unit;
                    $exp = ($dept->sap_dumps_sum_expenditure ?? 0) / $unit;
                    $util = $rel > 0 ? ($exp / $rel) * 100 : 0;
                @endphp
                <tr>
                    {{-- <td>{{ str_replace('&', '&amp;', $dept->name) }}</td> --}}
                    <td>{{ $dept->name }}</td>
                    <td>{{ round($budget, 2) }}</td>
                    <td>{{ round($rel, 2) }}</td>
                    <td>{{ round($exp, 2) }}</td>
                    {{-- Outputting as a number without the % symbol for easier Excel sorting/math --}}
                    <td>{{ round($util, 1) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
