@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Department Mapping Engine</h2>
                <p class="text-sm text-gray-500">Define ADP ranges to automatically assign Departments.</p>
            </div>

            <form action="{{ route('mappings.sync') }}" method="POST">
                @csrf
                <button
                    class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 shadow-md flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Sync All Rules
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- FORM COLUMN -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
                <h3 class="font-bold text-gray-800 mb-6 border-b pb-2">Create Assignment Rule</h3>
                <form action="{{ route('mappings.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase">Target Department</label>
                            <select name="department_id"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->sector->name }} → {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase">From ADP #</label>
                                <input type="text" name="start_adp" placeholder="A0001"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm">
                            </div>
                            <div>
        <label class="text-xs font-bold text-gray-400 uppercase">To ADP # (Optional)</label>
        <input type="text" name="end_adp" placeholder="Leave empty for single" class="w-full mt-1 border-gray-300 rounded-lg">
    </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-lg">
                            Apply & Save Rule
                        </button>
                    </div>
                </form>
            </div>

            <!-- LIST COLUMN -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 font-bold">Sector / Department</th>
                            <th class="px-6 py-4 font-bold">Assigned ADP Range</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($mappings as $mapping)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-[10px] text-blue-600 font-bold uppercase">
                                        {{ $mapping->department->sector->name }}</div>
                                    <div class="font-medium text-gray-900">{{ $mapping->department->name }}</div>
                                </td>
                              <td class="px-6 py-4">
    @if($mapping->start_adp === $mapping->end_adp)
        <!-- Single Project Display -->
        <span class="text-xs font-bold text-gray-400 uppercase mr-2">Single:</span>
        <span class="font-mono bg-green-50 text-green-700 px-2 py-1 rounded font-bold">{{ $mapping->start_adp }}</span>
    @else
        <!-- Range Display -->
        <span class="font-mono bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold">{{ $mapping->start_adp }}</span>
        <span class="mx-2 text-gray-300">→</span>
        <span class="font-mono bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold">{{ $mapping->end_adp }}</span>
    @endif
</td>
                                <td class="px-6 py-4 flex space-x-3">
                                    <!-- Edit Button (Triggers JS Modal) -->
                                    <button onclick="openEditModal({{ $mapping->toJson() }})"
                                        class="text-blue-600 hover:text-blue-900 font-bold">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('mappings.destroy', $mapping->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure? Projects in this range will become unmapped.');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-12 text-center text-gray-400">No mapping rules defined
                                    yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <form id="editForm" method="POST">
            @csrf @method('PATCH')
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-800">Edit Mapping Rule</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Department</label>
                    <select name="department_id" id="edit_dept_id" class="w-full mt-1 border-gray-300 rounded-lg">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">From ADP #</label>
                        <input type="text" name="start_adp" id="edit_start" class="w-full mt-1 border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase">To ADP #</label>
                        <input type="text" name="end_adp" id="edit_end" class="w-full mt-1 border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="text-gray-600 font-medium">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Update Rule</button>
            </div>
        </form>
    </div>
</div>



@endsection
@push('scripts')
<script>
    function openEditModal(mapping) {
        // Set the form action URL
        const form = document.getElementById('editForm');
        form.action = `/settings/mappings/${mapping.id}`;

        // Fill the fields
        document.getElementById('edit_dept_id').value = mapping.department_id;
        document.getElementById('edit_start').value = mapping.start_adp;
        document.getElementById('edit_end').value = mapping.end_adp;

        // Show modal
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush