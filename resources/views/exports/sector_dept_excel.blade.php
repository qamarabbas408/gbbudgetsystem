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
        <tr></tr> {{-- Blank separator row --}}
        <tr>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff; border: 1px solid #000000;">Sector / Department</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff; border: 1px solid #000000;">Allocation</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff; border: 1px solid #000000;">Releases</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff; border: 1px solid #000000;">Expenditure</th>
            <th style="font-weight: bold; background-color: #1f2937; color: #ffffff; border: 1px solid #000000;">Util %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sectors as $sector)
            {{-- SECTOR HEADER ROW (Notice: No Colspan here, we fill the first cell) --}}
            <tr>
                <td style="font-weight: bold; background-color: #dbeafe; border: 1px solid #000000;">
                    {{ str_replace('&', ' and ', $sector->name) }}
                </td>
                <td style="background-color: #dbeafe; border: 1px solid #000000;"></td>
                <td style="background-color: #dbeafe; border: 1px solid #000000;"></td>
                <td style="background-color: #dbeafe; border: 1px solid #000000;"></td>
                <td style="background-color: #dbeafe; border: 1px solid #000000;"></td>
            </tr>
            
            @foreach($sector->departments as $dept)
                @php
                    $budget = $dept->sap_dumps_sum_final_budget ?? 0;
                    $rel = $dept->sap_dumps_sum_releases ?? 0;
                    $exp = $dept->sap_dumps_sum_expenditure ?? 0;
                @endphp
                <tr>
                    <td style="border: 1px solid #000000;">{{ str_replace('&', ' and ', $dept->name) }}</td>
                    <td style="border: 1px solid #000000;">{{ (float)$budget }}</td>
                    <td style="border: 1px solid #000000;">{{ (float)$rel }}</td>
                    <td style="border: 1px solid #000000;">{{ (float)$exp }}</td>
                    <td style="border: 1px solid #000000;">{{ $rel > 0 ? round(($exp/$rel)*100, 1) : 0 }}%</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>