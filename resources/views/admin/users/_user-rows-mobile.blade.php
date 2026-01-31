@forelse($users as $user)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden user-row block sm:hidden">
        <div class="p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-purple-900/50 dark:to-blue-900/50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate mt-0.5">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    @php
                        $role = $user->roles->first();
                        $roleName = $role ? $role->name : 'N/A';
                        $roleColors = [
                            'admin' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300',
                            'teacher' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300',
                            'student' => 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300',
                        ];
                        $colorClass = $roleColors[$roleName] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $colorClass }}">
                        {{ ucfirst($roleName) }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M j, Y') }}</span>
                </div>
                @if(!$user->isAdmin())
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition">
                        {{ __('common.Edit') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@empty
@endforelse
