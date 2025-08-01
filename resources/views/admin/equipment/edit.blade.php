@extends('layouts.admin')

@section('title', 'Edit Equipment - NepByte Admin')
@section('page-title', 'Edit Equipment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Equipment: {{ $equipment->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.equipment.show', $equipment) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-eye mr-2"></i>View Equipment
            </a>
            <a href="{{ route('admin.equipment.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Equipment
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Equipment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}" required
                               placeholder="e.g., MacBook Pro 16-inch"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asset_tag" class="block text-sm font-medium text-gray-700 mb-1">Asset Tag *</label>
                        <input type="text" name="asset_tag" id="asset_tag" value="{{ old('asset_tag', $equipment->asset_tag) }}" required
                               placeholder="e.g., NB-LAP-001"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('asset_tag') border-red-500 @enderror">
                        @error('asset_tag')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-1">Manufacturer *</label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $equipment->manufacturer) }}" required
                               placeholder="e.g., Apple, Dell, HP"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('manufacturer') border-red-500 @enderror">
                        @error('manufacturer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $equipment->model) }}" required
                               placeholder="e.g., MacBook Pro 16-inch"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}"
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
                            <option value="laptop" {{ old('category', $equipment->category) == 'laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="desktop" {{ old('category', $equipment->category) == 'desktop' ? 'selected' : '' }}>Desktop</option>
                            <option value="server" {{ old('category', $equipment->category) == 'server' ? 'selected' : '' }}>Server</option>
                            <option value="network" {{ old('category', $equipment->category) == 'network' ? 'selected' : '' }}>Network</option>
                            <option value="mobile" {{ old('category', $equipment->category) == 'mobile' ? 'selected' : '' }}>Mobile</option>
                            <option value="printer" {{ old('category', $equipment->category) == 'printer' ? 'selected' : '' }}>Printer</option>
                            <option value="other" {{ old('category', $equipment->category) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="assigned" {{ old('status', $equipment->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="retired" {{ old('status', $equipment->status) == 'retired' ? 'selected' : '' }}>Retired</option>
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
                                <option value="{{ $employee->id }}" {{ old('assigned_to', $equipment->assigned_to) == $employee->id ? 'selected' : '' }}>
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
                        <input type="text" name="location" id="location" value="{{ old('location', $equipment->location) }}"
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
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $equipment->purchase_date->format('Y-m-d')) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_date') border-red-500 @enderror">
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-1">Purchase Price *</label>
                        <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}" step="0.01" min="0" required
                               placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('purchase_price') border-red-500 @enderror">
                        @error('purchase_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-1">Warranty Expiry</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" value="{{ old('warranty_expiry', $equipment->warranty_expiry?->format('Y-m-d')) }}"
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
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('specifications') border-red-500 @enderror">{{ old('specifications', $equipment->specifications) }}</textarea>
                    @error('specifications')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Additional notes and comments"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $equipment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Equipment Statistics -->
            @if($equipment->exists)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Current Equipment Statistics</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ ucfirst($equipment->status) }}</div>
                            <div class="text-sm text-gray-600">Current Status</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $equipment->purchase_date->diffInYears(now()) }}</div>
                            <div class="text-sm text-gray-600">Years Old</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">${{ number_format($equipment->purchase_price, 0) }}</div>
                            <div class="text-sm text-gray-600">Purchase Price</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-orange-600">
                                @if($equipment->warranty_expiry)
                                    @if($equipment->warranty_expiry->isPast())
                                        Expired
                                    @else
                                        {{ $equipment->warranty_expiry->diffInMonths(now()) }}mo
                                    @endif
                                @else
                                    N/A
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">Warranty</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.equipment.show', $equipment) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Update Equipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
