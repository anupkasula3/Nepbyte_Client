@extends('layouts.admin')

@section('title', $task->title . ' - Task Details')
@section('page-title', 'Task Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.tasks.edit', $task) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Task
            </a>
            <a href="{{ route('admin.tasks.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Task Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Task Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Assigned To</label>
                            <div class="mt-1 flex items-center">
                                <img class="h-6 w-6 rounded-full" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($task->assignedEmployee->full_name) }}&background=6366f1&color=fff" 
                                     alt="{{ $task->assignedEmployee->full_name }}">
                                <span class="ml-2 text-sm text-gray-900">{{ $task->assignedEmployee->full_name }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->creator->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($task->status == 'completed') bg-green-100 text-green-800
                                @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($task->status == 'review') bg-yellow-100 text-yellow-800
                                @elseif($task->status == 'todo') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Priority</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($task->priority == 'critical') bg-red-100 text-red-800
                                @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $task->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline & Progress -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Timeline & Progress</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Due Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->due_date->format('M d, Y') }}</p>
                            @if($task->due_date->isPast() && $task->status != 'completed')
                                <p class="text-xs text-red-600">Overdue by {{ $task->due_date->diffForHumans() }}</p>
                            @elseif($task->due_date->isToday())
                                <p class="text-xs text-orange-600">Due today</p>
                            @else
                                <p class="text-xs text-gray-500">{{ $task->due_date->diffForHumans() }}</p>
                            @endif
                        </div>
                        @if($task->completed_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Completed Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $task->completed_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($task->estimated_hours)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estimated Hours</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $task->estimated_hours }} hours</p>
                            </div>
                        @endif
                        @if($task->actual_hours)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Actual Hours</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $task->actual_hours }} hours</p>
                                @if($task->estimated_hours)
                                    @php
                                        $variance = (($task->actual_hours - $task->estimated_hours) / $task->estimated_hours) * 100;
                                    @endphp
                                    <p class="text-xs {{ $variance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $variance > 0 ? '+' : '' }}{{ number_format($variance, 1) }}% vs estimate
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Project Context -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Project Context</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Client</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->project->client->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project Manager</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $task->project->projectManager->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Project Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($task->project->status == 'completed') bg-green-100 text-green-800
                                @elseif($task->project->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($task->project->status == 'planning') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->project->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500">Project Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ Str::limit($task->project->description, 200) }}</p>
                    </div>
                </div>
            </div>

            @if($task->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $task->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Card -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tasks text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">{{ $task->title }}</h3>
                <p class="text-sm text-gray-500">{{ $task->project->name }}</p>
                <p class="text-sm text-gray-500">{{ $task->assignedEmployee->full_name }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Priority</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($task->priority) }}</span>
                    </div>
                    @if($task->estimated_hours)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Estimated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $task->estimated_hours }}h</span>
                        </div>
                    @endif
                    @if($task->actual_hours)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Actual</span>
                            <span class="text-sm font-medium text-gray-900">{{ $task->actual_hours }}h</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Days Until Due</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($task->due_date->isPast() && $task->status != 'completed')
                                <span class="text-red-600">Overdue</span>
                            @elseif($task->due_date->isToday())
                                <span class="text-orange-600">Due Today</span>
                            @else
                                {{ now()->diffInDays($task->due_date) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.tasks.edit', $task) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Task
                    </a>
                    <a href="{{ route('admin.projects.show', $task->project) }}"
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-project-diagram mr-2"></i>View Project
                    </a>
                    <a href="{{ route('admin.employees.show', $task->assignedEmployee) }}"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-user mr-2"></i>View Assignee
                    </a>
                </div>
            </div>

            <!-- Task Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-gray-900">Task Created</p>
                            <p class="text-gray-500">{{ $task->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($task->status == 'completed' && $task->completed_date)
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-900">Task Completed</p>
                                <p class="text-gray-500">{{ $task->completed_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 {{ $task->due_date->isPast() && $task->status != 'completed' ? 'bg-red-600' : 'bg-gray-400' }} rounded-full mr-3"></div>
                        <div>
                            <p class="text-gray-900">Due Date</p>
                            <p class="text-gray-500">{{ $task->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
