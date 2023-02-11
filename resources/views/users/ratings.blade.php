@extends('layouts.main')


@section('content')
	
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left text-white">
        <thead class="text-sm uppercase bg-gray-900">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Media Type
                </th>
                <th scope="col" class="px-6 py-3">
                    Value
                </th>
            </tr>
        </thead>
        <tbody class="[&>*:nth-child(odd)]:bg-gray-700 [&>*:nth-child(even)]:bg-gray-800">
            @if(count($ratings)>0)
	        	@foreach($ratings as $rating)
		            <tr class="bg-white border-b">
		                <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
		                    <a href="{{$rating['linkToPage']}}">{{$rating['title']}}</a>
		                </th>
		                <td class="px-6 py-4">{{$rating['media_type']}}</td>
		                <td class="px-6 py-4">{{$rating['value']}}</td>
		            </tr>
		        @endforeach
		    @else
		    	<tr class="text-lg">
                	<td>You have no ratings to show..</td>
            	</tr>
		    @endif
        </tbody>
    </table>
    <div class="flex justify-between mt-8">
            @if ($page>1)
                <a class="ml-4 bg-gray-800 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-white" href="/myratings/{{$page-1}}">Previous</a>
            @else
                <div></div>
            @endif
            @if ($page==1)
                <a class="mr-4 bg-gray-800 border-solid border-white rounded-lg px-3 py-2 hover:text-gray-900 hover:bg-white" href="/myratings/{{$page+1}}">Next</a>
            @else
                <div></div>
            @endif
    </div>
</div>
@endsection