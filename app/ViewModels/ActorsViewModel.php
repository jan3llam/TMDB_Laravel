<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class ActorsViewModel extends ViewModel
{

    public $popular;
    public $page;

    public function __construct($popular,$page)
    {
        $this->popular=$popular;
        $this->page=$page;
    }

    public function popular(){

        return collect($this->popular)->map(function($actor){
                return collect($actor)->merge([
                    'profile_path'=>$actor['profile_path'] ? 'https://image.tmdb.org/t/p/w235_and_h235_face'.$actor['profile_path'] : 'https://ui-avatars.com/api/?size=235&name='.$actor['name'],
                    'known_for'=>collect($actor['known_for'])->where('media_type','tv')->pluck('name')->union(
                                    collect($actor['known_for'])->where('media_type','movie')->pluck('title')
                                    )->take(2)->implode(', '),
                ])->only('name','id','poster_path','known_for','profile_path');
        });
    }

    public function previous(){
        return $this->page > 1 ? $this->page -1 : null ;
    }

    public function next(){
        return $this->page < 500 ? $this->page +1 : null ;
    }
}
