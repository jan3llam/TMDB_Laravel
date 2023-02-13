<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\MoviesViewModel;
use App\ViewModels\ShowMovieViewModel;
use Illuminate\Http\Client\ConnectionException;


class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $popular = Http::get('https://api.themoviedb.org/3/movie/popular'.'?api_key='.config('services.tmdb.api'))->json()['results'];

            $nowPlaying = Http::get('https://api.themoviedb.org/3/movie/now_playing'.'?api_key='.config('services.tmdb.api'))->json()['results'];


            $genres = Http::get('https://api.themoviedb.org/3/genre/movie/list'.'?api_key='.config('services.tmdb.api'))->json()['genres'];


            $viewModel = new MoviesViewModel($popular,$nowPlaying,$genres);


            return view('movies.index',$viewModel);       
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
            $details=Http::get('https://api.themoviedb.org/3/movie/'.$id.'?api_key='.config('services.tmdb.api').'&append_to_response=credits,videos,images')->json();

            if($details['success']==false){
                return redirect()->to('/movies')->with('failure','Source couldn\'t be found..');
            }

            $viewModel = new ShowMovieViewModel($details);

            return view('movies.show',$viewModel);
        }
        catch(ConnectionException $e){

            return redirect()->to('/movies')->with('failure','Connection Problem..');
        }
    }

}
