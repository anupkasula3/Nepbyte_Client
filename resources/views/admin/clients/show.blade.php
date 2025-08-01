@extends('layouts.admin')

@section('title', $client->company_name . ' - Client Details')
@section('page-title', 'Client Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $client->company_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.clients.edit', $client) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Client
            </a>
            <a href="{{ route('admin.clients.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Clients
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Company Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Company Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Industry</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->industry ?: 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Client Type</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($client->client_type == 'enterprise') bg-purple-100 text-purple-800
                                @elseif($client->client_type == 'government') bg-blue-100 text-blue-800
                                @elseif($client->client_type == 'small_business') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $client->client_type)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($client->status == 'active') bg-green-100 text-green-800
                                @elseif($client->status == 'inactive') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($client->status) }}
                            </span>
                        </div>
                        @if($client->website)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Website</label>
                                <a href="{{ $client->website }}" target="_blank" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                    {{ $client->website }} <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($client->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Contact Person</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->contact_person }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <a href="mailto:{{ $client->email }}" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                {{ $client->email }}
                            </a>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <a href="tel:{{ $client->phone }}" class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                {{ $client->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contract Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contract Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Contract Value</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($client->total_contract_value)
                                    ${{ number_format($client->total_contract_value, 2) }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Contract Start Date</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $client->contract_start_date ? $client->contract_start_date->format('M d, Y') : 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Contract End Date</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $client->contract_end_date ? $client->contract_end_date->format('M d, Y') : 'Not set' }}
                            </p>
                        </div>
                    </div>
                    @if($client->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Notes</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $client->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Projects -->
            @if($client->projects->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Projects</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($client->projects as $project)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $project->name }}</h4>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($project->status == 'completed') bg-green-100 text-green-800
                                            @elseif($project->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($project->status == 'planning') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">{{ $project->description }}</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-500">Project Manager:</span>
                                            <span class="text-gray-900">{{ $project->projectManager->full_name }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-500">Budget:</span>
                                            <span class="text-gray-900">${{ number_format($project->budget, 2) }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-500">Progress:</span>
                                            <span class="text-gray-900">{{ $project->progress_percentage }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Card -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">{{ $client->company_name }}</h3>
                <p class="text-sm text-gray-500">{{ $client->contact_person }}</p>
                @if($client->industry)
                    <p class="text-sm text-gray-500">{{ $client->industry }}</p>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $client->projects->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Active Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $client->projects->whereIn('status', ['planning', 'in_progress'])->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Completed Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $client->projects->where('status', 'completed')->count() }}</span>
                    </div>
                    @if($client->total_contract_value)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Contract Value</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($client->total_contract_value, 0) }}</span>
                        </div>
                    @endif
                    @if($client->contract_end_date)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Contract Expires</span>
                            <span class="text-sm font-medium text-gray-900">{{ $client->contract_end_date->format('M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.projects.create') }}?client={{ $client->id }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-plus mr-2"></i>Add Project
                    </a>
                    <a href="mailto:{{ $client->email }}"
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    <a href="tel:{{ $client->phone }}"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-phone mr-2"></i>Call Client
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
