@extends('layouts.admin')

@section('title', 'Add Department - NepByte Admin')
@section('page-title', 'Add Department')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add New Department</h1>
        <a href="{{ route('admin.departments.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Departments
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Department Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="head_of_department" class="block text-sm font-medium text-gray-700 mb-1">Head of Department</label>
                        <input type="text" name="head_of_department" id="head_of_department" value="{{ old('head_of_department') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('head_of_department') border-red-500 @enderror">
                        @error('head_of_department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               placeholder="e.g., Floor 3, Building A"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                        <input type="number" name="budget" id="budget" value="{{ old('budget') }}" step="0.01" min="0"
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
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
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

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.departments.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Save Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
