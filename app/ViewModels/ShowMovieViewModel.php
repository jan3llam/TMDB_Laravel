<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class ShowMovieViewModel extends ViewModel
{

    public $movie;

    public function __construct($movie)
    {
        $this->movie=$movie;
    }

    public function movie(){
        return collect($this->movie)->merge([
            'poster_path'=>'https://image.tmdb.org/t/p/w500/'.$this->movie['poster_path'],
            'vote_average'=>$this->movie['vote_average']*10 .'%',
            'release_date'=>\Carbon\Carbon::parse($this->movie['release_date'])->format('M d, Y'),
            'genres'=>collect($this->movie['genres'])->pluck('name')->implode(', '),
            'crew'=>collect($this->movie['credits']['crew'])->take(2),
            'cast'=>collect($this->movie['credits']['cast'])->take(5),
            'images'=>collect($this->movie['images']['backdrops'])->take(9),
        ])->only(['poster_path','id','genres','title','vote_average','overview','release_date','credits','images','crew','cast','videos']);
    }

    /* another solution to format genres  */
    private function formatGenres($genres){
        return collect($genres)->mapWithKeys(function($genre){
                return [$genre['id']=>$genre['name']];
        });
    }
}
