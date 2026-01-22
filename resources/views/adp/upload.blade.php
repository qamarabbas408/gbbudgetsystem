@extends('layouts.app')

@section('title', 'ADP Formulation Upload - GB PND')
@section('hide_floating_btn', true)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">ADP Formulation Upload</h2>
                    <p class="text-gray-500">Import the annual planning document (Approved & Proposed Schemes)</p>
                </div>
            </div>
        </div>

        {{-- Upload Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form id="adpUploadForm" action="{{ route('adp.storeFormulation') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="p-8 lg:p-10">
                    {{-- Drag and Drop Zone --}}
                    <div id="fileDropZone"
                        class="relative border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-indigo-50/30 rounded-2xl p-12 lg:p-16 text-center hover:border-indigo-500 hover:bg-indigo-50/50 transition-all duration-300 cursor-pointer group">

                        <div class="relative z-10">
                            <div
                                class="mx-auto w-20 h-20 mb-6 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2">Drop ADP Formulation Excel here</h3>
                            <p class="text-gray-500 mb-6 uppercase text-[10px] font-black tracking-widest leading-none">
                                Supports .XLSX Formulation Documents</p>

                            <label for="excelFileInput"
                                class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 cursor-pointer transition-all duration-300 shadow-lg transform hover:-translate-y-0.5">
                                Browse ADP File
                            </label>

                            <input type="file" id="excelFileInput" name="adp_file" class="hidden" accept=".xlsx,.xls">
                        </div>

                        {{-- Loading Overlay --}}
                        <div id="processingOverlay"
                            class="hidden absolute inset-0 bg-white/95 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center z-20">
                            <div class="relative">
                                <div
                                    class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-indigo-900" id="processingStatusText">Processing Excel
                                file...</p>
                            <p class="mt-2 text-xs text-gray-600" id="processingProgressText">Reading data...</p>
                        </div>
                    </div>

                    {{-- File Preview --}}
                    <div id="filePreviewContainer" class="hidden mt-6">
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    XLS
                                </div>
                                <div>
                                    <p class="font-bold text-indigo-900 text-sm" id="selectedFileName">file.xlsx</p>
                                    <p class="text-xs text-indigo-600" id="selectedFileSize">0 KB</p>
                                </div>
                            </div>
                            <button type="button" onclick="ADPUploader.clearSelectedFile()"
                                class="text-indigo-400 hover:text-indigo-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Configuration --}}
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Planning Year</label>
                        <select name="financial_year" id="financialYearSelect"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="2025-26" selected>2025-26</option>
                            <option value="2024-25">2024-25</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="text-xs text-gray-400 italic">
                            * This will update the Master ADP list. Approved schemes will be matched with SAP dumps
                            automatically.
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ADP Verification Modal --}}
    <div id="verificationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-7xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Modal Header --}}
                <div
                    class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6 flex justify-between items-center text-white flex-shrink-0">
                    <div>
                        <h3 class="text-2xl font-bold">ADP Verification</h3>
                        <p class="text-indigo-100 text-sm">
                            Reviewing <span id="totalSchemesCount" class="font-bold">0</span> schemes
                            (<span id="targetedSchemesCount" class="text-green-300">0</span> targeted,
                            <span id="nonTargetedSchemesCount" class="text-red-300">0</span> non-targeted)
                        </p>
                    </div>
                    <button onclick="ADPUploader.closeVerificationModal()"
                        class="hover:bg-white/10 p-2 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-8 overflow-y-auto flex-1">

                    {{-- Controls --}}
                    <div
                        class="flex items-center justify-between gap-4 mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black text-gray-400 uppercase">Display Unit:</span>
                            <select id="currencyUnitSelector" onchange="ADPUploader.renderPreviewTable()"
                                class="text-sm font-bold text-indigo-600 bg-white px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <option value="actual">Actual PKR</option>
                                <option value="millions">Millions (M)</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-gray-400 uppercase">Show:</span>
                            <select id="rowDisplayLimitSelector" onchange="ADPUploader.renderPreviewTable()"
                                class="text-sm font-bold text-indigo-600 bg-white px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <option value="50">50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="250">250 rows</option>
                                <option value="500">500 rows</option>
                                <option value="all">All rows</option>
                            </select>
                            <span class="text-[10px] text-gray-400 italic">
                                Showing <span id="displayedRowsCount">0</span> of <span id="totalRowsCount">0</span>
                            </span>
                        </div>
                    </div>

                    {{-- Table Container --}}
                    <div class="border-2 border-gray-200 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left min-w-[800px]">
                                <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700">#</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700">ADP No.</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 min-w-[300px]">
                                            Scheme Name</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700">Appr. Date</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700">District / Halqa
                                        </th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-center">
                                            Dist. Code</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-center">
                                            Targeted</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">Exp.
                                            Upto June/2025</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">Est.
                                            Cost</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">
                                            Throw-forward 2025-26</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">
                                            Allocation 2025-26</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="divide-y divide-gray-100 bg-white">
                                    {{-- Rows rendered via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="bg-gray-50 px-8 py-6 border-t-2 border-gray-200 flex justify-between items-center flex-shrink-0">
                    <button onclick="ADPUploader.closeVerificationModal()"
                        class="px-6 py-2.5 text-gray-600 font-bold hover:text-gray-800 hover:bg-gray-200 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button onclick="ADPUploader.submitADPData()" id="confirmSyncButton"
                        class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-black rounded-2xl shadow-xl hover:from-indigo-700 hover:to-indigo-800 transition-all flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        CONFIRM & SYNC ADP
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script>
        /**
         * ADP Formulation Upload Module
         * Handles Excel file upload, parsing, validation and submission
         */
        const ADPUploader = (function() {
            'use strict';

            // ==================== CONSTANTS ====================
            const CONFIG = {
                MAX_FILE_SIZE: 50 * 1024 * 1024, // 50MB
                TARGET_SHEET_NAME: "ADP 2025-26",
                HEADER_ROW_INDEX: 2, // Row 3 in Excel (0-indexed)
                CHUNK_SIZE: 100,
                UI_UPDATE_DELAY: 10,

                // Head codes to ignore (placeholder rows)
                IGNORED_HEAD_CODES: [
                    "A01270", "A02102", "A03970", "A09101", "A09501",
                    "A09601", "A09701", "A12102", "A12104", "A12403", "A12404"
                ],

                // Column mapping keywords
                COLUMN_KEYWORDS: {
                    adpNumber: ["ADP#", "ADP NO"],
                    serialNumber: ["S.No"],
                    targeted: ["T "],
                    schemeName: ["NAME OF SECTOR", "SCHEME NAME", "DESCRIPTION"],
                    headCode: ["HEAD CODE"],
                    estimatedCost: ["EST", "APPR. COST"],
                    expenditure: ["EXP. UPTO"],
                    allocation: ["ALLOCATION FOR", "ALLOC 25-26"],
                    districtCode: ["DIST CODE"],
                    sectorCode: ["SEC CODE"],
                    halqa: ["MLA-WISE", "HALQA"]
                }
            };

            // ==================== STATE ====================
            let state = {
                parsedSchemes: [],
                isProcessing: false,
                shouldAbortProcessing: false
            };

            // ==================== DOM ELEMENTS ====================
            const elements = {
                // Upload form elements
                fileDropZone: document.getElementById('fileDropZone'),
                excelFileInput: document.getElementById('excelFileInput'),
                filePreviewContainer: document.getElementById('filePreviewContainer'),
                selectedFileName: document.getElementById('selectedFileName'),
                selectedFileSize: document.getElementById('selectedFileSize'),

                // Processing overlay
                processingOverlay: document.getElementById('processingOverlay'),
                processingStatusText: document.getElementById('processingStatusText'),
                processingProgressText: document.getElementById('processingProgressText'),

                // Modal elements
                verificationModal: document.getElementById('verificationModal'),
                previewTableBody: document.getElementById('previewTableBody'),
                totalSchemesCount: document.getElementById('totalSchemesCount'),
                targetedSchemesCount: document.getElementById('targetedSchemesCount'),
                nonTargetedSchemesCount: document.getElementById('nonTargetedSchemesCount'),
                totalRowsCount: document.getElementById('totalRowsCount'),
                displayedRowsCount: document.getElementById('displayedRowsCount'),

                // Controls
                currencyUnitSelector: document.getElementById('currencyUnitSelector'),
                rowDisplayLimitSelector: document.getElementById('rowDisplayLimitSelector'),
                financialYearSelect: document.getElementById('financialYearSelect'),
                confirmSyncButton: document.getElementById('confirmSyncButton')
            };

            // ==================== INITIALIZATION ====================
            function initialize() {
                setupDragAndDrop();
                setupFileInputListener();
                setupCleanupListeners();
            }

            // ==================== DRAG & DROP SETUP ====================
            function setupDragAndDrop() {
                const preventDefaults = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                };

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    elements.fileDropZone.addEventListener(eventName, preventDefaults);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    elements.fileDropZone.addEventListener(eventName, () => {
                        elements.fileDropZone.classList.add('border-indigo-500', 'bg-indigo-100');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    elements.fileDropZone.addEventListener(eventName, () => {
                        elements.fileDropZone.classList.remove('border-indigo-500',
                        'bg-indigo-100');
                    });
                });

                elements.fileDropZone.addEventListener('drop', (e) => {
                    elements.excelFileInput.files = e.dataTransfer.files;
                    handleFileSelection(elements.excelFileInput.files);
                });
            }

            function setupFileInputListener() {
                elements.excelFileInput.addEventListener('change', function() {
                    handleFileSelection(this.files);
                });
            }

            function setupCleanupListeners() {
                window.addEventListener('beforeunload', () => {
                    state.shouldAbortProcessing = true;
                });
            }

            // ==================== FILE HANDLING ====================
            async function handleFileSelection(files) {
                if (!files || files.length === 0) return;

                const selectedFile = files[0];

                // Validate file size
                if (selectedFile.size > CONFIG.MAX_FILE_SIZE) {
                    alert(`File too large! Maximum size is ${CONFIG.MAX_FILE_SIZE / (1024 * 1024)}MB.`);
                    clearSelectedFile();
                    return;
                }

                // Update UI with file info
                updateFilePreview(selectedFile);

                // Show processing overlay
                showProcessingOverlay('Reading Excel file...', 'Preparing to process...');

                state.shouldAbortProcessing = false;

                try {
                    // Read and parse the file
                    const arrayBuffer = await readFileAsArrayBuffer(selectedFile);

                    if (state.shouldAbortProcessing) return;

                    await parseExcelFile(arrayBuffer);

                } catch (error) {
                    console.error('File processing error:', error);
                    hideProcessingOverlay();
                    alert('Error processing file: ' + error.message);
                    clearSelectedFile();
                }
            }

            function updateFilePreview(file) {
                elements.selectedFileName.textContent = file.name;
                elements.selectedFileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                elements.filePreviewContainer.classList.remove('hidden');
            }

            function showProcessingOverlay(statusText, progressText) {
                elements.processingOverlay.classList.remove('hidden');
                elements.processingStatusText.textContent = statusText;
                elements.processingProgressText.textContent = progressText;
            }

            function hideProcessingOverlay() {
                elements.processingOverlay.classList.add('hidden');
            }

            async function readFileAsArrayBuffer(file) {
                return new Promise((resolve, reject) => {
                    const fileReader = new FileReader();
                    fileReader.onload = (event) => resolve(event.target.result);
                    fileReader.onerror = reject;
                    fileReader.readAsArrayBuffer(file);
                });
            }

            // ==================== EXCEL PARSING ====================
            async function parseExcelFile(arrayBuffer) {
                showProcessingOverlay('Parsing Excel data...', 'Please wait...');
                await sleep(100);

                // Parse workbook with optimized settings
                const workbook = XLSX.read(new Uint8Array(arrayBuffer), {
                    type: 'array',
                    cellDates: true,
                    cellNF: false,
                    cellText: false
                });

                if (state.shouldAbortProcessing) return;

                // Get target worksheet
                const worksheet = workbook.Sheets[CONFIG.TARGET_SHEET_NAME];

                if (!worksheet) {
                    throw new Error(`Worksheet "${CONFIG.TARGET_SHEET_NAME}" not found in the file.`);
                }

                // Convert to rows
                const rows = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1,
                    raw: false,
                    defval: ''
                });

                showProcessingOverlay('Analyzing data structure...', `Found ${rows.length} rows`);
                await sleep(100);

                if (state.shouldAbortProcessing) return;

                // Validate header row
                validateHeaderRow(rows);

                showProcessingOverlay('Processing schemes...', 'Starting...');

                // Process data in chunks
                const parsedData = await processExcelDataInChunks(rows);

                if (state.shouldAbortProcessing) return;

                state.parsedSchemes = parsedData;

                showProcessingOverlay('Almost done...', `Processed ${parsedData.length} schemes`);
                await sleep(300);

                hideProcessingOverlay();

                // Update statistics and show modal
                updateStatistics(parsedData);
                renderPreviewTable();
            }

            function validateHeaderRow(rows) {
                const headerRow = rows[CONFIG.HEADER_ROW_INDEX];

                if (!headerRow || !headerRow.some(cell => cell && cell.toString().includes("ADP"))) {
                    throw new Error(
                        `Could not find headers at row ${CONFIG.HEADER_ROW_INDEX + 1}. Please check your Excel format.`
                        );
                }
            }

            async function processExcelDataInChunks(rows) {
                const headerRow = rows[CONFIG.HEADER_ROW_INDEX];
                const columnMapping = buildColumnMapping(headerRow);

                const processedSchemes = [];
                const totalRows = rows.length - CONFIG.HEADER_ROW_INDEX - 1;

                for (let rowIndex = CONFIG.HEADER_ROW_INDEX + 1; rowIndex < rows.length; rowIndex++) {
                    if (state.shouldAbortProcessing) return [];

                    const currentRow = rows[rowIndex];

                    // Skip empty rows
                    if (!currentRow || currentRow.length === 0) continue;

                    //ignore after row 1851
                    if (currentRow[8] === 'Block Allocation') {
                        break;
                    }

                    // Process row
                    const schemeData = processSchemeRow(currentRow, columnMapping);

                    if (schemeData) {
                        processedSchemes.push(schemeData);
                    }

                    // Update progress periodically
                    if (rowIndex % 100 === 0) {
                        const processedCount = rowIndex - CONFIG.HEADER_ROW_INDEX;
                        elements.processingProgressText.textContent =
                            `Processing: ${processedCount} / ${totalRows} rows`;
                        await sleep(CONFIG.UI_UPDATE_DELAY);
                    }
                }

                return processedSchemes;
            }

            function buildColumnMapping(headerRow) {
                const findColumnIndex = (keywords) => {
                    return headerRow.findIndex(cell =>
                        cell && keywords.some(keyword =>
                            cell.toString().toUpperCase().includes(keyword.toUpperCase())
                        )
                    );
                };

                return {
                    adpNumber: findColumnIndex(CONFIG.COLUMN_KEYWORDS.adpNumber),
                    serialNumber: findColumnIndex(CONFIG.COLUMN_KEYWORDS.serialNumber),
                    targeted: findColumnIndex(CONFIG.COLUMN_KEYWORDS.targeted),
                    schemeName: findColumnIndex(CONFIG.COLUMN_KEYWORDS.schemeName),
                    headCode: findColumnIndex(CONFIG.COLUMN_KEYWORDS.headCode),
                    estimatedCost: findColumnIndex(CONFIG.COLUMN_KEYWORDS.estimatedCost),
                    expenditure: findColumnIndex(CONFIG.COLUMN_KEYWORDS.expenditure),
                    allocation: findColumnIndex(CONFIG.COLUMN_KEYWORDS.allocation),
                    districtCode: findColumnIndex(CONFIG.COLUMN_KEYWORDS.districtCode),
                    sectorCode: findColumnIndex(CONFIG.COLUMN_KEYWORDS.sectorCode),
                    halqa: findColumnIndex(CONFIG.COLUMN_KEYWORDS.halqa)
                };
            }

            function processSchemeRow(row, columnMapping) {
                // Extract head code
                const headCode = row[columnMapping.headCode] ? row[columnMapping.headCode].toString().trim() : "";

                // Skip ignored head codes (placeholder rows)
                if (CONFIG.IGNORED_HEAD_CODES.includes(headCode)) {
                    return null;
                }

                // Extract scheme name
                const schemeName = row[columnMapping.schemeName] ? row[columnMapping.schemeName].toString().trim() :
                    "";
                const adpNumber = row[columnMapping.adpNumber] ? row[columnMapping.adpNumber].toString().trim() :
                "";

                // Skip invalid rows (sector headers, empty rows)
                if (!schemeName || schemeName.length < 5 || (adpNumber === "" && row[1] === "")) {
                    return null;
                }

                // Parse targeted status
                const targetedValue = row[10] ? row[10].toString().trim().toUpperCase() : "";
                const isTargeted = (targetedValue === 'T');

                // Parse and clean approval date
                const rawApprovalDate = row[14] ? row[14].toString() : "";
                const cleanedApprovalDate = rawApprovalDate.replace(/,/g, '').trim();
                
              
                // Parse financial values
                const estimatedCost = parseFloat(row[columnMapping.estimatedCost]) || 0;
                const expenditureToDate = parseFloat(row[columnMapping.expenditure]) || 0;
                const allocation = parseFloat(row[columnMapping.allocation]) || 0;
          
                // Build scheme object
                return {
                    adpNumber: adpNumber || "NEW",
                    adpCode: row[1] || "",
                    description: schemeName,
                    sectorCode: row[columnMapping.sectorCode] || "N/A",
                    districtCode: row[columnMapping.districtCode] || "N/A",
                    halqa: row[columnMapping.halqa] || "N/A",
                    isTargeted: isTargeted ? 'Yes' : 'No',
                    estimatedCost: estimatedCost,
                    expenditureToDate: expenditureToDate,
                    throwForward: estimatedCost - expenditureToDate,
                    allocation: allocation,
                    headCode: headCode,
                    approvalDate: cleanedApprovalDate
                };
            }

            // ==================== UI RENDERING ====================
            function updateStatistics(schemes) {
                const targetedCount = schemes.filter(s => s.isTargeted === 'Yes').length;
                const nonTargetedCount = schemes.filter(s => s.isTargeted === 'No').length;

                elements.totalSchemesCount.textContent = schemes.length;
                elements.totalRowsCount.textContent = schemes.length;
                elements.targetedSchemesCount.textContent = targetedCount;
                elements.nonTargetedSchemesCount.textContent = nonTargetedCount;
            }

            function renderPreviewTable() {
                const currencyUnit = elements.currencyUnitSelector.value;
                const displayLimit = elements.rowDisplayLimitSelector.value;

                const formatCurrency = (value) => {
                    if (currencyUnit === 'millions') {
                        return (value / 1000000).toFixed(2) + ' M';
                    }
                    return new Intl.NumberFormat('en-PK').format(value);
                };

                // Determine display limit
                const rowLimit = displayLimit === 'all' ? state.parsedSchemes.length : parseInt(displayLimit);
                const schemesToDisplay = state.parsedSchemes.slice(0, rowLimit);

                elements.displayedRowsCount.textContent = schemesToDisplay.length;

                // Use DocumentFragment for efficient DOM manipulation
                const tableFragment = document.createDocumentFragment();

                schemesToDisplay.forEach((scheme, index) => {
                    const row = createTableRow(scheme, index + 1, formatCurrency);
                    tableFragment.appendChild(row);
                });

                // Update table in one operation
                elements.previewTableBody.innerHTML = '';
                elements.previewTableBody.appendChild(tableFragment);

                // Show modal
                elements.verificationModal.classList.remove('hidden');
            }

            function createTableRow(scheme, rowNumber, formatCurrency) {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';

                const statusBadgeClass = scheme.isTargeted === 'Yes' ?
                    'bg-green-100 text-green-700' :
                    'bg-red-100 text-red-700';

                const statusDotClass = scheme.isTargeted === 'Yes' ?
                    'bg-green-500' :
                    'bg-red-500';

                tr.innerHTML = `
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs border-r border-gray-100">${rowNumber}</td>
                    <td class="px-4 py-3 font-mono font-bold text-indigo-700 border-r border-gray-100">${scheme.adpCode}</td>
                    <td class="px-4 py-3 text-gray-800 font-medium border-r border-gray-100">
                        <div class="max-w-md truncate" title="${scheme.description}">${scheme.description}</div>
                    </td>
                    <td class="px-4 py-3 text-center border-r border-gray-100 text-gray-600 font-mono text-xs">${scheme.approvalDate}</td>
                    <td class="px-4 py-3 text-[10px] text-gray-500 border-r border-gray-100">
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>${scheme.districtCode} / ${scheme.halqa}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center border-r border-gray-100 text-gray-700 font-semibold">${scheme.districtCode}</td>
                    <td class="px-4 py-3 text-center border-r border-gray-100">
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase ${statusBadgeClass} inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full ${statusDotClass}"></span>
                            ${scheme.isTargeted}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-mono font-bold text-gray-900 border-r border-gray-100">${formatCurrency(scheme.expenditureToDate)}</td>
                    <td class="px-4 py-3 text-right font-mono font-bold text-gray-900 border-r border-gray-100">${formatCurrency(scheme.estimatedCost)}</td>
                    <td class="px-4 py-3 text-right font-mono text-orange-600 font-semibold border-r border-gray-100">${formatCurrency(scheme.throwForward)}</td>
                    <td class="px-4 py-3 text-right font-mono text-indigo-600 font-semibold border-r border-gray-100">${formatCurrency(scheme.allocation)}</td>
                `;

                return tr;
            }

            // ==================== DATA SUBMISSION ====================
            async function submitADPData() {
                const financialYear = elements.financialYearSelect.value;
                const confirmButton = elements.confirmSyncButton;
                const originalButtonHTML = confirmButton.innerHTML;

                confirmButton.disabled = true;
                confirmButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-3 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Syncing ${state.parsedSchemes.length} schemes...
                `;

                try {
                    const response = await fetch("{{ route('adp.storeFormulation') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            data: state.parsedSchemes,
                            financial_year: financialYear
                        })
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        confirmButton.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Success!
                        `;

                        setTimeout(() => {
                            window.location.href = "{{ route('adp.formulation') }}";
                        }, 1000);
                    } else {
                        throw new Error(result.message || 'Upload failed');
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Error: ' + error.message);
                    confirmButton.disabled = false;
                    confirmButton.innerHTML = originalButtonHTML;
                }
            }

            // ==================== UTILITY FUNCTIONS ====================
            function sleep(milliseconds) {
                return new Promise(resolve => setTimeout(resolve, milliseconds));
            }

            function clearSelectedFile() {
                state.shouldAbortProcessing = true;
                elements.excelFileInput.value = '';
                elements.filePreviewContainer.classList.add('hidden');
                hideProcessingOverlay();
                state.parsedSchemes = [];
            }

            function closeVerificationModal() {
                elements.verificationModal.classList.add('hidden');
            }

            // ==================== PUBLIC API ====================
            return {
                initialize,
                renderPreviewTable,
                submitADPData,
                closeVerificationModal,
                clearSelectedFile
            };
        })();

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            ADPUploader.initialize();
        });
    </script>
@endpush
