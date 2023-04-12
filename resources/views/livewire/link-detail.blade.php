<div class="px-4 py-8">
    
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

</div>
