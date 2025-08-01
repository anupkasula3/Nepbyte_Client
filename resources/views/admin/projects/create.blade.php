@extends('layouts.admin')

@section('title', 'Add Project - NepByte Admin')
@section('page-title', 'Add Project')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add New Project</h1>
        <a href="{{ route('admin.projects.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Projects
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.projects.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Project Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client *</label>
                        <select name="client_id" id="client_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_id') border-red-500 @enderror">
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ (old('client_id') ?? request('client')) == $client->id ? 'selected' : '' }}>
                                    {{ $client->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_manager_id" class="block text-sm font-medium text-gray-700 mb-1">Project Manager *</label>
                        <select name="project_manager_id" id="project_manager_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_manager_id') border-red-500 @enderror">
                            <option value="">Select Project Manager</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('project_manager_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} - {{ $employee->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_manager_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="planning" {{ old('status', 'planning') == 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                        <select name="priority" id="priority" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget *</label>
                        <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" min="0" required
                               placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('budget') border-red-500 @enderror">
                        @error('budget')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Describe the project goals, scope, and deliverables"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Technical Details -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Technical Details</h3>
                
                <div class="mt-4">
                    <label for="technologies_used" class="block text-sm font-medium text-gray-700 mb-1">Technologies Used</label>
                    <textarea name="technologies_used" id="technologies_used" rows="3"
                              placeholder="List the technologies, frameworks, and tools to be used"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('technologies_used') border-red-500 @enderror">{{ old('technologies_used') }}</textarea>
                    @error('technologies_used')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4"
                              placeholder="Detailed project requirements and specifications"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('requirements') border-red-500 @enderror">{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Additional notes and comments"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.projects.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Save Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
