<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

class ViewMoviesTest extends TestCase
{
    /** @test */
    public function the_main_page_shows_correct_info(){

         Http::fake([
            'https://api.themoviedb.org/3/movie/popular?api_key=b6e0f9a4d4b4905db6287bf7931e9b29' => $this->fakePopularMovies(),
            'https://api.themoviedb.org/3/movie/now_playing?api_key=b6e0f9a4d4b4905db6287bf7931e9b29' => $this->fakeNowPlayingMovies(),
        ]);

        $response = $this->get(route('movies.index'));

        $response->assertSuccessful();
        $response->assertSee('Popular Movies');
        $response->assertSee('Fake Movie');
        $response->assertSee('Now Playing');
        $response->assertSee('Now Playing Fake Movie');

    }

    /** @test */
    public function the_search_dropdown_works_correctly()
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie?query=jumanji' => $this->fakeSearchMovies(),
        ]);

        Livewire::test('search-dropdown')
            ->assertDontSee('jumanji')
            ->set('search', 'jumanji')
            ->assertSee('Jumanji');
    }

    private function fakeSearchMovies()
    {
        return Http::response([
                'results' => [
                    [
                        "popularity" => 406.677,
                        "vote_count" => 2607,
                        "video" => false,
                        "poster_path" => "/xBHvZcjRiWyobQ9kxBhO6B2dtRI.jpg",
                        "id" => 419704,
                        "adult" => false,
                        "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                        "original_language" => "en",
                        "original_title" => "Jumanji",
                        "genre_ids" => [
                            12,
                            18,
                            9648,
                            878,
                            53,
                        ],
                        "title" => "Jumanji",
                        "vote_average" => 6,
                        "overview" => "Jumanji description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond. While a mysterious phenomenon menaces to destroy life on planet earth.",
                        "release_date" => "2019-09-17",
                    ]
                ]
            ], 200);
    }

    private function fakePopularMovies()
    {
        return Http::response([
                'results' => [
                    [
                        "popularity" => 406.677,
                        "vote_count" => 2607,
                        "video" => false,
                        "poster_path" => "/xBHvZcjRiWyobQ9kxBhO6B2dtRI.jpg",
                        "id" => 419704,
                        "adult" => false,
                        "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                        "original_language" => "en",
                        "original_title" => "Fake Movie",
                        "genre_ids" => [
                            12,
                            18,
                            9648,
                            878,
                            53,
                        ],
                        "title" => "Fake Movie",
                        "vote_average" => 6,
                        "overview" => "Fake movie description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond. While a mysterious phenomenon menaces to destroy life on planet earth.",
                        "release_date" => "2019-09-17",
                    ]
                ]
            ], 200);
    }

    private function fakeNowPlayingMovies()
    {
        return Http::response([
                'results' => [
                    [
                        "popularity" => 406.677,
                        "vote_count" => 2607,
                        "video" => false,
                        "poster_path" => "/xBHvZcjRiWyobQ9kxBhO6B2dtRI.jpg",
                        "id" => 419704,
                        "adult" => false,
                        "backdrop_path" => "/5BwqwxMEjeFtdknRV792Svo0K1v.jpg",
                        "original_language" => "en",
                        "original_title" => "Now Playing Fake Movie",
                        "genre_ids" => [
                            12,
                            18,
                            9648,
                            878,
                            53,
                        ],
                        "title" => "Now Playing Fake Movie",
                        "vote_average" => 6,
                        "overview" => "Now playing fake movie description. The near future, a time when both hope and hardships drive humanity to look to the stars and beyond. While a mysterious phenomenon menaces to destroy life on planet earth.",
                        "release_date" => "2019-09-17",
                    ]
                ]
            ], 200);
    }

}
