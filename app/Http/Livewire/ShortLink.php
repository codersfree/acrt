<?php

namespace App\Http\Livewire;

use App\Models\Link;
use Livewire\Component;
use Illuminate\Support\Str;

class ShortLink extends Component
{
    public $url;

    public $currentLink;

    public $links;

    protected $listeners = [
        'linkAdded' => 'linkAdded'
    ];

    public function mount(){
        $this->getLinks();

        if ($this->links->count()) {
            $this->currentLink = $this->links->first();
        }
    }

    public function getLinks(){
            
        $this->links = Link::where('user_id', auth()->id())
                        ->latest('id')
                        ->get();
    
    }

    public function save()
    {

        $this->validate([
            'url' => ['required', 'regex:/^(http|https)?(:\/\/)?(www\.)?[a-zA-Z0-9]+([\-\.]{1}[a-zA-Z0-9]+)*\.[a-zA-Z]{2,5}(:[0-9]{1,5})?(\/.*)?$/']
        ]);

        $link = Link::create([
            'title' => $this->url,
            'url' => $this->url,
            'slug' => Str::random(6),
            'user_id' => auth()->id()
        ]);

        $this->getLinks();

        if ($this->links->count() == 1) {
            $this->currentLink = $link;
        }

        $this->emit('linkAdded', $link);

        $this->reset('url');
    }

    public function changeLink($linkId)
    {
        $this->currentLink = Link::find($linkId);
    }

    public function linkAdded($link)
    {

        $link = Link::find($link['id']);

        try {

            $url = $link->url;

            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }

            $contents = file_get_contents($url);

            if (preg_match('/<title>(.*)<\/title>/', $contents, $matches)) {

                $title = $matches[1];

                $link->update([
                    'title' => $title
                ]);
            }

        } catch (\Exception $e) {
            
            $this->emit('error', 'No se pudo acceder a la URL');

        }
    }

    public function render()
    {
        return view('livewire.short-link');
    }
}
