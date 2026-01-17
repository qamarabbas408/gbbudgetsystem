@extends('layouts.app')
@section('hide_floating_btn', true)

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-2xl opacity-50 blur-3xl -z-10"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-gray-100">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 bg-clip-text text-transparent">
                            Department Mapping Engine
                        </h2>
                    </div>
                    <p class="text-sm text-gray-600 ml-[52px]">
                        Intelligent rule-based mapping for both ADP and SDG funding streams.
                    </p>
                </div>

                <form action="{{ route('mappings.sync') }}" method="POST" id="syncForm">
                    @csrf
                    <button type="submit"
                        class="group relative bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 shadow-lg shadow-green-200 hover:shadow-xl hover:shadow-green-300 transition-all duration-300 flex items-center gap-2 overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="relative z-10">Sync All Data</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- FORM COLUMN --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 h-fit overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 relative">
                    <h3 class="font-bold text-white text-lg relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Assignment Rule
                    </h3>
                </div>

                <form action="{{ route('mappings.store') }}" method="POST" id="createForm">
                    @csrf
                    <div class="p-6 space-y-6">
                        {{-- Mapping Type (ADP vs SDG) --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 block flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span>
                                Mapping Category
                            </label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="ADP" class="hidden peer" checked>
                                    <div class="text-center p-3 rounded-xl border-2 border-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="text-sm font-bold text-gray-400 peer-checked:text-blue-700">ADP</div>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="SDG" class="hidden peer">
                                    <div class="text-center p-3 rounded-xl border-2 border-gray-100 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                                        <div class="text-sm font-bold text-gray-400 peer-checked:text-purple-700">SDG</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Department Selection --}}
                        <div class="group">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                Target Department
                            </label>
                            <select name="department_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl appearance-none bg-white cursor-pointer hover:border-gray-300 transition-all">
                                <option value="">Select a department...</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->sector->name }} → {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ADP Range Inputs --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span>
                                    From #
                                </label>
                                <input type="text" name="start_adp" placeholder="A0001" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-mono text-sm hover:border-gray-300">
                            </div>
                            <div class="group">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-purple-600 rounded-full"></span>
                                    To # (Optional)
                                </label>
                                <input type="text" name="end_adp" placeholder="Leave for single"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-mono text-sm hover:border-gray-300">
                            </div>
                        </div>

                        <button type="submit"
                            class="group relative w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg transition-all duration-300 overflow-hidden">
                            <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Apply & Save Rule
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- LIST COLUMN --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        Active Mapping Rules
                        <span class="ml-auto text-sm font-normal text-gray-500">{{ count($mappings) }} total rules</span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-700 text-xs border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Type / Stream</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Department</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider">Assigned Range</th>
                                <th class="px-6 py-4 font-bold uppercase tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($mappings as $index => $mapping)
                                <tr class="group hover:bg-gray-50 transition-all duration-200"
                                    style="animation: fadeInUp 0.3s ease-out {{ $index * 0.05 }}s backwards;">
                                    <td class="px-6 py-5">
                                        @if($mapping->type === 'SDG')
                                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-purple-200">
                                                SDG
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-200">
                                                ADP
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">{{ $mapping->department->sector->name }}</div>
                                        <div class="font-semibold text-gray-900">{{ $mapping->department->name }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if ($mapping->start_adp === $mapping->end_adp)
                                            <div class="inline-flex items-center gap-2 font-mono bg-green-50 text-green-700 px-3 py-1.5 rounded-lg border border-green-200 shadow-sm text-xs font-bold">
                                                Single: {{ $mapping->start_adp }}
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-2 font-mono bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg border border-blue-200 shadow-sm text-xs font-bold">
                                                {{ $mapping->start_adp }} <svg class="w-3 h-3 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg> {{ $mapping->end_adp }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openEditModal({{ $mapping->toJson() }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('mappings.destroy', $mapping->id) }}" method="POST" onsubmit="return confirm('Delete this rule?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-16 text-center text-gray-400">No mapping rules defined.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" id="editModalContent">
            <form id="editForm" method="POST">
                @csrf @method('PATCH')
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white flex justify-between items-center">
                    <h3 class="text-xl font-bold">Edit Mapping Rule</h3>
                    <button type="button" onclick="closeEditModal()" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Edit Type --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Mapping Category</label>
                        <select name="type" id="edit_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl">
                            <option value="ADP">ADP Stream</option>
                            <option value="SDG">SDG Stream</option>
                        </select>
                    </div>

                    {{-- Edit Dept --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Department</label>
                        <select name="department_id" id="edit_dept_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl">
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->sector->name }} → {{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Edit Range --}}
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="start_adp" id="edit_start" required placeholder="From" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-mono text-sm">
                        <input type="text" name="end_adp" id="edit_end" placeholder="To" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-mono text-sm">
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-gray-700 font-semibold">Cancel</button>
                    <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-bold rounded-xl shadow-lg">Update Rule</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openEditModal(mapping) {
            document.getElementById('editForm').action = `/settings/mappings/${mapping.id}`;
            document.getElementById('edit_type').value = mapping.type;
            document.getElementById('edit_dept_id').value = mapping.department_id;
            document.getElementById('edit_start').value = mapping.start_adp;
            document.getElementById('edit_end').value = mapping.end_adp;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
    </script>
@endpush