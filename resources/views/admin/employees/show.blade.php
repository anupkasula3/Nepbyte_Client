@extends('layouts.admin')

@section('title', $employee->full_name . ' - Employee Details')
@section('page-title', 'Employee Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $employee->full_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.employees.edit', $employee) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Employee
            </a>
            <a href="{{ route('admin.employees.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Employees
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Employee Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Employee ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->employee_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->phone ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Birth Date</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $employee->birth_date ? $employee->birth_date->format('M d, Y') : 'Not provided' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($employee->status == 'active') bg-green-100 text-green-800
                                @elseif($employee->status == 'inactive') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                    </div>
                    @if($employee->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Employment Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Position</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->position }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Department</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->department->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Employment Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Hire Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->hire_date->format('M d, Y') }}</p>
                        </div>
                        @if($employee->salary)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Salary</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($employee->salary, 2) }}</p>
                            </div>
                        @endif
                    </div>
                    @if($employee->skills)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500">Skills</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $employee->skills }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($employee->emergency_contact_name || $employee->emergency_contact_phone)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Emergency Contact</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($employee->emergency_contact_name)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Contact Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->emergency_contact_name }}</p>
                                </div>
                            @endif
                            @if($employee->emergency_contact_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Contact Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->emergency_contact_phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <img class="h-32 w-32 rounded-full mx-auto" 
                     src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=6366f1&color=fff&size=128" 
                     alt="{{ $employee->full_name }}">
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $employee->full_name }}</h3>
                <p class="text-sm text-gray-500">{{ $employee->position }}</p>
                <p class="text-sm text-gray-500">{{ $employee->department->name }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Managed Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $employee->managedProjects->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Assigned Tasks</span>
                        <span class="text-sm font-medium text-gray-900">{{ $employee->assignedTasks->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Equipment Assigned</span>
                        <span class="text-sm font-medium text-gray-900">{{ $employee->equipment->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Years with Company</span>
                        <span class="text-sm font-medium text-gray-900">{{ $employee->hire_date->diffInYears(now()) }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($employee->assignedTasks->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Tasks</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($employee->assignedTasks->take(5) as $task)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $task->project->name }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($task->status == 'completed') bg-green-100 text-green-800
                                        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($task->status == 'review') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
