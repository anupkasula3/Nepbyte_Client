@extends('layouts.admin')

@section('title', $project->name . ' - Project Timeline')
@section('page-title', 'Project Timeline')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Project Timeline</h1>
            <p class="text-sm text-gray-600">{{ $project->name }} • {{ $project->client->company_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects.tracking.dashboard', $project) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Activity Timeline</h3>
            <p class="text-sm text-gray-500">All project activities and updates</p>
        </div>
        <div class="p-6">
            @if($activities->count() > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($activities as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                @if($activity->activity_type == 'task_completed') bg-green-500
                                                @elseif($activity->activity_type == 'task_created') bg-blue-500
                                                @elseif($activity->activity_type == 'team_member_added') bg-purple-500
                                                @elseif($activity->activity_type == 'team_member_removed') bg-red-500
                                                @else bg-gray-500 @endif">
                                                <i class="{{ $activity->activity_icon }} text-white text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                                <div class="mt-2 flex items-center space-x-2">
                                                    <div class="flex items-center">
                                                        <img class="h-6 w-6 rounded-full" 
                                                             src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->full_name) }}&background=6366f1&color=fff" 
                                                             alt="{{ $activity->user->full_name }}">
                                                        <span class="ml-2 text-sm text-gray-500">{{ $activity->user->full_name }}</span>
                                                    </div>
                                                    <span class="text-gray-400">•</span>
                                                    <span class="text-sm text-gray-500">{{ $activity->user->position }}</span>
                                                </div>
                                                
                                                @if($activity->metadata)
                                                    <div class="mt-2 text-xs text-gray-600 bg-gray-50 rounded p-2">
                                                        @foreach($activity->metadata as $key => $value)
                                                            <div><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $activity->created_at->toISOString() }}">
                                                    {{ $activity->created_at->format('M d, Y') }}
                                                </time>
                                                <div class="text-xs">
                                                    {{ $activity->created_at->format('g:i A') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pagination -->
                @if($activities->hasPages())
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        {{ $activities->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Activity Yet</h3>
                    <p class="text-gray-500">Project activities will appear here as team members work on tasks.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-plus-circle text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tasks Created</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $activities->where('activity_type', 'task_created')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tasks Completed</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $activities->where('activity_type', 'task_completed')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-user-plus text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Team Changes</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $activities->whereIn('activity_type', ['team_member_added', 'team_member_removed', 'team_member_updated'])->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100">
                    <i class="fas fa-chart-line text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activities->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Activities</h3>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                      {{ !request('type') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                All Activities
            </a>
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}?type=task_created" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                      {{ request('type') == 'task_created' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-plus-circle mr-1"></i>Tasks Created
            </a>
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}?type=task_completed" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                      {{ request('type') == 'task_completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-check-circle mr-1"></i>Tasks Completed
            </a>
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}?type=team_member_added" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                      {{ request('type') == 'team_member_added' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-user-plus mr-1"></i>Team Added
            </a>
            <a href="{{ route('admin.projects.tracking.timeline', $project) }}?type=project_updated" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                      {{ request('type') == 'project_updated' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                <i class="fas fa-edit mr-1"></i>Project Updates
            </a>
        </div>
    </div>
</div>
@endsection
