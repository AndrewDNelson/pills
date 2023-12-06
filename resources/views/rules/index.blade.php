<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rules') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">            
            <div class="p-4 sm:p-8 sm:rounded-lg">
                <div class="mb-4">
                    <x-primary-link route="rules.create" class="w-full">
                        Create New Rule
                    </x-primary-link>
                </div>
                <div class="">
                    @foreach ($rules->sortBy('time') as $rule)
                        <div class="flex justify-between items-center mb-4 p-4 bg-white shadow rounded-lg">
                            <div>
                                <div class="flex items-end gap-3">
                                    <p class="text-3xl font-medium text-black">{{ date("g:i A", strtotime($rule->time)) }}</p>
                                    <p class="text-medium text-sky-500">{{ $rule->pills }} pills</p>
                                </div>
                                
                                <p class="text-medium text-neutral-700">
                                    @foreach(json_decode($rule->days_of_week) as $day)
                                        {{ $day }},
                                    @endforeach
                                </p>
                            </div>
                            <div>
                                <form action="{{ route('rules.destroy', $rule) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-neutral-500 rounded hover:underline text-sm font-medium tracking-wide transition mx-4"
                                            onclick="event.preventDefault();
                                            this.closest('form').submit();">Delete</button>
                                </form>

                                <x-secondary-link route="rules.edit" :itemId="$rule">
                                    Update
                                </x-secondary-link>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>
