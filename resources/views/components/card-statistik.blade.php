{{-- Card Statistik Component --}}
@props(['title', 'value', 'icon', 'color' => 'blue', 'borderColor' => 'blue-500'])

<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-{{ $borderColor }}">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
        <div class="bg-{{ $color }}-100 rounded-lg p-3">
            {!! $icon !!}
        </div>
    </div>
</div>

