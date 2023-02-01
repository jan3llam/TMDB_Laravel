<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\ViewModels\SearchViewModel;


class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $results=[];
        if(strlen($this->search)>1){
            $results=Http::get('https://api.themoviedb.org/3/search/multi'.'?api_key='.config('services.tmdb.api').'&query='.$this->search)->json()['results'];

        }


        $viewModel=new SearchViewModel($results);

        return view('livewire.search-dropdown',$viewModel);
    }
}
