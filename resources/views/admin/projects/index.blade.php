@extends('layouts.admin')

@section('title', 'Projects - NepByte Admin')
@section('page-title', 'Projects')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <a href="{{ route('admin.projects.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add Project
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Project name or description"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="client" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <select name="client" id="client"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                            {{ $client->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-2">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.projects.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $project->start_date->format('M d') }} - {{ $project->end_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->client->company_name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->client->contact_person }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->projectManager->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->projectManager->position }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($project->status == 'completed') bg-green-100 text-green-800
                                    @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($project->status == 'planning') bg-yellow-100 text-yellow-800
                                    @elseif($project->status == 'on_hold') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $project->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($project->budget, 0) }}</div>
                                @if($project->actual_cost > 0)
                                    <div class="text-sm text-gray-500">Spent: ${{ number_format($project->actual_cost, 0) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.projects.tracking.dashboard', $project) }}"
                                       class="text-purple-600 hover:text-purple-900" title="Project Tracking">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.show', $project) }}"
                                       class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project) }}"
                                       class="text-indigo-600 hover:text-indigo-900" title="Edit Project">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete Project">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
