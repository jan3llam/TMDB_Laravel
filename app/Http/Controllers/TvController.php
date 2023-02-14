<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\TvViewModel;
use App\ViewModels\ShowTvViewModel;
use Illuminate\Http\Client\ConnectionException;

class TvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $popular =Http::get('https://api.themoviedb.org/3/tv/popular'.'?api_key='.config('services.tmdb.api'))->json()['results'];

            $topRated=Http::get('https://api.themoviedb.org/3/tv/top_rated'.'?api_key='.config('services.tmdb.api'))->json()['results'];

            $genres = Http::get('https://api.themoviedb.org/3/genre/tv/list'.'?api_key='.config('services.tmdb.api'))->json()['genres'];

            $viewModel = new TvViewModel($popular,$topRated,$genres);

            return view('tv.index',$viewModel);  
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $details=Http::get('https://api.themoviedb.org/3/tv/'.$id.'?api_key='.config('services.tmdb.api').'&append_to_response=credits,videos,images')->json();

            if(isset($details['success'])){
                if($details['success']==false){
                    return redirect()->to('/tv')->with('failure','Source couldn\'t be found..');
                }
            }

            $viewModel=new ShowTvViewModel($details);

            return view('tv.show',$viewModel);
        }
        catch(ConnectionException $e){

            return redirect()->to('/tv')->with('failure','Connection Problem..');
        }
    }

}
