@extends('layouts.admin')

@section('title', 'Add Equipment - NepByte Admin')
@section('page-title', 'Add Equipment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add New Equipment</h1>
        <a href="{{ route('admin.equipment.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Equipment
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.equipment.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Equipment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="e.g., MacBook Pro 16-inch"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asset_tag" class="block text-sm font-medium text-gray-700 mb-1">Asset Tag *</label>
                        <input type="text" name="asset_tag" id="asset_tag" value="{{ old('asset_tag') }}" required
                               placeholder="e.g., NB-LAP-001"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('asset_tag') border-red-500 @enderror">
                        @error('asset_tag')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-1">Manufacturer *</label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer') }}" required
                               placeholder="e.g., Apple, Dell, HP"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('manufacturer') border-red-500 @enderror">
                        @error('manufacturer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" required
                               placeholder="e.g., MacBook Pro 16-inch"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                               placeholder="Device serial number"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('serial_number') border-red-500 @enderror">
                        @error('serial_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category" id="category" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="laptop" {{ old('category') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="desktop" {{ old('category') == 'desktop' ? 'selected' : '' }}>Desktop</option>
                            <option value="server" {{ old('category') == 'server' ? 'selected' : '' }}>Server</option>
                            <option value="network" {{ old('category') == 'network' ? 'selected' : '' }}>Network</option>
                            <option value="mobile" {{ old('category') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            <option value="printer" {{ old('category') == 'printer' ? 'selected' : '' }}>Printer</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <select name="assigned_to" id="assigned_to"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('assigned_to') border-red-500 @enderror">
                            <option value="">Unassigned</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} - {{ $employee->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
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
                </div>
            </div>

            <!-- Purchase Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-1">Purchase Date *</label>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_date') border-red-500 @enderror">
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-1">Purchase Price *</label>
                        <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" step="0.01" min="0" required
                               placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_price') border-red-500 @enderror">
                        @error('purchase_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-1">Warranty Expiry</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" value="{{ old('warranty_expiry') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('warranty_expiry') border-red-500 @enderror">
                        @error('warranty_expiry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                
                <div class="mt-4">
                    <label for="specifications" class="block text-sm font-medium text-gray-700 mb-1">Specifications</label>
                    <textarea name="specifications" id="specifications" rows="3"
                              placeholder="Technical specifications and features"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('specifications') border-red-500 @enderror">{{ old('specifications') }}</textarea>
                    @error('specifications')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Additional notes and comments"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.equipment.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Save Equipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
