@extends('layouts.app')

@section('title', 'File Upload - GB PND Budget System')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Upload Area -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Upload SAP Dump (Excel)</h3>

            <!-- Added Action, Method, CSRF, and ID -->
            <form id="uploadForm" action="{{ route('sap.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Drag and Drop Area -->
                <div id="dropZone"
                    class="border-2 border-dashed {{ $errors->has('sap_file') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-12 text-center hover:border-blue-500 transition-colors cursor-pointer bg-gray-50">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700 mb-2">Drag and drop your SAP Excel file here</p>
                    <p class="text-sm text-gray-500 mb-4">or</p>

                    <label for="fileInput"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Browse Files
                    </label>

                    <!-- IMPORTANT: Name must be 'sap_file' to match Controller -->
                    <input type="file" id="fileInput" name="sap_file" class="hidden" accept=".xlsx,.xls">

                    <p class="text-xs text-gray-400 mt-4">Supported formats: XLS, XLSX (Max 10MB)</p>
                </div>

                <!-- File Preview -->
                <div id="filePreview" class="mt-6 space-y-3 hidden"></div>

                <!-- File Details -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Financial Year</label>
                        <select name="financial_year"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="2023-24">2023-24</option>
                            <option value="2024-25" selected>2024-25</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Report "As Of" Date</label>
                        <input type="date" id="asOfDate" name="as_of_date" value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Select the date this data was exported from SAP.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Upload & Process SAP
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Uploads Section (Optional: You can loop through data here later) -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Uploads</h3>
            </div>
            <div class="p-8 text-center text-gray-500">
                No recent uploads found.
            </div>
        </div>
    </div>

    <!-- Data Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <di v class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-6xl  overflow-hidden">

                <!-- Modal Header -->
                <div class="bg-gray-50 px-8 py-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">SAP Data Verification</h3>
                        <p class="text-sm text-gray-500">Please review the calculated totals before final processing</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-8">
                    <!-- Analytics Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <p class="text-xs text-blue-600 font-semibold uppercase">Total Projects</p>
                            <p id="statRows" class="text-2xl font-bold text-blue-900">0</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <p class="text-xs text-green-600 font-semibold uppercase">Total Budget</p>
                            <p id="statBudget" class="text-2xl font-bold text-green-900">0</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                            <p class="text-xs text-yellow-600 font-semibold uppercase">Total Releases</p>
                            <p id="statReleases" class="text-2xl font-bold text-yellow-900">0</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                            <p class="text-xs text-purple-600 font-semibold uppercase">Total Expenditure</p>
                            <p id="statExp" class="text-2xl font-bold text-purple-900">0</p>
                        </div>
                    </div>

                    <!-- Preview Table -->
                    <!-- Inner Modal Loading Overlay -->
                    <div id="modalLoader"
                        class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-80 hidden">
                        <div class="text-center">
                            <!-- Modern Spinner -->
                            <div
                                class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent mb-4">
                            </div>
                            <p class="text-lg font-semibold text-gray-700">Processing & Rendering Data...</p>
                            <p class="text-sm text-gray-500">Please wait while we format the rows.</p>
                        </div>
                    </div>
                    <div class="border rounded-lg overflow-hidden">
                        <div class="max-h-80 overflow-y-auto">
                            <!-- Modal Header (Update with Control Bar) -->
                            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">SAP Data Verification</h3>
                                        <p class="text-sm text-gray-500">Reviewing <span id="totalRowsCount">0</span>
                                            records</p>
                                    </div>

                                    <!-- NEW: CONTROL BAR -->
                                    <div
                                        class="flex flex-wrap items-center gap-4 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                                        <!-- Row Range -->
                                        <div class="flex items-center space-x-2 border-r pr-4">
                                            <span class="text-xs font-semibold text-gray-500 uppercase">Show:</span>
                                            <input type="number" id="rangeStart" value="1" min="1"
                                                class="w-16 px-2 py-1 border rounded text-sm focus:ring-blue-500">
                                            <span class="text-gray-400">to</span>
                                            <input type="number" id="rangeEnd" value="100"
                                                class="w-16 px-2 py-1 border rounded text-sm focus:ring-blue-500">
                                            <button onclick="applyFilters()"
                                                class="bg-gray-100 hover:bg-gray-200 p-1 rounded">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Unit Selector -->
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-semibold text-gray-500 uppercase">Unit:</span>
                                            <select id="unitSelector" onchange="applyFilters()"
                                                class="text-sm border-none focus:ring-0 cursor-pointer font-medium text-blue-600">
                                                <option value="actual">Actual PKR</option>
                                                <option value="millions">In Millions (M)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-700">ADP NO</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Description</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700 text-right">Budget</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700 text-right">Releases</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">WBS Element</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="divide-y divide-gray-100">
                                    <!-- Data rows inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-end space-x-4">
                    <button onclick="closeModal()" class="px-6 py-2 text-gray-700 hover:text-gray-900 font-medium">Cancel
                        & Re-upload</button>
                    <button type="button" onclick="submitFinalData()"
                        class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-lg">Confirm &
                        Save to Database</button>
                </div>
            </div>
    </div>
    </div>
@endsection
<!-- Data Preview Dump -->
{{-- <div id="dataPreview" class="mt-8 hidden">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-800 p-4 flex justify-between items-center">
            <h3 class="text-white font-mono text-sm">Filtered SAP Data Dump (Raw JSON)</h3>
            <span id="rowCount" class="text-xs bg-blue-600 text-white px-2 py-1 rounded"></span>
        </div>
        <pre id="jsonDump" class="p-6 text-xs font-mono text-green-400 bg-gray-900 overflow-x-auto max-h-96"></pre>
    </div>
</div> --}}


@push('scripts')
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const uploadForm = document.getElementById('uploadForm');

        // Drag and Drop Logic
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(name => {
            dropZone.addEventListener(name, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(name => {
            dropZone.addEventListener(name, () => dropZone.classList.add('border-blue-500', 'bg-blue-50'));
        });

        ['dragleave', 'drop'].forEach(name => {
            dropZone.addEventListener(name, () => dropZone.classList.remove('border-blue-500', 'bg-blue-50'));
        });

        dropZone.addEventListener('drop', e => {
            fileInput.files = e.dataTransfer.files;
            handleFiles(fileInput.files);
        });

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        let globalSapData = [];

        function handleFiles(files) {
            const file = files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const worksheet = workbook.Sheets[workbook.SheetNames[0]];

                // 1. Convert to 2D Array (Rows and Columns)
                const rows = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1
                });

                // 2. Find the index of the header row (the one containing "ADP NO")
                let headerRowIndex = -1;
                for (let i = 0; i < rows.length; i++) {
                    if (rows[i].includes("ADP NO")) {
                        headerRowIndex = i;
                        break;
                    }
                }

                if (headerRowIndex === -1) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not find "ADP NO" column in file!'
                    });
                    return;
                }

                // 3. Identify column positions based on the header row found
                const headerRow = rows[headerRowIndex];
                const colMap = {
                    adp: headerRow.indexOf("ADP NO"),
                    desc: headerRow.findIndex(cell => cell && cell.toString().includes("Project Description")),
                    budget: headerRow.findIndex(cell => cell && cell.toString().includes("Final Budget")),
                    releases: headerRow.findIndex(cell => cell && cell.toString().includes("Prog Releases")),
                    exp: headerRow.findIndex(cell => cell && cell.toString().includes("Progressive Exp")),
                    wbs: headerRow.findIndex(cell => cell && cell.toString().includes("WBS Element"))
                };

                // 4. Process all rows AFTER the header row
                const filteredData = [];
                for (let i = headerRowIndex + 1; i < rows.length; i++) {
                    const row = rows[i];

                    // Extract values based on our map
                    const adpNo = row[colMap.adp] ? row[colMap.adp].toString().trim() : null;
                    const wbs = row[colMap.wbs] ? row[colMap.wbs].toString().trim() : null;

                    // APPLY YOUR LOGIC:
                    // - Ignore if ADP NO is empty or '*'
                    // - Ignore if WBS is empty
                    if (adpNo && wbs) {
                        filteredData.push({
                            "ADP_NO": adpNo,
                            "Description": row[colMap.desc] ? row[colMap.desc].toString().trim() : "",
                            "Final_Budget": parseFloat(row[colMap.budget]) || 0,
                            "Releases": parseFloat(row[colMap.releases]) || 0,
                            "Expenditure": parseFloat(row[colMap.exp]) || 0,
                            "WBS": wbs
                        });
                    }
                }

                globalSapData = filteredData;
                document.getElementById('rangeEnd').value = Math.min(100, globalSapData.length);
                document.getElementById('totalRowsCount').innerText = globalSapData.length;


                // displayDump(filteredData);
                renderModalContent();
            };

            reader.readAsArrayBuffer(file);
        }

        function renderModalContent() {
            // 1. Get current filter states
            const start = parseInt(document.getElementById('rangeStart').value) - 1;
            const end = parseInt(document.getElementById('rangeEnd').value);
            const unit = document.getElementById('unitSelector').value;

            // 2. Calculate Global Analytics (Always on full dataset)
            const totalBudget = globalSapData.reduce((sum, r) => sum + r.Final_Budget, 0);
            const totalRel = globalSapData.reduce((sum, r) => sum + r.Releases, 0);
            const totalExp = globalSapData.reduce((sum, r) => sum + r.Expenditure, 0);

            // 3. Formatting Helper
            const formatValue = (val) => {
                if (unit === 'millions') {
                    return (val / 1000000).toFixed(2) + ' M';
                }
                return new Intl.NumberFormat('en-PK').format(val);
            };

            // 4. Update Header Stats
            document.getElementById('statRows').innerText = globalSapData.length.toLocaleString();
            document.getElementById('statBudget').innerText = 'Rs ' + formatValue(totalBudget);
            document.getElementById('statReleases').innerText = 'Rs ' + formatValue(totalRel);
            document.getElementById('statExp').innerText = 'Rs ' + formatValue(totalExp);

            // 5. Render Table Slice (Pagination/Range)
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';

            const slicedData = globalSapData.slice(start, end);

            // For very large ranges (e.g., 4000 rows), string concatenation is 
            // faster than updating tbody.innerHTML inside the loop.
            let htmlBuffer = '';

            slicedData.forEach(row => {
                htmlBuffer += `
            <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors">
                <td class="px-4 py-3 font-mono text-blue-700 font-bold">${row.ADP_NO}</td>
                <td class="px-4 py-3 text-gray-600 truncate max-w-xs">${row.Description}</td>
                <td class="px-4 py-3 text-right font-medium">${formatValue(row.Final_Budget, unit)}</td>
                <td class="px-4 py-3 text-right font-medium text-green-600">${formatValue(row.Releases, unit)}</td>
                <td class="px-4 py-3 font-mono text-xs text-gray-400">${row.WBS}</td>
            </tr>
        `;
            });
            tbody.innerHTML = htmlBuffer;

            // 6. Warning if user selects "All" (e.g., more than 1000 rows)
            if (end - start > 1000) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Showing many rows may slow down the browser.'
                });
            }

            document.getElementById('previewModal').classList.remove('hidden');
        }


        function closeModal() {
            document.getElementById('previewModal').classList.add('hidden');
            // Optionally reset file input
            document.getElementById('uploadForm').reset();
            document.getElementById('filePreview').classList.add('hidden');
        }

        function applyFilters() {
            const loader = document.getElementById('modalLoader');

            // 1. Show the loader immediately
            loader.classList.remove('hidden');

            // 2. Use setTimeout (0ms or 50ms) to allow the browser to PAINT the loader
            // before the heavy table rendering blocks the main thread.
            setTimeout(() => {
                try {
                    renderModalContent();
                } finally {
                    // 3. Hide the loader once rendering is finished
                    loader.classList.add('hidden');
                }
            }, 50);
        }

        async function submitFinalData() {
            // 1. Get the Metadata from the form
            const asOfDate = document.getElementById('asOfDate').value;
            const finYear = document.querySelector('select[name="financial_year"]').value;

            if (!asOfDate) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please select a Report "As Of" Date'
                });
                return;
            }

            // 2. Show a loading state on the button
            const confirmBtn = document.querySelector('#previewModal button.bg-blue-600');
            const originalText = confirmBtn.innerText;
            confirmBtn.disabled = true;
            confirmBtn.innerText = 'Saving to Database...';

            try {
                // 3. Send data via Fetch API
                const response = await fetch("{{ route('sap.storeBatch') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        data: globalSapData, // The 4,000+ rows we parsed earlier
                        as_of_date: asOfDate,
                        financial_year: finYear
                    })
                });

                const result = await response.json();
                
                if (response.ok) {  
                    // 4. Success! Redirect to Dashboard
                    Toast.fire({
                        icon: 'success',
                        title: result.message
                    });
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Server Error');
                }

            } catch (error) {
                console.error(error);
                // 5. Handle Errors
                Toast.fire({
                    icon: 'error',
                    title: 'Upload Failed: ' + error.message
                });
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalText;
            }
        }
    </script>
@endpush
