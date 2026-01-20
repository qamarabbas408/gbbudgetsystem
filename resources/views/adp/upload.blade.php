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
            <form id="uploadForm" action="{{ route('adp.storeFormulation') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 lg:p-10">
                    {{-- Drag and Drop Zone --}}
                    <div id="dropZone"
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

                            <label for="fileInput"
                                class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 cursor-pointer transition-all duration-300 shadow-lg transform hover:-translate-y-0.5">
                                Browse ADP File
                            </label>

                            <input type="file" id="fileInput" name="adp_file" class="hidden" accept=".xlsx,.xls">
                        </div>

                        {{-- Loading Overlay --}}
                        <div id="loadingOverlay" class="hidden absolute inset-0 bg-white/95 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center z-20">
                            <div class="relative">
                                <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-indigo-900" id="loadingText">Processing Excel file...</p>
                            <p class="mt-2 text-xs text-gray-600" id="loadingProgress">Reading data...</p>
                        </div>
                    </div>

                    {{-- File Preview --}}
                    <div id="filePreview" class="hidden mt-6">
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    XLS</div>
                                <div>
                                    <p class="font-bold text-indigo-900 text-sm" id="fileName">file.xlsx</p>
                                    <p class="text-xs text-indigo-600" id="fileSize">0 KB</p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-indigo-400 hover:text-indigo-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Configuration --}}
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Planning Year</label>
                        <select name="financial_year"
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
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-7xl overflow-hidden max-h-[90vh] flex flex-col">
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6 flex justify-between items-center text-white flex-shrink-0">
                    <div>
                        <h3 class="text-2xl font-bold">ADP Verification</h3>
                        <p class="text-indigo-100 text-sm">
                            Reviewing <span id="totalRowsCount" class="font-bold">0</span> schemes 
                            (<span id="approvedCount" class="text-green-300">0</span> approved, 
                            <span id="unapprovedCount" class="text-red-300">0</span> un-approved)
                        </p>
                    </div>
                    <button onclick="closeModal()" class="hover:bg-white/10 p-2 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-8 overflow-y-auto flex-1">
                    {{-- Controls --}}
                    <div class="flex items-center justify-between gap-4 mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black text-gray-400 uppercase">Display Unit:</span>
                            <select id="unitSelector" onchange="renderModalContent()"
                                class="text-sm font-bold text-indigo-600 bg-white px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <option value="actual">Actual PKR</option>
                                <option value="millions">Millions (M)</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-gray-400 uppercase">Show:</span>
                            <select id="rowLimitSelector" onchange="renderModalContent()"
                                class="text-sm font-bold text-indigo-600 bg-white px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <option value="50">50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="250">250 rows</option>
                                <option value="500">500 rows</option>
                                <option value="all">All rows</option>
                            </select>
                            <span class="text-[10px] text-gray-400 italic">Showing <span id="displayedRowsCount">0</span> of <span id="totalRowsCount2">0</span></span>
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
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 min-w-[300px]">Scheme Name</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-center">Status</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">Est. Cost</th>
                                        <th class="px-4 py-3 font-bold uppercase border-r border-gray-700 text-right">Allocation</th>
                                        <th class="px-4 py-3 font-bold uppercase">District / Halqa</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="divide-y divide-gray-100 bg-white">
                                    {{-- Rows rendered via JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-gray-50 px-8 py-6 border-t-2 border-gray-200 flex justify-between items-center flex-shrink-0">
                    <button onclick="closeModal()" class="px-6 py-2.5 text-gray-600 font-bold hover:text-gray-800 hover:bg-gray-200 rounded-xl transition-all">
                        Cancel
                    </button>
                    <button onclick="submitFinalData()" id="confirmBtn"
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
        (function() {
            'use strict';

            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');
            const loadingProgress = document.getElementById('loadingProgress');
            
            let globalSapData = [];
            let processingAborted = false;

            // 1. Drag and Drop Setup
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(name => {
                dropZone.addEventListener(name, e => { 
                    e.preventDefault(); 
                    e.stopPropagation(); 
                });
            });

            ['dragenter', 'dragover'].forEach(name => {
                dropZone.addEventListener(name, () => {
                    dropZone.classList.add('border-indigo-500', 'bg-indigo-100');
                });
            });

            ['dragleave', 'drop'].forEach(name => {
                dropZone.addEventListener(name, () => {
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-100');
                });
            });

            dropZone.addEventListener('drop', e => {
                fileInput.files = e.dataTransfer.files;
                handleFiles(fileInput.files);
            });

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            // 2. Optimized File Parsing Logic with Async Processing
            async function handleFiles(files) {
                if (files.length === 0) return;
                
                const file = files[0];
                
                // Validate file size (max 50MB)
                if (file.size > 50 * 1024 * 1024) {
                    alert('File too large! Maximum size is 50MB.');
                    clearFile();
                    return;
                }

                // Update UI
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
                filePreview.classList.remove('hidden');
                
                // Show loading overlay
                loadingOverlay.classList.remove('hidden');
                loadingText.textContent = 'Reading Excel file...';
                loadingProgress.textContent = 'Preparing to process...';
                
                processingAborted = false;

                try {
                    // Read file asynchronously
                    const arrayBuffer = await readFileAsync(file);
                    
                    if (processingAborted) return;
                    
                    loadingText.textContent = 'Parsing Excel data...';
                    loadingProgress.textContent = 'Please wait...';
                    
                    // Use setTimeout to allow UI to update
                    await sleep(100);
                    
                    // Parse workbook
                    const workbook = XLSX.read(new Uint8Array(arrayBuffer), { 
                        type: 'array',
                        cellDates: true,
                        cellNF: false,
                        cellText: false
                    });
                    
                    if (processingAborted) return;
                    
                    const worksheet = workbook.Sheets[workbook.SheetNames[0]];
                    const rows = XLSX.utils.sheet_to_json(worksheet, { 
                        header: 1,
                        raw: false,
                        defval: ''
                    });

                    loadingText.textContent = 'Analyzing data structure...';
                    loadingProgress.textContent = `Found ${rows.length} rows`;
                    
                    await sleep(100);
                    
                    if (processingAborted) return;

                    // Find header row
                    const headerRowIndex = findHeaderRow(rows);
                    
                    if (headerRowIndex === -1) {
                        throw new Error('Could not detect ADP Header row! Please ensure your Excel file has proper headers.');
                    }

                    loadingText.textContent = 'Processing schemes...';
                    
                    // Process data in chunks to prevent UI freezing
                    const filteredData = await processDataInChunks(rows, headerRowIndex);
                    
                    if (processingAborted) return;
                    
                    globalSapData = filteredData;
                    
                    loadingText.textContent = 'Almost done...';
                    loadingProgress.textContent = `Processed ${filteredData.length} schemes`;
                    
                    await sleep(300);
                    
                    // Hide loading and show modal
                    loadingOverlay.classList.add('hidden');
                    
                    // Update counts
                    const approved = filteredData.filter(r => r.Status === 'Approved').length;
                    const unapproved = filteredData.filter(r => r.Status === 'Un-Approved').length;
                    
                    document.getElementById('totalRowsCount').textContent = filteredData.length;
                    document.getElementById('totalRowsCount2').textContent = filteredData.length;
                    document.getElementById('approvedCount').textContent = approved;
                    document.getElementById('unapprovedCount').textContent = unapproved;
                    
                    renderModalContent();
                    
                } catch (err) {
                    console.error('Error processing file:', err);
                    loadingOverlay.classList.add('hidden');
                    alert('Error processing file: ' + err.message);
                    clearFile();
                }
            }

            // Helper: Read file as ArrayBuffer asynchronously
            function readFileAsync(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = e => resolve(e.target.result);
                    reader.onerror = reject;
                    reader.readAsArrayBuffer(file);
                });
            }

            // Helper: Sleep function for UI updates
            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            // Helper: Find header row efficiently
            function findHeaderRow(rows) {
                const maxRowsToCheck = Math.min(rows.length, 30);
                for (let i = 0; i < maxRowsToCheck; i++) {
                    if (rows[i] && rows[i].some(cell => cell && cell.toString().toUpperCase().includes("ADP"))) {
                        return i;
                    }
                }
                return -1;
            }

            // Helper: Process data in chunks to prevent UI freezing
            async function processDataInChunks(rows, headerRowIndex) {
                const headerRow = rows[headerRowIndex];
                const findCol = (keys) => headerRow.findIndex(c => 
                    c && keys.some(k => c.toString().toUpperCase().includes(k.toUpperCase()))
                );

                const colMap = {
                    adp: findCol(["ADP#", "ADP NO"]),
                    name: findCol(["NAME OF SECTOR", "SCHEME NAME", "DESCRIPTION"]),
                    cost: findCol(["EST", "APPR. COST"]),
                    exp: findCol(["EXP. UPTO"]),
                    alloc: findCol(["ALLOCATION FOR", "ALLOC 25-26", "ALLOCATION 2025-26"]),
                    dist: findCol(["DIST CODE", "DISTRICT"]),
                    sec: findCol(["SEC CODE", "SECTOR"]),
                    halqa: findCol(["MLA-WISE", "HALQA"]),
                    head: findCol(["HEAD CODE"])
                };

                const filteredData = [];
                const chunkSize = 100; // Process 100 rows at a time
                const totalRows = rows.length - headerRowIndex - 1;
                
                for (let i = headerRowIndex + 1; i < rows.length; i += chunkSize) {
                    if (processingAborted) return [];
                    
                    const chunk = rows.slice(i, Math.min(i + chunkSize, rows.length));
                    
                    // Update progress
                    const processed = Math.min(i - headerRowIndex, totalRows);
                    loadingProgress.textContent = `Processing: ${processed} / ${totalRows} rows`;
                    
                    // Process chunk
                    chunk.forEach(row => {
                        const schemeName = row[colMap.name] ? row[colMap.name].toString().trim() : null;
                        
                        // Skip invalid rows
                        if (!schemeName || schemeName.length < 5) return;
                        
                        const adpVal = row[colMap.adp] ? row[colMap.adp].toString().trim() : "";
                        const isApproved = adpVal && adpVal !== "" && !adpVal.toUpperCase().includes("NEW");

                        filteredData.push({
                            "ADP_NO": adpVal || "NEW",
                            "Description": schemeName,
                            "Sector": row[colMap.sec] || "N/A",
                            "District": row[colMap.dist] || "N/A",
                            "Halqa": row[colMap.halqa] || "N/A",
                            "Status": isApproved ? "Approved" : "Un-Approved",
                            "Est_Cost": parseFloat(row[colMap.cost]) || 0,
                            "Exp_June": parseFloat(row[colMap.exp]) || 0,
                            "Allocation": parseFloat(row[colMap.alloc]) || 0,
                            "Head": row[colMap.head] || ""
                        });
                    });
                    
                    // Allow UI to breathe
                    await sleep(10);
                }
                
                return filteredData;
            }

            // 3. Optimized Modal Rendering with Virtual Scrolling
            window.renderModalContent = function() {
                const unit = document.getElementById('unitSelector').value;
                const rowLimit = document.getElementById('rowLimitSelector').value;
                
                const format = (val) => {
                    if (unit === 'millions') {
                        return (val / 1000000).toFixed(2) + ' M';
                    }
                    return new Intl.NumberFormat('en-PK').format(val);
                };

                const tbody = document.getElementById('previewTableBody');
                
                // Determine how many rows to display
                const displayLimit = rowLimit === 'all' ? globalSapData.length : parseInt(rowLimit);
                const dataToDisplay = globalSapData.slice(0, displayLimit);
                
                document.getElementById('displayedRowsCount').textContent = dataToDisplay.length;
                
                // Use DocumentFragment for better performance
                const fragment = document.createDocumentFragment();
                
                dataToDisplay.forEach((row, index) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 transition-colors';
                    
                    const statusClass = row.Status === 'Approved' 
                        ? 'bg-green-100 text-green-700' 
                        : 'bg-red-100 text-red-700';
                    
                    tr.innerHTML = `
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs border-r border-gray-100">${index + 1}</td>
                        <td class="px-4 py-3 font-mono font-bold text-indigo-700 border-r border-gray-100">${row.ADP_NO}</td>
                        <td class="px-4 py-3 text-gray-800 font-medium border-r border-gray-100">
                            <div class="max-w-md truncate" title="${row.Description}">${row.Description}</div>
                        </td>
                        <td class="px-4 py-3 text-center border-r border-gray-100">
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase ${statusClass} inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full ${row.Status === 'Approved' ? 'bg-green-500' : 'bg-red-500'}"></span>
                                ${row.Status}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-gray-900 border-r border-gray-100">${format(row.Est_Cost)}</td>
                        <td class="px-4 py-3 text-right font-mono text-indigo-600 font-semibold border-r border-gray-100">${format(row.Allocation)}</td>
                        <td class="px-4 py-3 text-[10px] text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>${row.District} / ${row.Halqa}</span>
                            </div>
                        </td>
                    `;
                    
                    fragment.appendChild(tr);
                });
                
                // Clear and append all at once
                tbody.innerHTML = '';
                tbody.appendChild(fragment);
                
                // Show modal
                document.getElementById('previewModal').classList.remove('hidden');
            };

            // 4. Optimized Final Submission Logic
            window.submitFinalData = async function() {
                const finYear = document.querySelector('select[name="financial_year"]').value;
                const confirmBtn = document.getElementById('confirmBtn');
                const originalHTML = confirmBtn.innerHTML;

                confirmBtn.disabled = true;
                confirmBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-3 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Syncing ${globalSapData.length} schemes...
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
                            data: globalSapData,
                            financial_year: finYear
                        })
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        confirmBtn.innerHTML = `
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
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = originalHTML;
                }
            };

            // Helper Functions
            window.closeModal = () => {
                document.getElementById('previewModal').classList.add('hidden');
            };
            
            window.clearFile = () => {
                processingAborted = true;
                fileInput.value = '';
                filePreview.classList.add('hidden');
                loadingOverlay.classList.add('hidden');
                globalSapData = [];
            };

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                processingAborted = true;
            });
        })();
    </script>
@endpush