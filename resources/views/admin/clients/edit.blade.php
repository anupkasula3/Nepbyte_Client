@extends('layouts.admin')

@section('title', 'Edit Client - NepByte Admin')
@section('page-title', 'Edit Client')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Client: {{ $client->company_name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.clients.show', $client) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-eye mr-2"></i>View Client
            </a>
            <a href="{{ route('admin.clients.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Clients
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.clients.update', $client) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $client->company_name) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('company_name') border-red-500 @enderror">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                        <input type="text" name="industry" id="industry" value="{{ old('industry', $client->industry) }}"
                               placeholder="e.g., Technology, Healthcare, Finance"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('industry') border-red-500 @enderror">
                        @error('industry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type *</label>
                        <select name="client_type" id="client_type" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_type') border-red-500 @enderror">
                            <option value="">Select Client Type</option>
                            <option value="individual" {{ old('client_type', $client->client_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="small_business" {{ old('client_type', $client->client_type) == 'small_business' ? 'selected' : '' }}>Small Business</option>
                            <option value="enterprise" {{ old('client_type', $client->client_type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            <option value="government" {{ old('client_type', $client->client_type) == 'government' ? 'selected' : '' }}>Government</option>
                        </select>
                        @error('client_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="prospect" {{ old('status', $client->status) == 'prospect' ? 'selected' : '' }}>Prospect</option>
                            <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website', $client->website) }}"
                               placeholder="https://example.com"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3"
                              placeholder="Company address"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $client->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person *</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $client->contact_person) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contract Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contract Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="total_contract_value" class="block text-sm font-medium text-gray-700 mb-1">Total Contract Value</label>
                        <input type="number" name="total_contract_value" id="total_contract_value" value="{{ old('total_contract_value', $client->total_contract_value) }}" step="0.01" min="0"
                               placeholder="0.00"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('total_contract_value') border-red-500 @enderror">
                        @error('total_contract_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_start_date" class="block text-sm font-medium text-gray-700 mb-1">Contract Start Date</label>
                        <input type="date" name="contract_start_date" id="contract_start_date" value="{{ old('contract_start_date', $client->contract_start_date?->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contract_start_date') border-red-500 @enderror">
                        @error('contract_start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_end_date" class="block text-sm font-medium text-gray-700 mb-1">Contract End Date</label>
                        <input type="date" name="contract_end_date" id="contract_end_date" value="{{ old('contract_end_date', $client->contract_end_date?->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('contract_end_date') border-red-500 @enderror">
                        @error('contract_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                              placeholder="Additional notes about the client"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $client->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.clients.show', $client) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Update Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
