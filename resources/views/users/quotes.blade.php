@extends('layouts.main')


@section('content')
	
	@if(request()->half == 'quote')
		@php $s=true @endphp
		@php $lbg='bg-gray-600' @endphp
		@php $rbg='' @endphp
	@else
		@php $s=0 @endphp
		@php $rbg='bg-gray-600' @endphp
		@php $lbg='' @endphp
	@endif
	<div x-data="{isOpen:{{$s}}}" class="flex flex-col w-full h-fit bg-gray-800">
		<div class="flex flex-row text-orange-400 text-center justify-between border-b">
			<div x-ref="quote" class="text-xl font-bold rounded-lg {{$lbg}} hover:bg-gray-500 hover:text-orange-500 mr-8 w-2/5">
				<button class="w-full" type="button" 
						@click="isOpen=true
								$refs.quote.classList.add('bg-gray-600')
								$refs.fav.classList.remove('bg-gray-600')">
					Quote of the day
				</button>
			</div>
			<div x-ref="fav" class="text-xl font-bold rounded-lg {{$rbg}} hover:bg-gray-500 ml-8 hover:text-orange-500 w-2/5">
				<button class="w-full" type="button" 
						@click="isOpen=false
								$refs.fav.classList.add('bg-gray-600')
								$refs.quote.classList.remove('bg-gray-600')
								@php $favs->setPath('/animeQuotes/favs') @endphp">
					Favorites
				</button>
			</div>
		</div>
		<div x-show="isOpen" x-transition.opacity>
			<div class="flex flex-col items-left">
				@if(isset($data))
				 <div class="mt-10 ml-4">
				 	<span class="text-orange-500 text-xl border-b border-orange-500">Anime: </span><p class="text-white text-base px-8" id="anime">{{$data['anime']}}</p>
				 </div>
				<div class="mt-10 ml-4">
					<span class="text-orange-500 text-xl border-b border-orange-500">Character: </span><p class="text-white text-base px-8" id="char">{{$data['character']}}</p>
				</div>
				<div class="mt-10 ml-4">
					<span class="text-orange-500 text-xl border-b border-orange-500">Quote: </span><p class="text-white text-base px-8" id="quote">{{$data['quote']}}</p>
				</div>		
				@else
				<div class="text-center mt-10">
					<p class="text-white">Press the button to generate a quote..</p>
				</div>		
				@endif
			</div>
			<div class="flex flex-row justify-center mt-4 py-2">
				<form method="POST" action="{{route('users.quote',['half'=>'quote'])}}">
				@csrf
					<button class="bg-green-600 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-green-700 mr-4" type="submit">Generate</button>
				</form>
				@if(isset($data))
					<form method="POST">
						<button id="heart" class="text-black text-2xl px-2 py-1 hover:text-red-600 ml-4" type="submit"><i class="fa fa-heart" aria-hidden="true"></i></button>
					</form>
				@endif
			</div>
		</div>
		<div class="relative overflow-x-auto" x-show="!isOpen" x-transition.opacity>
			<table class="w-full text-sm text-left text-white mt-1">
		        <thead class="text-base uppercase bg-gray-900">
		            <tr>
		                <th scope="col" class="px-6 py-3">
		                    Anime
		                </th>
		                <th scope="col" class="px-6 py-3">
		                    Character
		                </th>
		                <th scope="col" class="px-6 py-3">
		                    Quote
		                </th>
		                <th>
		                	
		                </th>
		            </tr>
		        </thead>
			    <tbody id="favorites" class="[&>*:nth-child(odd)]:bg-gray-700 [&>*:nth-child(even)]:bg-gray-800">
			            @if(count($favs)>0)
				        	@foreach($favs as $fav)
					            <tr class="border-b">
					                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
					                    {{$fav->anime}}
					                </th>
					                <td class="px-6 py-4">{{$fav->character}}</td>
					                <td class="px-6 py-4">{{$fav->quote}}</td>
					                <td class="px-6 py-4">
					                	<a class="ml-4 bg-red-600 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-red-700" href="/animeQuotes/delete/{{$fav->id}}">
				        					Delete
				        				</a>
				        			</td>
					            </tr>
					        @endforeach
					    @else
					    	<tr class="text-lg">
			                	<td id="empty">You have favorites..</td>
			            	</tr>
					    @endif
			    </tbody>
		    </table>
		   	<div class="flex justify-between mt-8 mb-2">
			    @if ($favs->hasPages())
			        @if (!$favs->onFirstPage())
			        	<a class="ml-4 bg-gray-800 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-white" href="{{$favs->previousPageUrl()}}">
			        		{!! __('pagination.previous') !!}
			        	</a>
			        @else
			        	<div></div>
			        @endif
			        @if ($favs->hasMorePages())
			        	<a class="mr-4 bg-gray-800 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-white" href="{{$favs->nextPageUrl()}}">
			        		{!! __('pagination.next') !!}
			        	</a>
			        @else
			        	<div></div>	                
			        @endif
			    @endif
			</div>
		</div>
	</div>



@endsection

@section('scripts')

<script>
	
	$("#heart").click(function(e){

		e.preventDefault();
		var anime = document.getElementById('anime').textContent;
		var char = document.getElementById('char').textContent;
		var quote = document.getElementById('quote').textContent;
		var empty = document.getElementById("empty");
		
		$.ajax({
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
	        type: 'post',
	        url: '/animeQuotes/quote/favorites',
	        data: {
	        	'anime':anime,
	        	'character':char,
	        	'quote':quote,
	        },
	        dataType: "json",
	        success: function (data) {	
			    $( "#favorites" ).prepend( "<tr class='border-b'><th scope='row' class='px-6 py-4 font-medium whitespace-nowrap'>"+data.anime+"</th><td class='px-6 py-4'>"+data.character+"</td><td class='px-6 py-4'>"+data.quote+"</td><td class='px-6 py-4'><a class='ml-4 bg-red-600 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-red-700' href='/animeQuotes/delete/"+data.id+"'>Delete</a></td></tr>" );
	        	document.getElementById('heart').classList.remove('text-black','hover:text-red-600');
	        	document.getElementById('heart').classList.add('text-red-600');
	        	document.getElementById('heart').setAttribute('disabled','true');
	        	if(empty != null){
	        		empty.textContent='';
	        	}
	        },
    	});


	});

</script>


@endsection