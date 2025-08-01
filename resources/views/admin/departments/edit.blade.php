@extends('layouts.admin')

@section('title', 'Edit Department - NepByte Admin')
@section('page-title', 'Edit Department')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Department: {{ $department->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.departments.show', $department) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-eye mr-2"></i>View Department
            </a>
            <a href="{{ route('admin.departments.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Departments
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.departments.update', $department) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Department Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="head_of_department" class="block text-sm font-medium text-gray-700 mb-1">Head of Department</label>
                        <input type="text" name="head_of_department" id="head_of_department" value="{{ old('head_of_department', $department->head_of_department) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('head_of_department') border-red-500 @enderror">
                        @error('head_of_department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $department->location) }}"
                               placeholder="e.g., Floor 3, Building A"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                        <input type="number" name="budget" id="budget" value="{{ old('budget', $department->budget) }}" step="0.01" min="0"
                               placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('budget') border-red-500 @enderror">
                        @error('budget')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Describe the department's role and responsibilities"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active Department
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Inactive departments will not be available for new employee assignments.</p>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Statistics -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-900 mb-3">Current Department Statistics</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $department->employees->count() }}</div>
                        <div class="text-sm text-gray-600">Total Employees</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $department->employees->where('status', 'active')->count() }}</div>
                        <div class="text-sm text-gray-600">Active</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-purple-600">{{ $department->employees->where('employment_type', 'full-time')->count() }}</div>
                        <div class="text-sm text-gray-600">Full-time</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-orange-600">
                            @if($department->budget)
                                ${{ number_format($department->budget / 1000, 0) }}K
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Budget</div>
                    </div>
                </div>
            </div>

            <!-- Warning for employees -->
            @if($department->employees->count() > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Department has {{ $department->employees->count() }} employee(s)
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Changing the department status to inactive will prevent new employee assignments, 
                                    but existing employees will remain in this department.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.departments.show', $department) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Update Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
