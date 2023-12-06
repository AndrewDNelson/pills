<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doses') }}
        </h2>

        <x-breadcrumbs class="mt-2" :items="[
            ['route' => 'home', 'text' => 'Dashboard', 'icon' => 'M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z'],
            ['route' => 'doses.index', 'text' => 'Doses'],
        ]" />  

    </x-slot>


    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">        

            <div class="sm:rounded-lg py-4 sm:px-0 px-4">
                <div class="mb-4">
                    <x-primary-link route="doses.update" class="w-full">
                        Update doses
                    </x-primary-link>
                </div>
                <div class="">
                    @foreach ($doses->sortBy('time') as $dose)
                        <div class="flex justify-between items-center mb-4 p-4 bg-white shadow rounded-lg">
                            <div>
                                <div class="flex items-end gap-3">
                                    <p class="text-normal font-medium text-black">{{ date("F j, Y g:i A", strtotime($dose->time)) }}</p>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('doses.destroy', $dose) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-neutral-500 rounded hover:underline text-sm font-medium tracking-wide transition mx-4"
                                            onclick="event.preventDefault();
                                            this.closest('form').submit();">Delete</button>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>
