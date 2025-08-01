@extends('layouts.admin')

@section('title', 'Tasks - NepByte Admin')
@section('page-title', 'Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
        <a href="{{ route('admin.tasks.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add Task
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.tasks.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Task title or description"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label for="project" class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                <select name="project" id="project" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                <select name="assigned_to" id="assigned_to" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('assigned_to') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-2">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.tasks.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($task->description, 60) }}</div>
                                    @if($task->estimated_hours)
                                        <div class="text-xs text-gray-400">Est: {{ $task->estimated_hours }}h</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                <div class="text-sm text-gray-500">{{ $task->project->client->company_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($task->assignedEmployee->full_name) }}&background=6366f1&color=fff" 
                                         alt="{{ $task->assignedEmployee->full_name }}">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->assignedEmployee->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $task->assignedEmployee->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($task->status == 'completed') bg-green-100 text-green-800
                                    @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($task->status == 'review') bg-yellow-100 text-yellow-800
                                    @elseif($task->status == 'todo') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($task->priority == 'critical') bg-red-100 text-red-800
                                    @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $task->due_date->format('M d, Y') }}</div>
                                @if($task->due_date->isPast() && $task->status != 'completed')
                                    <div class="text-xs text-red-600">Overdue</div>
                                @elseif($task->due_date->isToday())
                                    <div class="text-xs text-orange-600">Due Today</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.tasks.show', $task) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tasks.edit', $task) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No tasks found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
