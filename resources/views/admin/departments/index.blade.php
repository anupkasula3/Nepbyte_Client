@extends('layouts.admin')

@section('title', 'Departments - NepByte Admin')
@section('page-title', 'Departments')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Departments</h1>
        <a href="{{ route('admin.departments.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add Department
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.departments.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Department name or description"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-2">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.departments.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $department)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $department->name }}</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $department->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    @if($department->description)
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($department->description, 100) }}</p>
                    @endif
                    
                    <div class="space-y-2 mb-4">
                        @if($department->head_of_department)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user-tie w-4 mr-2"></i>
                                <span>{{ $department->head_of_department }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>{{ $department->employees_count }} Employees</span>
                        </div>
                        
                        @if($department->location)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                <span>{{ $department->location }}</span>
                            </div>
                        @endif
                        
                        @if($department->budget)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-dollar-sign w-4 mr-2"></i>
                                <span>${{ number_format($department->budget, 2) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.departments.show', $department) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('admin.departments.edit', $department) }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.departments.destroy', $department) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this department?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No departments found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first department.</p>
                    <a href="{{ route('admin.departments.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>Add Department
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($departments->hasPages())
        <div class="flex justify-center">
            {{ $departments->links() }}
        </div>
    @endif
</div>
@endsection
