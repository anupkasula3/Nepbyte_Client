@extends('layouts.admin')

@section('title', $equipment->name . ' - Equipment Details')
@section('page-title', 'Equipment Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $equipment->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.equipment.edit', $equipment) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Equipment
            </a>
            <a href="{{ route('admin.equipment.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Equipment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Equipment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Equipment Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Equipment Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Asset Tag</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->asset_tag }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Manufacturer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->manufacturer }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Model</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->model }}</p>
                        </div>
                        @if($equipment->serial_number)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Serial Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->serial_number }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Category</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($equipment->category == 'laptop') bg-blue-100 text-blue-800
                                @elseif($equipment->category == 'desktop') bg-green-100 text-green-800
                                @elseif($equipment->category == 'server') bg-purple-100 text-purple-800
                                @elseif($equipment->category == 'mobile') bg-pink-100 text-pink-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($equipment->category) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($equipment->status == 'available') bg-green-100 text-green-800
                                @elseif($equipment->status == 'assigned') bg-blue-100 text-blue-800
                                @elseif($equipment->status == 'maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>
                        @if($equipment->location)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $equipment->location }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            @if($equipment->assignedEmployee)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Assignment Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($equipment->assignedEmployee->full_name) }}&background=6366f1&color=fff" 
                                 alt="{{ $equipment->assignedEmployee->full_name }}">
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">{{ $equipment->assignedEmployee->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $equipment->assignedEmployee->position }}</div>
                                <div class="text-sm text-gray-500">{{ $equipment->assignedEmployee->department->name }}</div>
                                <div class="text-sm text-gray-500">{{ $equipment->assignedEmployee->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Purchase & Warranty Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Purchase & Warranty Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Purchase Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->purchase_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Purchase Price</label>
                            <p class="mt-1 text-sm text-gray-900">${{ number_format($equipment->purchase_price, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Age</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $equipment->purchase_date->diffForHumans() }}</p>
                        </div>
                        @if($equipment->warranty_expiry)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Warranty Status</label>
                                @if($equipment->warranty_expiry->isPast())
                                    <p class="mt-1 text-sm text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Expired {{ $equipment->warranty_expiry->diffForHumans() }}
                                    </p>
                                @elseif($equipment->warranty_expiry->diffInDays() < 30)
                                    <p class="mt-1 text-sm text-orange-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Expires {{ $equipment->warranty_expiry->diffForHumans() }}
                                    </p>
                                @else
                                    <p class="mt-1 text-sm text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Valid until {{ $equipment->warranty_expiry->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Warranty Status</label>
                                <p class="mt-1 text-sm text-gray-400">No warranty information</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Technical Specifications -->
            @if($equipment->specifications)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Technical Specifications</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $equipment->specifications }}</p>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($equipment->notes)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $equipment->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Equipment Card -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    @if($equipment->category == 'laptop')
                        <i class="fas fa-laptop text-gray-600 text-2xl"></i>
                    @elseif($equipment->category == 'desktop')
                        <i class="fas fa-desktop text-gray-600 text-2xl"></i>
                    @elseif($equipment->category == 'server')
                        <i class="fas fa-server text-gray-600 text-2xl"></i>
                    @elseif($equipment->category == 'mobile')
                        <i class="fas fa-mobile-alt text-gray-600 text-2xl"></i>
                    @elseif($equipment->category == 'printer')
                        <i class="fas fa-print text-gray-600 text-2xl"></i>
                    @else
                        <i class="fas fa-microchip text-gray-600 text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-lg font-medium text-gray-900">{{ $equipment->name }}</h3>
                <p class="text-sm text-gray-500">{{ $equipment->manufacturer }} {{ $equipment->model }}</p>
                <p class="text-sm text-gray-500">{{ $equipment->asset_tag }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($equipment->status) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Category</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($equipment->category) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Age</span>
                        <span class="text-sm font-medium text-gray-900">{{ $equipment->purchase_date->diffInYears(now()) }} years</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Value</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($equipment->purchase_price, 0) }}</span>
                    </div>
                    @if($equipment->warranty_expiry)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Warranty</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($equipment->warranty_expiry->isPast())
                                    <span class="text-red-600">Expired</span>
                                @else
                                    <span class="text-green-600">Valid</span>
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.equipment.edit', $equipment) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Equipment
                    </a>
                    @if($equipment->assignedEmployee)
                        <a href="{{ route('admin.employees.show', $equipment->assignedEmployee) }}"
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                            <i class="fas fa-user mr-2"></i>View Assignee
                        </a>
                    @endif
                    @if($equipment->status == 'assigned')
                        <button onclick="if(confirm('Are you sure you want to unassign this equipment?')) { document.getElementById('unassign-form').submit(); }"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                            <i class="fas fa-user-times mr-2"></i>Unassign
                        </button>
                        <form id="unassign-form" action="{{ route('admin.equipment.update', $equipment) }}" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="assigned_to" value="">
                            <input type="hidden" name="status" value="available">
                            <input type="hidden" name="name" value="{{ $equipment->name }}">
                            <input type="hidden" name="asset_tag" value="{{ $equipment->asset_tag }}">
                            <input type="hidden" name="manufacturer" value="{{ $equipment->manufacturer }}">
                            <input type="hidden" name="model" value="{{ $equipment->model }}">
                            <input type="hidden" name="category" value="{{ $equipment->category }}">
                            <input type="hidden" name="purchase_date" value="{{ $equipment->purchase_date->format('Y-m-d') }}">
                            <input type="hidden" name="purchase_price" value="{{ $equipment->purchase_price }}">
                        </form>
                    @endif
                </div>
            </div>

            <!-- Equipment Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-gray-900">Equipment Purchased</p>
                            <p class="text-gray-500">{{ $equipment->purchase_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($equipment->assignedEmployee)
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-900">Assigned to {{ $equipment->assignedEmployee->full_name }}</p>
                                <p class="text-gray-500">Current assignment</p>
                            </div>
                        </div>
                    @endif
                    @if($equipment->warranty_expiry)
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 {{ $equipment->warranty_expiry->isPast() ? 'bg-red-600' : 'bg-yellow-600' }} rounded-full mr-3"></div>
                            <div>
                                <p class="text-gray-900">Warranty {{ $equipment->warranty_expiry->isPast() ? 'Expired' : 'Expires' }}</p>
                                <p class="text-gray-500">{{ $equipment->warranty_expiry->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
