@extends('layouts.app')

@section('title', 'File Upload - GB PND Budget System')
@section('hide_floating_btn', true)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">SAP Data Upload</h2>
                    <p class="text-gray-500">Import your SAP Excel dump for budget analysis</p>
                </div>
            </div>
        </div>

        {{-- Upload Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form id="uploadForm" action="{{ route('sap.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Upload Section --}}
                <div class="p-8 lg:p-10">
                    {{-- Drag and Drop Zone --}}
                    <div id="dropZone"
                        class="relative border-2 border-dashed {{ $errors->has('sap_file') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gradient-to-br from-gray-50 to-blue-50/30' }} rounded-2xl p-12 lg:p-16 text-center hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-300 cursor-pointer group">
                        
                        {{-- Background Pattern --}}
                        <div class="absolute inset-0 opacity-5 pointer-events-none">
                            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                        <circle cx="10" cy="10" r="1" fill="currentColor" class="text-blue-600"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#grid)"/>
                            </svg>
                        </div>

                        <div class="relative z-10">
                            {{-- Upload Icon --}}
                            <div class="mx-auto w-20 h-20 mb-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2">Drop your SAP Excel file here</h3>
                            <p class="text-gray-500 mb-1">or click to browse from your computer</p>
                            <p class="text-sm text-gray-400 mb-6">Supports .XLS and .XLSX files up to 10MB</p>

                            {{-- Browse Button --}}
                            <label for="fileInput"
                                class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Browse Files
                            </label>

                            <input type="file" id="fileInput" name="sap_file" class="hidden" accept=".xlsx,.xls">
                        </div>
                    </div>

                    {{-- File Preview Section --}}
                    <div id="filePreview" class="hidden mt-6">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="fileName">file.xlsx</p>
                                    <p class="text-sm text-gray-600" id="fileSize">0 KB</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Ready</span>
                                <button type="button" onclick="clearFile()" class="p-2 hover:bg-green-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Configuration Section --}}
                <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 px-8 lg:px-10 py-8 border-t border-gray-100">
                    <h4 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Upload Configuration
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Financial Year --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                Financial Year
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="financial_year"
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 appearance-none cursor-pointer font-medium">
                                    <option value="2023-24">2023-24</option>
                                    <option value="2024-25" selected>2024-25</option>
                                    <option value="2025-26">2025-26</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Report Date --}}
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                Report "As Of" Date
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="asOfDate" name="as_of_date" value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 font-medium">
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Date when this data was exported from SAP
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Preview Modal --}}
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal Container --}}
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-7xl overflow-hidden transform transition-all">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 border-b border-blue-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">Data Verification</h3>
                                <p class="text-blue-100 text-sm">Review <span id="totalRowsCount" class="font-bold">0</span> records before processing</p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Content --}}
                <div class="p-8 max-h-[calc(100vh-200px)] overflow-y-auto">
                    {{-- Statistics Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border-2 border-blue-200 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Projects</p>
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p id="statRows" class="text-3xl font-bold text-blue-900">0</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border-2 border-green-200 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Total Budget</p>
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p id="statBudget" class="text-3xl font-bold text-green-900">0</p>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl border-2 border-yellow-200 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Total Releases</p>
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p id="statReleases" class="text-3xl font-bold text-yellow-900">0</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border-2 border-purple-200 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Expenditure</p>
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p id="statExp" class="text-3xl font-bold text-purple-900">0</p>
                        </div>
                    </div>

                    {{-- Control Bar --}}
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 border-2 border-gray-200 rounded-xl p-4 mb-6">
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Row Range --}}
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-bold text-gray-700">Show Rows:</span>
                                <input type="number" id="rangeStart" value="1" min="1"
                                    class="w-20 px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <span class="text-gray-400 font-medium">to</span>
                                <input type="number" id="rangeEnd" value="100"
                                    class="w-20 px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            </div>

                            {{-- Unit Selector --}}
                            <div class="flex items-center space-x-3 border-l-2 border-gray-300 pl-4">
                                <span class="text-sm font-bold text-gray-700">Display Unit:</span>
                                <select id="unitSelector"
                                    class="px-4 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer">
                                    <option value="actual">Actual PKR</option>
                                    <option value="millions">In Millions (M)</option>
                                </select>
                            </div>

                            {{-- Apply Button --}}
                            <button onclick="applyFilters()"
                                class="ml-auto px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    {{-- Loading Overlay --}}
                    <div id="modalLoader" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-white/90 backdrop-blur-sm">
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center mb-4">
                                <div class="w-16 h-16 border-4 border-blue-200 rounded-full"></div>
                                <div class="w-16 h-16 border-4 border-blue-600 rounded-full animate-spin border-t-transparent border-r-transparent absolute"></div>
                                <div class="w-12 h-12 border-4 border-blue-400 rounded-full animate-spin border-b-transparent border-l-transparent absolute" style="animation-duration: 1.5s; animation-direction: reverse;"></div>
                            </div>
                            <p class="text-lg font-bold text-gray-900">Processing Data...</p>
                            <p class="text-sm text-gray-500">Formatting rows for display</p>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div class="border-2 border-gray-200 rounded-xl overflow-hidden shadow-lg">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gradient-to-r from-gray-100 to-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4 font-bold text-gray-700 text-left border-b-2 border-gray-200">ADP NO</th>
                                        <th class="px-6 py-4 font-bold text-gray-700 text-left border-b-2 border-gray-200">Description</th>
                                        <th class="px-6 py-4 font-bold text-gray-700 text-right border-b-2 border-gray-200">Budget</th>
                                        <th class="px-6 py-4 font-bold text-gray-700 text-right border-b-2 border-gray-200">Releases</th>
                                        <th class="px-6 py-4 font-bold text-gray-700 text-left border-b-2 border-gray-200">WBS Element</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="divide-y divide-gray-100 bg-white">
                                    {{-- Rows inserted via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-t-2 border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <button onclick="closeModal()"
                        class="w-full sm:w-auto px-6 py-3 text-gray-700 font-semibold hover:bg-gray-100 rounded-xl transition-colors">
                        Cancel & Re-upload
                    </button>
                    <button type="button" onclick="submitFinalData()"
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirm & Save to Database
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
            const uploadForm = document.getElementById('uploadForm');
            let globalSapData = [];

            // Drag and Drop Event Handlers
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-blue-500', 'bg-blue-100', 'scale-105');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-blue-500', 'bg-blue-100', 'scale-105');
                });
            });

            dropZone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                handleFiles(files);
            });

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            /**
             * Handle file selection and parsing
             */
            function handleFiles(files) {
                if (files.length === 0) return;

                const file = files[0];
                
                // Show file preview
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                filePreview.classList.remove('hidden');

                const reader = new FileReader();

                reader.onload = function(e) {
                    try {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
                        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
                        const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                        // Find header row
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

                        // Map column positions
                        const headerRow = rows[headerRowIndex];
                        const colMap = {
                            adp: headerRow.indexOf("ADP NO"),
                            desc: headerRow.findIndex(cell => cell && cell.toString().includes("Project Description")),
                            budget: headerRow.findIndex(cell => cell && cell.toString().includes("Final Budget")),
                            releases: headerRow.findIndex(cell => cell && cell.toString().includes("Prog Releases")),
                            exp: headerRow.findIndex(cell => cell && cell.toString().includes("Progressive Exp")),
                            wbs: headerRow.findIndex(cell => cell && cell.toString().includes("WBS Element"))
                        };

                        // Parse data rows
                        const filteredData = [];
                        for (let i = headerRowIndex + 1; i < rows.length; i++) {
                            const row = rows[i];
                            const adpNo = row[colMap.adp] ? row[colMap.adp].toString().trim() : null;
                            const wbs = row[colMap.wbs] ? row[colMap.wbs].toString().trim() : null;

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

                        renderModalContent();
                    } catch (error) {
                        console.error('File parsing error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Error parsing Excel file. Please check the format.'
                        });
                    }
                };

                reader.readAsArrayBuffer(file);
            }

            /**
             * Render modal content with filters
             */
            window.renderModalContent = function() {
                const start = parseInt(document.getElementById('rangeStart').value) - 1;
                const end = parseInt(document.getElementById('rangeEnd').value);
                const unit = document.getElementById('unitSelector').value;

                // Calculate totals
                const totalBudget = globalSapData.reduce((sum, r) => sum + r.Final_Budget, 0);
                const totalRel = globalSapData.reduce((sum, r) => sum + r.Releases, 0);
                const totalExp = globalSapData.reduce((sum, r) => sum + r.Expenditure, 0);

                // Format helper
                const formatValue = (val) => {
                    if (unit === 'millions') {
                        return (val / 1000000).toFixed(2) + ' M';
                    }
                    return new Intl.NumberFormat('en-PK').format(val);
                };

                // Update statistics
                document.getElementById('statRows').innerText = globalSapData.length.toLocaleString();
                document.getElementById('statBudget').innerText = 'Rs ' + formatValue(totalBudget);
                document.getElementById('statReleases').innerText = 'Rs ' + formatValue(totalRel);
                document.getElementById('statExp').innerText = 'Rs ' + formatValue(totalExp);

                // Render table rows
                const tbody = document.getElementById('previewTableBody');
                const slicedData = globalSapData.slice(start, end);

                let htmlBuffer = '';
                slicedData.forEach(row => {
                    htmlBuffer += `
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-blue-700 font-bold">${row.ADP_NO}</td>
                            <td class="px-6 py-4 text-gray-700 truncate max-w-xs" title="${row.Description}">${row.Description}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">${formatValue(row.Final_Budget)}</td>
                            <td class="px-6 py-4 text-right font-semibold text-green-700">${formatValue(row.Releases)}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">${row.WBS}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = htmlBuffer;

                if (end - start > 1000) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Showing many rows may slow down the browser.'
                    });
                }

                document.getElementById('previewModal').classList.remove('hidden');
            };

            /**
             * Apply filters with loader
             */
            window.applyFilters = function() {
                const loader = document.getElementById('modalLoader');
                loader.classList.remove('hidden');

                setTimeout(() => {
                    try {
                        renderModalContent();
                    } finally {
                        loader.classList.add('hidden');
                    }
                }, 50);
            };

            /**
             * Close modal
             */
            window.closeModal = function() {
                document.getElementById('previewModal').classList.add('hidden');
                uploadForm.reset();
                filePreview.classList.add('hidden');
            };

            /**
             * Clear selected file
             */
            window.clearFile = function() {
                fileInput.value = '';
                filePreview.classList.add('hidden');
                globalSapData = [];
            };

            /**
             * Submit data to server
             */
            window.submitFinalData = async function() {
                const asOfDate = document.getElementById('asOfDate').value;
                const finYear = document.querySelector('select[name="financial_year"]').value;

                if (!asOfDate) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please select a Report "As Of" Date'
                    });
                    return;
                }

                const confirmBtn = document.querySelector('button[onclick="submitFinalData()"]');
                const originalHTML = confirmBtn.innerHTML;
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;

                try {
                    const response = await fetch("{{ route('sap.storeBatch') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            data: globalSapData,
                            as_of_date: asOfDate,
                            financial_year: finYear
                        })
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        Toast.fire({
                            icon: 'success',
                            title: result.message
                        });
                        setTimeout(() => {
                            window.location.href = "{{ route('sap.list') }}";
                        }, 1500);
                    } else {
                        throw new Error(result.message || 'Server Error');
                    }
                } catch (error) {
                    console.error(error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Upload Failed: ' + error.message
                    });
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = originalHTML;
                }
            };

            /**
             * Format file size
             */
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }
        })();
    </script>
@endpush