<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkDetail extends Component
{
    public $currentLink;

    public function downloadQr(){
        $qrCode = QrCode::size(500)->generate(route('shortlink', $this->currentLink));

        Storage::disk('public')->put('qrCode/'.$this->currentLink->slug.'.svg', $qrCode);

        return Storage::disk('public')->download('qrCode/'.$this->currentLink->slug.'.svg');
    }

    public function render()
    {
        return view('livewire.link-detail');
    }
}
