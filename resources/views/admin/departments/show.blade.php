@extends('layouts.admin')

@section('title', $department->name . ' - Department Details')
@section('page-title', 'Department Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $department->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.departments.edit', $department) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Department
            </a>
            <a href="{{ route('admin.departments.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Departments
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Department Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Department Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Department Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $department->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $department->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $department->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if($department->head_of_department)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Head of Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $department->head_of_department }}</p>
                            </div>
                        @endif
                        @if($department->location)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $department->location }}</p>
                            </div>
                        @endif
                        @if($department->budget)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Budget</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($department->budget, 2) }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Employee Count</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $department->employees->count() }} employees</p>
                        </div>
                    </div>
                    @if($department->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $department->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Department Employees -->
            @if($department->employees->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Department Employees</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employment Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($department->employees as $employee)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full" 
                                                         src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=6366f1&color=fff" 
                                                         alt="{{ $employee->full_name }}">
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $employee->position }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($employee->status == 'active') bg-green-100 text-green-800
                                                    @elseif($employee->status == 'inactive') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($employee->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.employees.show', $employee) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Department Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Department Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Employees</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Active Employees</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Full-time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->where('employment_type', 'full-time')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Part-time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->where('employment_type', 'part-time')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Contractors</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->where('employment_type', 'contract')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Interns</span>
                        <span class="text-sm font-medium text-gray-900">{{ $department->employees->where('employment_type', 'intern')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.employees.create') }}?department={{ $department->id }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-user-plus mr-2"></i>Add Employee
                    </a>
                    <a href="{{ route('admin.departments.edit', $department) }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Department
                    </a>
                </div>
            </div>

            <!-- Department Info Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $department->name }}</h3>
                    @if($department->head_of_department)
                        <p class="text-sm text-gray-500">Led by {{ $department->head_of_department }}</p>
                    @endif
                    @if($department->location)
                        <p class="text-sm text-gray-500 mt-1">{{ $department->location }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
