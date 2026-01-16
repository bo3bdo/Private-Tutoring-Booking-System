@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-dark-blue-500 dark:focus:border-dark-blue-400 focus:ring-dark-blue-500 dark:focus:ring-dark-blue-400 rounded-button shadow-sm']) }}>
