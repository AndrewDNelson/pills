<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create new rule') }}
        </h2>
        <x-breadcrumbs class="mt-2" :items="[
            ['route' => 'home', 'text' => 'Dashboard', 'icon' => 'M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z'],
            ['route' => 'rules.index', 'text' => 'Rules', 'icon' => 'M7.84 1.804A1 1 0 018.82 1h2.36a1 1 0 01.98.804l.331 1.652a6.993 6.993 0 011.929 1.115l1.598-.54a1 1 0 011.186.447l1.18 2.044a1 1 0 01-.205 1.251l-1.267 1.113a7.047 7.047 0 010 2.228l1.267 1.113a1 1 0 01.206 1.25l-1.18 2.045a1 1 0 01-1.187.447l-1.598-.54a6.993 6.993 0 01-1.929 1.115l-.33 1.652a1 1 0 01-.98.804H8.82a1 1 0 01-.98-.804l-.331-1.652a6.993 6.993 0 01-1.929-1.115l-1.598.54a1 1 0 01-1.186-.447l-1.18-2.044a1 1 0 01.205-1.251l1.267-1.114a7.05 7.05 0 010-2.227L1.821 7.773a1 1 0 01-.206-1.25l1.18-2.045a1 1 0 011.187-.447l1.598.54A6.993 6.993 0 017.51 3.456l.33-1.652zM10 13a3 3 0 100-6 3 3 0 000 6z'],
            ['route' => 'rules.create', 'text' => 'New'],
        ]" />
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">            
            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="">
                    
                        <div class="flex justify-between items-center mb-4 p-4 bg-white shadow rounded-lg">

                            <form method="POST" action="{{ route('rules.store') }}" class="w-full max-w-xl space-y-6" >
                                @method('POST')
                                @csrf

                                <div>
                                    <label for='pills' class='block font-semibold text-base text-gray-700'>
                                        Pills
                                    </label>
                                    <div class="w-full mx-auto my-1">
                                        <input name="pills" id="pills" type="number" placeholder="Count" value="{{ old("pills") }}" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    @error("pills")
                                        <ul class="text-sm text-red-600 space-y-1">
                                            <li>{{ $message }}</li>
                                        </ul>
                                    @enderror
                                </div>

                                <div>
                                    <label for='time' class='block font-semibold text-base text-gray-700'>
                                        Time
                                    </label>
                                    <div class="w-full mx-auto">
                                        <input name="time" id="time" type="time" placeholder="Time" value="{{ old("time") }}" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    @error("time")
                                        <ul class="text-sm text-red-600 space-y-1">
                                            <li>{{ $message }}</li>
                                        </ul>
                                    @enderror
                                </div>

                                <div>
                                    <label for='days_of_week' class='block font-semibold text-base text-gray-700'>
                                        Days of Week
                                    </label>
                                    <div class="w-full mx-auto">
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <div>
                                                <input type="checkbox" id="{{ $day }}" name="days_of_week[]" value="{{ $day }}" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-neutral-900 focus:ring-neutral-900" {{ is_array(old('days_of_week')) && in_array($day, old('days_of_week')) ? 'checked' : '' }}>
                                                <label for="{{ $day }}" class="ml-2 text-sm font-medium text-gray-900">{{ $day }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error("days_of_week")
                                        <ul class="text-sm text-red-600 space-y-1">
                                            <li>{{ $message }}</li>
                                        </ul>
                                    @enderror
                                </div>

                                <x-primary-button>
                                    Submit
                                </x-primary-button>
                            </form>

                        </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
