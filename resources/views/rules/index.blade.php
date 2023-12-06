<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rules') }}
        </h2>
        
        <x-breadcrumbs class="mt-2" :items="[
            ['route' => 'home', 'text' => 'Dashboard', 'icon' => 'M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z'],
            ['route' => 'rules.index', 'text' => 'Rules', 'icon' => 'M7.84 1.804A1 1 0 018.82 1h2.36a1 1 0 01.98.804l.331 1.652a6.993 6.993 0 011.929 1.115l1.598-.54a1 1 0 011.186.447l1.18 2.044a1 1 0 01-.205 1.251l-1.267 1.113a7.047 7.047 0 010 2.228l1.267 1.113a1 1 0 01.206 1.25l-1.18 2.045a1 1 0 01-1.187.447l-1.598-.54a6.993 6.993 0 01-1.929 1.115l-.33 1.652a1 1 0 01-.98.804H8.82a1 1 0 01-.98-.804l-.331-1.652a6.993 6.993 0 01-1.929-1.115l-1.598.54a1 1 0 01-1.186-.447l-1.18-2.044a1 1 0 01.205-1.251l1.267-1.114a7.05 7.05 0 010-2.227L1.821 7.773a1 1 0 01-.206-1.25l1.18-2.045a1 1 0 011.187-.447l1.598.54A6.993 6.993 0 017.51 3.456l.33-1.652zM10 13a3 3 0 100-6 3 3 0 000 6z'],
        ]" />

    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">        

            <div class="sm:rounded-lg py-4 sm:px-0 px-4">
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
