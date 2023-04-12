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
                        
                        <div class="px-4 py-8 contenedor">
        
                            <div class="bg-white p-6 shadow-lg rounded-lg mb-8">
                        
                                <h2 class="text-xl font-semibold mb-2">
                                    {{$currentLink->title}}
                                </h2>
                        
                                <p>
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    {{$currentLink->created_at->format('F d, Y h:i A')}}
                                </p>
                        
                            </div>
                        
                            <div class="bg-white p-6 shadow-lg rounded-lg mb-8">
                        
                                <div class="flex justify-between mb-4" x-data="{
                                    copied: false,
                                    copyToClipboard(){
                        
                                        const enlace = $refs.enlace;
                        
                                        let input = document.createElement('input');
                                        input.setAttribute('type', 'text');
                                        input.setAttribute('value', enlace.innerText);
                        
                                        document.body.appendChild(input);
                        
                                        input.select();
                        
                                        document.execCommand('copy');
                        
                                        document.body.removeChild(input);
                        
                                        this.copied = true;
                        
                                        setTimeout(() => {
                                            this.copied = false;
                                        }, 2000);
                                    }
                                }">
                        
                                    <div>
                                        <h2 class="text-lg font-semibold text-blue-600" x-ref="enlace">
                                            {{ route('shortlink', $currentLink) }}
                                        </h2>
                        
                                        <p>
                                            {{$currentLink->visits->count()}} visitas
                                        </p>
                        
                                        <a href="{{ route('shortlink', $currentLink) }}" 
                                            class="inline-flex items-center"
                                            target="__blank">
                                            <i class="fa-solid fa-turn-up rotate-90 mr-2"></i>
                                            {{ route('shortlink', $currentLink) }}
                                        </a>
                                    </div>
                        
                                    <div>
                        
                                        <button class="bg-gray-100 px-4 py-2 rounded-lg shadow-lg" x-on:click="copyToClipboard()">
                        
                                            <i class="fa-solid fa-copy mr-2"></i>
                        
                                            <span x-text="copied ? '¡Copiado!' : 'Copiar'"></span>
                                        </button>
                        
                                    </div>
                        
                                </div>
                        
                                <div>
                                    <h2 class="text-lg font-semibold">
                                        QR Code
                                    </h2>
                        
                                    <div class="flex">
                                        {!! QrCode::size(100)->generate(route('shortlink', $currentLink)) !!}
                        
                                        <x-button class="ml-4" wire:click="downloadQr()">
                                            <i class="fa-solid fa-download mr-2"></i>
                                            Descargar
                                        </x-button>
                                    </div>
                                </div>
                        
                            </div>
                        
                            <canvas id="myChart"></canvas>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    @endif

    @push('js')
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

            function initChart(labels, data){

                const ctx = document.getElementById('myChart');
            
                new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                    label: 'Paises',
                    data: data,
                    borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
                });
            }

            initChart(@json($this->labels), @json($this->data));


            Livewire.on('renderChart', (labels, data) => {

                const canvas = document.getElementById('myChart');
                canvas.remove();

                const newCanvas = document.createElement('canvas');
                newCanvas.setAttribute('id', 'myChart');

                const contenedor = document.querySelector('.contenedor');
                contenedor.appendChild(newCanvas);

                initChart(labels, data);
            });

        </script>
        
    @endpush

</div>
