<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class SearchViewModel extends ViewModel
{
    public $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function results(){
        return collect($this->results)->take(7)->map(function($result){
            if (isset($result['title'])) {
                    $title = $result['title'];
                } elseif (isset($result['name'])) {
                    $title = $result['name'];
                } else {
                    $title = 'Untitled';
                }
            return collect($result)->merge([
                'poster_path'=>'https://image.tmdb.org/t/p/w92/'.$result['poster_path'],
                'title'=>$title,
                'linkToPage' => $result['media_type'] === 'movie' ? route('movies.show', $result['id']) : route('tv.show', $result['id']),
            ])->only('id','title','name','poster_path','linkToPage');
        });
    }
}
