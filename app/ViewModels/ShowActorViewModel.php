<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class ShowActorViewModel extends ViewModel
{
    public $actor;
    public $credits;
    public $social;

    public function __construct($actor,$credits,$social)
    {
        $this->actor = $actor;
        $this->credits=$credits;
        $this->social=$social;
    }

    public function actor(){

        $currentYear = \Carbon\Carbon::now()->year;

        return collect($this->actor)->merge([
                'profile_path'=>$this->actor['profile_path'] ? 'https://image.tmdb.org/t/p/w500/'.$this->actor['profile_path'] : 'https://ui-avatars.com/api/?size=235&name='.$this->actor['name'],
                'birthday' => \Carbon\Carbon::parse($this->actor['birthday'])->format('M d, Y'),
                'age'=>'('.\Carbon\Carbon::parse($this->actor['birthday'])->age.' years old)',
            ])->only(['id','name','birthday','biography','profile_path','age','homepage']);

    }

    public function knownFor(){

        $cast=collect($this->credits)->get('cast');

        return collect($cast)->sortByDesc('popularity')->unique('name')->take(5)->map(function($movie){
                if (isset($movie['title'])) {
                    $title = $movie['title'];
                } elseif (isset($movie['name'])) {
                    $title = $movie['name'];
                } else {
                    $title = 'Untitled';
                }
                return collect($movie)->merge([
                    'poster_path' => $movie['poster_path'] ? 'https://image.tmdb.org/t/p/w185'.$movie['poster_path'] : 'https://via.placeholder.com/185x278',
                    'title' => $title,
                    'linkToPage' => $movie['media_type'] === 'movie' ? route('movies.show', $movie['id']) : route('tv.show', $movie['id']),
                ])->only(['id','poster_path','linkToPage','title','media_type','name']);
        });

    }

    public function credits(){
        $cast=collect($this->credits)->get('cast');

        return collect($cast)->map(function($movie){
                if (isset($movie['release_date'])) {
                    $releaseDate = $movie['release_date'];
                } elseif (isset($movie['first_air_date'])) {
                    $releaseDate = $movie['first_air_date'];
                } else {
                    $releaseDate = '';
                }

                if (isset($movie['title'])) {
                    $title = $movie['title'];
                } elseif (isset($movie['name'])) {
                    $title = $movie['name'];
                } else {
                    $title = 'Untitled';
                }
                return collect($movie)->merge([
                    'title' => $title,
                    'linkToPage' => $movie['media_type'] === 'movie' ? route('movies.show', $movie['id']) : route('tv.show', $movie['id']),
                    'release_date' => $releaseDate,
                    'release_year'=> isset($releaseDate) ? \Carbon\Carbon::parse($releaseDate)->year : 'Future',
                    'character'=> isset($movie['character']) ? $movie['character'] : '',
                ])->only([
                'release_date', 'release_year', 'title', 'character', 'linkToPage',
            ]);
        })->sortByDesc('release_year');
    }

    public function social(){
        return collect($this->social)->merge([
            'twitter' => $this->social['twitter_id'] ? 'https://twitter.com/'.$this->social['twitter_id'] : null,
            'facebook' => $this->social['facebook_id'] ? 'https://facebook.com/'.$this->social['facebook_id'] : null,
            'instagram' => $this->social['instagram_id'] ? 'https://instagram.com/'.$this->social['instagram_id'] : null,
        ])->only([
            'facebook', 'instagram', 'twitter',
        ]);
    }
}
