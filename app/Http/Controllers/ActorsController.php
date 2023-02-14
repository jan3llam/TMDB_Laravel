<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\ActorsViewModel;
use App\ViewModels\ShowActorViewModel;
use Illuminate\Http\Client\ConnectionException;

class ActorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page=1)
    {   
        try{
            abort_if($page > 500,204);

            $popular = Http::get("https://api.themoviedb.org/3/person/popular?api_key=".config('services.tmdb.api')."&page=".$page)->json()['results'];


            $viewModel=new ActorsViewModel($popular,$page);

            return view('actors.index',$viewModel);
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
            $actor = Http::get("https://api.themoviedb.org/3/person/".$id."?api_key=".config('services.tmdb.api'))->json();

            $credits = Http::get("https://api.themoviedb.org/3/person/".$id."/combined_credits?api_key=".config('services.tmdb.api'))->json();

            $social=Http::get("https://api.themoviedb.org/3/person/".$id."/external_ids?api_key=".config('services.tmdb.api'))->json();

            if(isset($actors['success'])){
                if($actor['success']==false){
                    return redirect()->to('/actors')->with('failure','Source couldn\'t be found..');
                }
            }

            $viewModel=new ShowActorViewModel($actor,$credits,$social);

            return view('actors.show',$viewModel);    
        }
        catch(ConnectionException $e){

            return redirect()->to('/actors')->with('failure','Connection Problem..');
        }
    }

}
