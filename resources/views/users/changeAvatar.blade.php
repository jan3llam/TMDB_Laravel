@extends('layouts.main')


@section('content')
<form method="POST" action="{{route('users.changeAvatar')}}" enctype="multipart/form-data">
    @csrf
    <div class="mt-10 flex flex-col items-center justify-center">
      <div class="flex flex-col bg-gray-800 shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
        <div class="flex justify-center items-center content-center">
            @if(Auth::guard('user')->check())
              @if(isset(Auth::guard('user')->user()->avatar))
                <div class="md:mr-4 mt-3 md:mt-0">
                     <img src="{{Auth::guard('user')->user()->avatar}}" alt="avatar" class="rounded-full w-52 h-52">
                </div>
              @else
                <div class="md:mr-4 mt-3 md:mt-0">
                     <img src="{{url('img/noprofile.png')}}" alt="avatar" class="rounded-full w-52 h-52">
                </div>
              @endif
            @endif
        </div>
        <div class="flex flex-row justify-between mt-10">
            <div>
            	<input id="avatar" type="file" name="avatar" class="rounded-lg" multiple="false" accept="image/jpg, image/png, image/jpeg" />
            </div>
            @if(Auth::guard('user')->check())
              	@if(isset(Auth::guard('user')->user()->avatar))
              		<div>
		            	<button type="submit" class="bg-red-600 text-gray-900 rounded font-semibold px-3 py-2 hover:bg-red-700" name="remove">
		                   	<span>Remove</span>
		                </button>
		            </div>
		        @endif
		    @endif
        </div>
        <div class="flex justify-center mt-6">
            <button type="submit" class="w-40 bg-green-600 text-gray-900 rounded font-semibold px-4 py-2 hover:bg-green-700">
                <span>Change</span>
            </button>
        </div>
      </div>
    </div>
</form>
@endsection