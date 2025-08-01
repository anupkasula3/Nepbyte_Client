@extends('layouts.admin')

@section('title', 'Clients - NepByte Admin')
@section('page-title', 'Clients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Clients</h1>
        <a href="{{ route('admin.clients.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add Client
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.clients.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Company name, contact person, or email"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="prospect" {{ request('status') == 'prospect' ? 'selected' : '' }}>Prospect</option>
                </select>
            </div>
            
            <div>
                <label for="client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                <select name="client_type" id="client_type" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="individual" {{ request('client_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="small_business" {{ request('client_type') == 'small_business' ? 'selected' : '' }}>Small Business</option>
                    <option value="enterprise" {{ request('client_type') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    <option value="government" {{ request('client_type') == 'government' ? 'selected' : '' }}>Government</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-2">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.clients.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projects</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-building text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $client->company_name }}</div>
                                        @if($client->industry)
                                            <div class="text-sm text-gray-500">{{ $client->industry }}</div>
                                        @endif
                                        @if($client->website)
                                            <a href="{{ $client->website }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-external-link-alt mr-1"></i>Website
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $client->contact_person }}</div>
                                <div class="text-sm text-gray-500">{{ $client->email }}</div>
                                <div class="text-sm text-gray-500">{{ $client->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($client->client_type == 'enterprise') bg-purple-100 text-purple-800
                                    @elseif($client->client_type == 'government') bg-blue-100 text-blue-800
                                    @elseif($client->client_type == 'small_business') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $client->client_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($client->status == 'active') bg-green-100 text-green-800
                                    @elseif($client->status == 'inactive') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $client->projects_count }} projects
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($client->total_contract_value)
                                    ${{ number_format($client->total_contract_value, 2) }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.clients.show', $client) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.clients.destroy', $client) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this client?')">
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
                                No clients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($clients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
