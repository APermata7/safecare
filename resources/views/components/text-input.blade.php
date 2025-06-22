@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50 rounded-xl shadow-sm']) }}>
