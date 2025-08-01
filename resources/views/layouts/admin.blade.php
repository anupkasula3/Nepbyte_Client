<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NepByte Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

   {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflareF.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-gray-800">
                <h1 class="text-white text-xl font-bold">NepByte Admin</h1>
            </div>

            <!-- Navigation -->
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>

                    <!-- Employees -->
                    <a href="{{ route('admin.employees.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.employees.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Employees
                    </a>

                    <!-- Departments -->
                    <a href="{{ route('admin.departments.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.departments.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-building mr-3"></i>
                        Departments
                    </a>

                    <!-- Clients -->
                    <a href="{{ route('admin.clients.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.clients.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-handshake mr-3"></i>
                        Clients
                    </a>

                    <!-- Projects -->
                    <a href="{{ route('admin.projects.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-project-diagram mr-3"></i>
                        Projects
                    </a>

                    <!-- Tasks -->
                    <a href="{{ route('admin.tasks.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.tasks.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-tasks mr-3"></i>
                        Tasks
                    </a>

                    <!-- Equipment -->
                    <a href="{{ route('admin.equipment.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.equipment.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-laptop mr-3"></i>
                        Equipment
                    </a>

                    <!-- Accounting Section -->
                    <div class="mt-6">
                        <div class="px-4 py-2">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Accounting</h3>
                        </div>

                        <!-- Accounting Dashboard -->
                        <a href="{{ route('admin.accounting.index') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.accounting.index') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-chart-pie mr-3"></i>
                            Dashboard
                        </a>

                        <!-- Invoices -->
                        <a href="{{ route('admin.invoices.index') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.invoices.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-file-invoice mr-3"></i>
                            Invoices
                        </a>

                        <!-- Expenses -->
                        <a href="{{ route('admin.expenses.index') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.expenses.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-receipt mr-3"></i>
                            Expenses
                        </a>

                        <!-- Payments -->
                        <a href="{{ route('admin.payments.index') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-credit-card mr-3"></i>
                            Payments
                        </a>

                        <!-- Budgets -->
                        <a href="{{ route('admin.budgets.index') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.budgets.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-calculator mr-3"></i>
                            Budgets
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('admin.accounting.reports') }}"
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200 {{ request()->routeIs('admin.accounting.reports') || request()->routeIs('admin.accounting.profit-loss') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Reports
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="ml-4 text-xl font-semibold text-gray-800 lg:ml-0">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false, unreadCount: 0, notifications: [] }" x-init="loadNotifications()">
                            <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-bell text-lg"></i>
                                <span x-show="unreadCount > 0" x-text="unreadCount"
                                      class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">

                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                                        <button @click="markAllAsRead()" x-show="unreadCount > 0"
                                                class="text-sm text-blue-600 hover:text-blue-800">
                                            Mark all read
                                        </button>
                                    </div>
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer"
                                             :class="{ 'bg-blue-50': !notification.is_read }"
                                             @click="handleNotificationClick(notification)">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <i :class="getNotificationIcon(notification.type)" class="text-sm"></i>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                    <p class="text-sm text-gray-600" x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                                <div class="flex-shrink-0 ml-2">
                                                    <div x-show="!notification.is_read" class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="notifications.length === 0" class="p-4 text-center text-gray-500">
                                        No notifications
                                    </div>
                                </div>

                                <div class="p-4 border-t border-gray-200">
                                    <a href="{{ route('admin.notifications.index') }}"
                                       class="block text-center text-sm text-blue-600 hover:text-blue-800">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin">
                                <span class="ml-2 text-sm font-medium">Admin</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <hr class="my-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>

    <script>
        // Notification functions
        function loadNotifications() {
            fetch('{{ route("admin.notifications.unread") }}')
                .then(response => response.json())
                .then(data => {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function markAsRead(notification) {
            fetch(`/admin/notifications/${notification.id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notification.is_read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllAsRead() {
            fetch('{{ route("admin.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.notifications.forEach(notification => {
                        notification.is_read = true;
                    });
                    this.unreadCount = 0;
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }

        function handleNotificationClick(notification) {
            // Mark as read if not already read
            if (!notification.is_read) {
                this.markAsRead(notification);
            }

            // Navigate to action URL if provided
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        }

        function getNotificationIcon(type) {
            const icons = {
                'task_assigned': 'fas fa-tasks text-blue-600',
                'task_completed': 'fas fa-check-circle text-green-600',
                'task_updated': 'fas fa-edit text-yellow-600',
                'project_updated': 'fas fa-project-diagram text-purple-600',
                'team_member_added': 'fas fa-user-plus text-green-600',
                'team_member_removed': 'fas fa-user-minus text-red-600',
                'deadline_approaching': 'fas fa-clock text-orange-600',
                'project_completed': 'fas fa-flag-checkered text-green-600',
            };
            return icons[type] || 'fas fa-bell text-gray-600';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));

            if (diffInMinutes < 1) return 'Just now';
            if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
            if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`;
            if (diffInMinutes < 10080) return `${Math.floor(diffInMinutes / 1440)}d ago`;

            return date.toLocaleDateString();
        }

        // Auto-refresh notifications every 30 seconds
        setInterval(() => {
            if (typeof loadNotifications === 'function') {
                loadNotifications();
            }
        }, 30000);
    </script>
</body>
</html>
