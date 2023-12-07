

<nav class="bg-white fixed w-full z-20 top-0 start-0 border-b border-gray-200 h-16 z-40">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
          </svg>

          <span class="hidden sm:block self-center text-2xl font-semibold whitespace-nowrap">Quickdose</span>
      </a>

      <div class="flex justify-between items-center">
        <ul class="flex p-0 font-medium bg-white space-x-2 sm:space-x-8">
          <li>
            <a href="{{route('home')}}" class="block px-3 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 p-0">Home</a>
          </li>
          <li>
            <a href=" {{route('rules.index')}}" class="block px-3 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 p-0">Rules</a>
          </li>
          <li>
            <a href="{{route('doses.index')}}" class="block px-3 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 p-0">Doses</a>
          </li>
          <li>
            <a href="{{route('refill.index')}}" class="block px-3 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 p-0">Refill</a>
          </li>
        </ul>
      </div>
      
    </div>
  </nav>
  
  <div class="h-16"></div>