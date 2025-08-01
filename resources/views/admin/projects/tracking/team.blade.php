@extends('layouts.admin')

@section('title', $project->name . ' - Team Management')
@section('page-title', 'Team Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Team Management</h1>
            <p class="text-sm text-gray-600">{{ $project->name }} • {{ $project->client->company_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects.tracking.dashboard', $project) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Team Members -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Team Members ({{ $project->activeTeamMembers->count() }})</h3>
                </div>
                <div class="p-6">
                    @forelse($project->activeTeamMembers as $teamMember)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($teamMember->employee->full_name) }}&background=6366f1&color=fff" 
                                         alt="{{ $teamMember->employee->full_name }}">
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $teamMember->employee->full_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $teamMember->employee->position }}</p>
                                        <p class="text-sm text-gray-500">{{ $teamMember->employee->department->name }}</p>
                                        <p class="text-xs text-gray-400">Joined {{ $teamMember->joined_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $teamMember->role_color }}">
                                        {{ ucfirst($teamMember->role) }}
                                    </span>
                                    <div class="flex space-x-2">
                                        <button onclick="openEditModal({{ $teamMember->id }}, '{{ $teamMember->role }}', '{{ addslashes($teamMember->responsibilities) }}')"
                                                class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.projects.tracking.team.remove', [$project, $teamMember]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to remove this team member?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if($teamMember->responsibilities)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <p class="text-sm text-gray-600"><strong>Responsibilities:</strong> {{ $teamMember->responsibilities }}</p>
                                </div>
                            @endif
                            
                            <!-- Task Summary for this team member -->
                            @php
                                $memberTasks = $project->tasks->where('assigned_to', $teamMember->employee_id);
                                $completedTasks = $memberTasks->where('status', 'completed')->count();
                                $totalTasks = $memberTasks->count();
                            @endphp
                            @if($totalTasks > 0)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tasks: {{ $completedTasks }}/{{ $totalTasks }} completed</span>
                                        <span class="text-gray-600">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%</span>
                                    </div>
                                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No team members assigned to this project yet.</p>
                            <p class="text-sm text-gray-400">Add team members using the form on the right.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Team Member Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add Team Member</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.projects.tracking.team.add', $project) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee *</label>
                            <select name="employee_id" id="employee_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror">
                                <option value="">Select Employee</option>
                                @foreach($availableEmployees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} - {{ $employee->position }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select name="role" id="role" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                                <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                                <option value="lead" {{ old('role') == 'lead' ? 'selected' : '' }}>Lead</option>
                                <option value="reviewer" {{ old('role') == 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                                <option value="observer" {{ old('role') == 'observer' ? 'selected' : '' }}>Observer</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="responsibilities" class="block text-sm font-medium text-gray-700 mb-1">Responsibilities</label>
                            <textarea name="responsibilities" id="responsibilities" rows="3"
                                      placeholder="Describe the team member's responsibilities..."
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('responsibilities') border-red-500 @enderror">{{ old('responsibilities') }}</textarea>
                            @error('responsibilities')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Add Team Member
                        </button>
                    </form>
                </div>
            </div>

            <!-- Team Statistics -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Team Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Members</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->activeTeamMembers->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Team Leads</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->activeTeamMembers->where('role', 'lead')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Reviewers</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->activeTeamMembers->where('role', 'reviewer')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Available Employees</span>
                        <span class="text-sm font-medium text-gray-900">{{ $availableEmployees->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Role Descriptions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Role Descriptions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Lead</span>
                        <p class="text-xs text-gray-600 mt-1">Responsible for team coordination and decision making</p>
                    </div>
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Reviewer</span>
                        <p class="text-xs text-gray-600 mt-1">Reviews and approves work before completion</p>
                    </div>
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Member</span>
                        <p class="text-xs text-gray-600 mt-1">Active contributor to project tasks</p>
                    </div>
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Observer</span>
                        <p class="text-xs text-gray-600 mt-1">Has read-only access to project information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Team Member Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Team Member</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="edit_role" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="member">Member</option>
                        <option value="lead">Lead</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="observer">Observer</option>
                    </select>
                </div>

                <div>
                    <label for="edit_responsibilities" class="block text-sm font-medium text-gray-700 mb-1">Responsibilities</label>
                    <textarea name="responsibilities" id="edit_responsibilities" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(teamMemberId, role, responsibilities) {
    document.getElementById('editForm').action = `{{ route('admin.projects.tracking.team.update', [$project, ':id']) }}`.replace(':id', teamMemberId);
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_responsibilities').value = responsibilities || '';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
