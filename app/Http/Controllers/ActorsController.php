<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\ActorsViewModel;
use App\ViewModels\ShowActorViewModel;

class ActorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page=1)
    {
        abort_if($page > 500,204);

        $popular = Http::get("https://api.themoviedb.org/3/person/popular?api_key=".config('services.tmdb.api')."&page=".$page)->json()['results'];


        $viewModel=new ActorsViewModel($popular,$page);

        return view('actors.index',$viewModel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $actor = Http::get("https://api.themoviedb.org/3/person/".$id."?api_key=".config('services.tmdb.api'))->json();

        $credits = Http::get("https://api.themoviedb.org/3/person/".$id."/combined_credits?api_key=".config('services.tmdb.api'))->json();

        $social=Http::get("https://api.themoviedb.org/3/person/".$id."/external_ids?api_key=".config('services.tmdb.api'))->json();


        $viewModel=new ShowActorViewModel($actor,$credits,$social);

        return view('actors.show',$viewModel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
