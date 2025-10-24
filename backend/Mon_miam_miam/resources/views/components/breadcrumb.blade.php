{{-- resources/views/components/breadcrumb.blade.php --}}
@props(['items'])

<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center space-x-2">
        @foreach($items as $index => $item)
            <li class="flex items-center">
                @if($index > 0)
                    <span class="mx-2 text-gray-400">/</span>
                @endif
                
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="text-yellow-600 hover:text-yellow-700 hover:underline">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-gray-600">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>