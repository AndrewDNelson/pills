<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit pill rule') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">            
            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="">
                    
                        <div class="flex justify-between items-center mb-4 p-4 bg-white shadow rounded-lg">

                            <form method="POST" action="{{ route('rules.update', $rule) }}" class="w-full max-w-xl space-y-6" >
                                @method('PATCH')
                                @csrf

                                <div>
                                    <label for='pills' class='block font-semibold text-base text-gray-700'>
                                        Pills
                                    </label>
                                    <div class="w-full mx-auto my-1">
                                        <input name="pills" id="pills" type="number" placeholder="Count" value="{{ old("pills", $rule->pills) }}" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
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
                                        <input name="time" id="time" type="time" placeholder="Time" value="{{ old("time", substr($rule->time, 0, 5)) }}" class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
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
                                                <input type="checkbox" id="{{ $day }}" name="days_of_week[]" value="{{ $day }}" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-neutral-900 focus:ring-neutral-900" {{ (is_array(old('days_of_week')) && in_array($day, old('days_of_week'))) || in_array($day, json_decode($rule->days_of_week)) ? 'checked' : '' }}>
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
