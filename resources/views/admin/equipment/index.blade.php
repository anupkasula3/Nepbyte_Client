@extends('layouts.admin')

@section('title', 'Equipment - NepByte Admin')
@section('page-title', 'Equipment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Equipment</h1>
        <a href="{{ route('admin.equipment.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add Equipment
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.equipment.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Name, asset tag, serial number, or model"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="category" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <option value="laptop" {{ request('category') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="desktop" {{ request('category') == 'desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="server" {{ request('category') == 'server' ? 'selected' : '' }}>Server</option>
                    <option value="network" {{ request('category') == 'network' ? 'selected' : '' }}>Network</option>
                    <option value="mobile" {{ request('category') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="printer" {{ request('category') == 'printer' ? 'selected' : '' }}>Printer</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-2">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.equipment.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Equipment Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($equipment as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            @if($item->category == 'laptop')
                                                <i class="fas fa-laptop text-gray-600"></i>
                                            @elseif($item->category == 'desktop')
                                                <i class="fas fa-desktop text-gray-600"></i>
                                            @elseif($item->category == 'server')
                                                <i class="fas fa-server text-gray-600"></i>
                                            @elseif($item->category == 'mobile')
                                                <i class="fas fa-mobile-alt text-gray-600"></i>
                                            @elseif($item->category == 'printer')
                                                <i class="fas fa-print text-gray-600"></i>
                                            @else
                                                <i class="fas fa-microchip text-gray-600"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->manufacturer }} {{ $item->model }}</div>
                                        <div class="text-xs text-gray-400">Asset: {{ $item->asset_tag }}</div>
                                        @if($item->serial_number)
                                            <div class="text-xs text-gray-400">S/N: {{ $item->serial_number }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($item->category == 'laptop') bg-blue-100 text-blue-800
                                    @elseif($item->category == 'desktop') bg-green-100 text-green-800
                                    @elseif($item->category == 'server') bg-purple-100 text-purple-800
                                    @elseif($item->category == 'mobile') bg-pink-100 text-pink-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($item->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($item->status == 'available') bg-green-100 text-green-800
                                    @elseif($item->status == 'assigned') bg-blue-100 text-blue-800
                                    @elseif($item->status == 'maintenance') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->assignedEmployee)
                                    <div class="flex items-center">
                                        <img class="h-6 w-6 rounded-full" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($item->assignedEmployee->full_name) }}&background=6366f1&color=fff" 
                                             alt="{{ $item->assignedEmployee->full_name }}">
                                        <div class="ml-2">
                                            <div class="text-sm text-gray-900">{{ $item->assignedEmployee->full_name }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($item->purchase_price, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ $item->purchase_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->warranty_expiry)
                                    <div class="text-sm text-gray-900">{{ $item->warranty_expiry->format('M d, Y') }}</div>
                                    @if($item->warranty_expiry->isPast())
                                        <div class="text-xs text-red-600">Expired</div>
                                    @elseif($item->warranty_expiry->diffInDays() < 30)
                                        <div class="text-xs text-orange-600">Expires Soon</div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">No warranty</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.equipment.show', $item) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.equipment.edit', $item) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.equipment.destroy', $item) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this equipment?')">
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
                                No equipment found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($equipment->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $equipment->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
