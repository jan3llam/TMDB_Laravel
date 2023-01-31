<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class TvViewModel extends ViewModel
{
    public $topRated;
    public $popular;
    public $genres;

    public function __construct($topRated,$popular,$genres)
    {
        $this->topRated=$topRated;
        $this->popular=$popular;
        $this->genres=$genres;
    }

    public function topRated(){

        return $this->formatTv($this->topRated);

    }

    public function popular(){

        return $this->formatTv($this->popular);

    }

    public function genres(){
        return collect($this->genres)->mapWithKeys(function($genre){
                return [$genre['id']=>$genre['name']];
        });
    }

    private function formatTv($tvShows){

        return collect($tvShows)->map(function($show){
            $genresFormatted = collect($show['genre_ids'])->mapWithKeys(function($value){
                    return [$value=>$this->genres()->get($value)];
            })->implode(', ');
            return collect($show)->merge([
                'poster_path'=>'https://image.tmdb.org/t/p/w500/'.$show['poster_path'],
                'vote_average'=>$show['vote_average']*10 .'%',
                'first_air_date'=>\Carbon\Carbon::parse($show['first_air_date'])->format('M d, Y'),
                'genres'=> $genresFormatted,
            ])->only(['poster_path','id','genre_ids','vote_average','overview','first_air_date','genres','name']);
        });   
    }
}
