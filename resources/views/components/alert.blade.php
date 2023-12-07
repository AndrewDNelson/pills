

@if ($isVisible)
<div x-data="{
    bannerVisible: false,
    bannerVisibleAfter: 300,
    }"
    x-show="bannerVisible"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="-translate-y-10"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-10"
    x-init="
    setTimeout(()=>{ bannerVisible = true }, bannerVisibleAfter);
    "
    class="fixed top-16 left-0 w-full h-auto py-2 duration-300 ease-out bg-white shadow-sm sm:py-0 sm:h-10 z-30" x-cloak>
    <div class="flex items-center justify-between w-full h-full px-3 mx-auto max-w-7xl ">
        <a href="{{route($route)}}" class="flex flex-col w-full h-full text-xs leading-6 text-black duration-150 ease-out sm:flex-row sm:items-center opacity-80 hover:opacity-100">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" ><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>

                <strong class="font-semibold">Alert</strong><span class="hidden w-px h-4 mx-3 rounded-full sm:block bg-neutral-200"></span>
            </span>
            <span class="block pt-1 pb-2 leading-none sm:inline sm:pt-0 sm:pb-0">{{ $message }}</span>
        </a>
        <button @click="bannerVisible=false;" class="flex items-center flex-shrink-0 translate-x-1 ease-out duration-150 justify-center w-6 h-6 p-1.5 text-black rounded-full hover:bg-neutral-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
</div>
@endif