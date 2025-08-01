@extends('layouts.admin')

@section('title', 'Notifications - NepByte Admin')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">All Notifications</h1>
        <div class="flex space-x-3">
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-check-double mr-2"></i>Mark All Read
                </button>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-6 {{ !$notification->is_read ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <i class="{{ $notification->icon }} text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $notification->title }}</h3>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                                    
                                    @if($notification->project)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-project-diagram mr-1"></i>
                                                {{ $notification->project->name }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->is_read)
                                            <span>
                                                <i class="fas fa-check mr-1"></i>
                                                Read {{ $notification->read_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if($notification->action_url)
                                    <a href="{{ $notification->action_url }}" 
                                       onclick="markAsRead({{ $notification->id }})"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View
                                    </a>
                                @endif

                                @if(!$notification->is_read)
                                    <form action="{{ route('admin.notifications.read', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Mark Read
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.notifications.destroy', $notification) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this notification?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-bell-slash text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Notifications</h3>
                <p class="text-gray-500">You're all caught up! New notifications will appear here.</p>
            </div>
        @endif
    </div>

    <!-- Notification Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-bell text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Notifications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Read</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_read', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-exclamation-circle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Unread</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_read', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('created_at', '>=', today())->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optionally refresh the page or update the UI
            console.log('Notification marked as read');
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}
</script>
@endsection
