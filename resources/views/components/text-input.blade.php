@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 focus:border-dark-blue-500 dark:focus:border-dark-blue-400 focus:ring-2 focus:ring-dark-blue-500/20 dark:focus:ring-dark-blue-400/20']) }}>
