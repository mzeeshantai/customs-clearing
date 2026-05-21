@props(['active', 'href', 'icon' => null, 'method' => 'GET'])

@php
$classes = 'nav-link-cust ' . ($active ? 'active' : '');
@endphp

@if($method === 'POST')
    <form method="POST" action="{{ $href }}" class="w-full">
        @csrf
        <button type="submit" {{ $attributes->merge(['class' => $classes . ' w-full text-left']) }}>
            @if($icon)
                {!! $icon !!}
            @endif
            <span>{{ $slot }}</span>
        </button>
    </form>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            {!! $icon !!}
        @endif
        <span>{{ $slot }}</span>
    </a>
@endif
