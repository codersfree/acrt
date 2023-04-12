<div>

    <div class="bg-white shadow-lg rounded px-8 py-6 mb-12">

        <form wire:submit.prevent="save">

            <x-validation-errors class="mb-4" />

            <div class="flex">
                <x-input wire:model.defer="url" type="text" class="w-full" placeholder="Ingrese la URL que quiere acortar" />

                <x-button class="ml-4">
                    Acortar
                </x-button>

            </div>
        </form>
    </div>

    @if ($links->count())
    
        <div class="bg-gray-50 shadow-xl rounded-lg">
            
            <div class="grid grid-cols-4">

                <div class="col-span-1 border-r border-gray-200">

                    <div class="px-4 py-2 border-b">

                        <p class="font-semibold">
                            {{$links->count()}} url's encontradas
                        </p>

                    </div>

                    <ul class="divide-y divide-gray-200">
                        @foreach ($links as $link)
                            
                            <li class="p-4 cursor-pointer {{ $currentLink && ($link->id == $currentLink->id) ? 'bg-blue-50' : '' }}" wire:click="changeLink({{$link->id}})">
                                <p class="text-xs">
                                    {{$link->created_at->format('d M Y')}}
                                </p>

                                <p class="whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{$link->title}}
                                </p>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('shortlink', $link) }}" target="__blank" class="text-xs text-red-500 font-semibold">
                                        {{ route('shortlink', $link) }}
                                    </a>

                                    <span class="text-sm">
                                        {{$link->visits->count()}}

                                        <i class="fa-solid fa-chart-simple"></i>
                                    </span>
                                </div>
                            </li>

                        @endforeach
                    </ul>
                </div>

                <div class="col-span-3">

                    @if ($currentLink)
                        
                        @livewire('link-detail', ['link' => $currentLink], key('link-detail-' . $currentLink->id))

                    @endif

                </div>

            </div>

        </div>

    @endif

</div>
