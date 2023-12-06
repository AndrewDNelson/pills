@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' =>  'flex justify-between']) }}>
    <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
        @foreach ($items as $item)

            <li class="flex items-center h-full">
                <a href="{{ route($item['route']) }}" class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    @isset($item['icon'])
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="{{$item['icon']}}" fill="currentColor"></path></svg>
                    @endisset
                    <span>{{$item['text']}}</span>
                </a>
            </li>  

            @if (!$loop->last)
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="none"><path d="M10 8.013l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
            @endif

        @endforeach
    </ol>
</nav>    