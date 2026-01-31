@forelse($users as $user)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition user-row hidden sm:table-row">
        <td class="px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-purple-900/50 dark:to-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
            </div>
        </td>
        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $user->email }}</td>
        <td class="px-4 sm:px-6 py-3 sm:py-4">
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
            <span class="inline-flex items-center rounded-full px-2 sm:px-2.5 py-0.5 text-xs font-semibold {{ $colorClass }}">
                {{ ucfirst($roleName) }}
            </span>
        </td>
        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm text-gray-600 dark:text-gray-400 hidden md:table-cell">
            {{ $user->created_at->format('M j, Y') }}
        </td>
        <td class="px-4 sm:px-6 py-3 sm:py-4 text-right text-sm font-medium">
            @if(!$user->isAdmin())
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition">
                    {{ __('common.Edit') }}
                </a>
            @else
                <span class="text-gray-400 dark:text-gray-600 text-xs">{{ __('common.Admin') }}</span>
            @endif
        </td>
    </tr>
@empty
@endforelse
