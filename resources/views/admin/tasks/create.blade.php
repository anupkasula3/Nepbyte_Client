@extends('layouts.admin')

@section('title', 'Add Task - NepByte Admin')
@section('page-title', 'Add Task')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add New Task</h1>
        <a href="{{ route('admin.tasks.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.tasks.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Task Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Task Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Task Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project *</label>
                        <select name="project_id" id="project_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('project_id') border-red-500 @enderror">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }} ({{ $project->client->company_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To *</label>
                        <select name="assigned_to" id="assigned_to" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('assigned_to') border-red-500 @enderror">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} - {{ $employee->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="created_by" class="block text-sm font-medium text-gray-700 mb-1">Created By *</label>
                        <select name="created_by" id="created_by" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('created_by') border-red-500 @enderror">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('created_by') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} - {{ $employee->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('created_by')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="todo" {{ old('status', 'todo') == 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
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
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date *</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label>
                        <input type="number" name="estimated_hours" id="estimated_hours" value="{{ old('estimated_hours') }}" min="1"
                               placeholder="Hours"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('estimated_hours') border-red-500 @enderror">
                        @error('estimated_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              placeholder="Describe the task requirements and deliverables"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
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
                <a href="{{ route('admin.tasks.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Save Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
