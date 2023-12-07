<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Refill') }}
        </h2>

        <x-breadcrumbs class="mt-2" :items="[
            ['route' => 'home', 'text' => 'Dashboard', 'icon' => 'M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z'],
            ['route' => 'refill.index', 'text' => 'Pill Count'],
        ]" />

    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">        

            <div class="sm:rounded-lg py-4 sm:px-0 px-4">
                <div class="mb-4">
                    <x-primary-link route="refill.create" class="w-full">
                        Update pill count
                    </x-primary-link>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="max-w-xl text-sm text-gray-600">
                            <p>As of {{ now()->toDateString() }}, the current pill count is:</p>
                        </div>
                        <div class="mt-5 text-3xl leading-9 font-semibold text-gray-900">
                            {{ $pills }} pills
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
