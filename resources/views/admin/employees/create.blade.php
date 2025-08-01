@extends('layouts.admin')

@section('title', 'Add Employee - NepByte Admin')
@section('page-title', 'Add Employee')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add New Employee</h1>
        <a href="{{ route('admin.employees.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Employees
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.employees.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                        <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror">
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Employment Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('position') border-red-500 @enderror">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department_id" id="department_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('department_id') border-red-500 @enderror">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ (old('department_id') ?? request('department')) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-1">Employment Type *</label>
                        <select name="employment_type" id="employment_type" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('employment_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="intern" {{ old('employment_type') == 'intern' ? 'selected' : '' }}>Intern</option>
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date *</label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('hire_date') border-red-500 @enderror">
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary</label>
                        <input type="number" name="salary" id="salary" value="{{ old('salary') }}" step="0.01" min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('salary') border-red-500 @enderror">
                        @error('salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                    <textarea name="skills" id="skills" rows="3" placeholder="List employee skills and competencies"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('skills') border-red-500 @enderror">{{ old('skills') }}</textarea>
                    @error('skills')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Emergency Contact -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_name') border-red-500 @enderror">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_phone') border-red-500 @enderror">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.employees.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Save Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
