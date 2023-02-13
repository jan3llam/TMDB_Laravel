@extends('layouts.main')

@section('style')

   li.stars:hover ~ li.stars {
        color: #f97316;
    }

@endsection

@section('content')

<div class="movie-info border-b border-gray-800">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
        <div class="flex-none">
            <img src="{{$movie['poster_path']}}" alt="poster" class="w-64 lg:w-96">
        </div>
        <div class="md:ml-24">
            <h2 class="text-4xl mt-4 md:mt-0 font-semibold">{{$movie['title']}}</h2>
            <div class="flex flex-wrap items-center text-gray-400 text-sm">
                <svg class="fill-current text-orange-500 w-4" viewBox="0 0 24 24"><g data-name="Layer 2"><path d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z" data-name="star"/></g></svg>
                <span class="ml-1">{{$movie['vote_average']}}</span>
                <span class="mx-2">|</span>
                <span>{{ $movie['release_date']}}</span>
                <span class="mx-2">|</span>
                <span>{{$movie['genres']}}</span>
            </div>

            <p class="text-gray-300 mt-8">
                {{$movie['overview']}}
            </p>

            <div class="mt-12">
                <h4 class="text-white font-semibold">Featured Crew</h4>
                <div class="flex mt-4">
                    @foreach($movie['crew'] as $crew)
                        <div class="mr-8">
                            <div>{{$crew['name']}}</div>
                            <div class="text-sm text-gray-400">{{$crew['job']}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div x-data="{isOpen:false,isRate:false}">
                @if(count($movie['videos']['results']) > 0)
                    @foreach($movie['videos']['results'] as $video)
                        @if($video['type']=='Trailer')
                            <div class="mt-12">
                                <button @click="isOpen=true
                                                $refs.video.setAttribute('src','https://youtube.com/embed/{{$video['key']}}');"
                                class="flex inline-flex items-center bg-orange-500 text-gray-900 rounded font-semibold px-5 py-4 hover:bg-orange-600 transition ease-in-out duration-150"
                                >
                                    <svg class="w-6 fill-current" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                    <span class="ml-2">Play Trailer</span>
                                </button>
                                @if(Auth::guard('user')->check())
                                    <button @click="isRate=true"
                                    class="flex inline-flex items-center ml-2 bg-orange-500 text-gray-900 rounded font-semibold px-5 py-4 hover:bg-orange-600 transition ease-in-out duration-150"
                                    >
                                        <svg class="w-6 fill-current" viewBox="0 0 24 24"><g data-name="Layer 2"><path d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z" data-name="star"/></g></svg>
                                        <span class="ml-2">Rate Now!</span>
                                    </button>
                                @endif
                            </div>
                        @break
                        @endif
                    @endforeach
                @endif
                <div
                    style="background-color: rgba(0, 0, 0, .5);"
                    class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                    x-show="isOpen"
                    x-transition.opacity
                >
                    <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                        <div class="bg-gray-900 rounded">
                            <div class="flex justify-end pr-4 pt-2">
                                <button
                                    @click="isOpen = false
                                            $refs.video.setAttribute('src','');"
                                    @keydown.escape.window="isOpen = false"
                                    class="text-3xl leading-none hover:text-gray-300">&times;
                                </button>
                            </div>
                            <div class="modal-body px-8 py-8">
                                <div class="responsive-container overflow-hidden relative" style="padding-top: 56.25%;">
                                  <iframe x-ref="video" width="560" height="315" src=""
                                  class="responsive-iframe absolute top-0 left-0 w-full h-full" frameborder="0"  allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{route('users.rate',[$movie['id'],$movie['title']])}}">
                    @csrf
                    <div
                        style="background-color: rgba(0, 0, 0, .5);"
                        class="fixed top-1/2 bottom-1/2 flex items-center shadow-lg"
                        x-show="isRate" x-transition.opacity
                    >
                        <div class="container mx-auto lg:px-32 rounded-lg">
                            <div class="bg-gray-800 rounded">
                                <div class="flex justify-end pr-2">
                                    <button
                                        @click="resetStars
                                                isRate=false"
                                        @keydown.escape.window="isRate = false"
                                        class="text-2xl leading-none hover:text-gray-300"
                                        type="button">&times;
                                    </button>
                                </div>
                                <div class="px-8 py-8">
                                    <div class="flex justify-center mb-3">
                                        <i class="fa fa-frown-o text-lg mr-1" aria-hidden="true"></i><span class="text-xl">Submit your rating here!</span><i class="fa fa-smile-o text-lg ml-1" aria-hidden="true"></i>
                                    </div>
                                    <div class="responsive-container overflow-hidden relative ">
                                        <input x-ref="rate" type="hidden" id="rate" name="rate" value="0">
                                        <ul class="flex flex-row-reverse justify-center">
                                            @for ($i = 10; $i >0; $i--)     
                                                <li x-ref="r{{$i}}"
                                                    @click="starsRating
                                                            $refs.rate.setAttribute('value',$refs.r{{$i}}.getAttribute('title'))" class="stars text-2xl text-black mr-1 hover:text-orange-500" title="{{$i}}">
                                                    <i class="fa fa-star"></i>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <div class="flex justify-center mt-4">
                                        <button type="submit" class="flex items-center px-4 py-2   bg-orange-500 text-white rounded font-semibold hover:bg-orange-600" name="rmovie">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<div class="movie-cast border-b border-gray-800">
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-semibold">Cast</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach($movie['cast'] as $cast)
            <div class="mt-8">
                <a href="{{route('actors.show',$cast['id'])}}">
                    <img src="{{'https://image.tmdb.org/t/p/w300/'.$cast['profile_path']}}" alt="actor" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
                <div class="mt-2">
                     <a href="{{route('actors.show',$cast['id'])}}" class="text-lg mt-2 hover:text-gray:300">
                        {{$cast['name']}}
                    </a>
                    <div class="text-sm text-gray-400">
                        {{$cast['character']}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div> 
</div>

<div class="movie-images" x-data="{isOpen:false,image:''}">
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-semibold">Images</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($movie['images'] as $image)
                    <div class="mt-8">
                        <a @click.prevent="isOpen=true
                             image='{{'https://image.tmdb.org/t/p/original/'.$image['file_path']}}'"
                             href="#">
                            <img src="{{'https://image.tmdb.org/t/p/w300/'.$image['file_path']}}" alt="image" class="hover:opacity-75 transition ease-in-out duration-150">
                        </a>
                    </div>
            @endforeach
        </div>
        <div
            style="background-color: rgba(0, 0, 0, .5);"
            class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
            x-show="isOpen" x-transition.opacity>
            <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                <div class="bg-gray-900 rounded">
                    <div class="flex justify-end pr-4 pt-2">
                        <button
                            @click="isOpen = false"
                            @keydown.escape.window="isOpen = false"
                            class="text-3xl leading-none hover:text-gray-300">&times;
                        </button>
                    </div>
                    <div class="modal-body px-8 py-8">
                        <img :src="image" alt="poster">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    <script type="text/javascript">

        const stars = document.getElementsByClassName('stars');

        function starsRating(){
            const rate = parseInt(document.getElementById('rate').getAttribute('value'));

            for (let i = 0; i < stars.length; i++) {
                  if(parseInt(stars[i].getAttribute('title'))<=rate){
                    stars[i].setAttribute('style','color:#f97316;');
                  }
                  else{
                    stars[i].setAttribute('style','{color:black;} :hover ~ li{color:#f97316;}');
                  }
                }
        }

        function resetStars(){
            for (let i = 0; i < stars.length; i++) {
                stars[i].setAttribute('style','{color:black;} :hover ~ li{color:#f97316;}');
            }
        }      

    </script>

@endsection