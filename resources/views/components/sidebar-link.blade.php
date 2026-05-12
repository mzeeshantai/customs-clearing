@props(['active', 'href', 'icon' => null, 'method' => 'GET'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-600/30 transition-all duration-300 transform'
            : 'flex items-center px-4 py-2.5 text-sm font-bold text-slate-400 hover:bg-slate-800/60 hover:text-indigo-400 rounded-xl transition-all duration-200 transform hover:translate-x-1.5';
@endphp

@if($method === 'POST')
    <form method="POST" action="{{ $href }}" class="w-full">
        @csrf
        <button type="submit" {{ $attributes->merge(['class' => $classes . ' w-full text-left focus:outline-none group']) }}>
            @if($icon)
                <span class="mr-3 flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
                    {!! $icon !!}
                </span>
            @endif
            <span>{{ $slot }}</span>
        </button>
    </form>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' group']) }}>
        @if($icon)
            <span class="mr-3 flex-shrink-0 transition-all duration-200 group-hover:scale-110 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}">
                {!! $icon !!}
            </span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@endif
