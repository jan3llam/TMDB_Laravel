<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movie App</title>
    <link href="/css/main.css" rel="stylesheet">
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <style type="text/css">
      @yield('style')
    </style>
  </head>
  <body class="font-sans bg-gray-900 text-white">
    <nav class="border-b border-gray-800">
      <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between px-4 py-6">
        <ul class="flex flex-col md:flex-row items-center">
          <li>
            <a href="{{route('movies.index')}}">
                  <svg class="w-32" viewBox="0 0 74 24" fill="none"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM35.568 7.047l2.557 7.219" fill="#fff"/>
                  </svg>
              </a>
          </li>
          <li class="md:ml-16 mt-3 md:mt-0">
              <a href="{{route('movies.index')}}" class="hover:text-gray-300">Movies</a>
          </li>
          <li class="md:ml-6 mt-3 md:mt-0">
              <a href="{{route('tv.index')}}" class="hover:text-gray-300">TV Shows</a>
          </li>
          <li class="md:ml-6 mt-3 md:mt-0">
              <a href="{{route('actors.index')}}" class="hover:text-gray-300">Actors</a>
          </li>
        </ul>
        <div class="flex flex-col md:flex-row items-center">
            @if(Auth::guard('user')->check())
              <div class="md:mr-8 mt-3 md:mt-0">
                  <a href="{{url('/logout')}}" class="hover:text-gray-300">Logout</a>
              </div>
            @else
              <div class="md:mr-8 mt-3 md:mt-0">
                  <a href="{{url('/login')}}" class="hover:text-gray-300">Sign in</a>
              </div>
            @endif
            <livewire:search-dropdown>
            @if(Auth::guard('user')->check())
              @if(isset(Auth::guard('user')->user()->avatar))
                <div class="md:ml-4 mt-3 md:mt-0">
                      <a href="#">
                        <img src="{{Auth::guard('user')->user()->avatar}}" alt="avatar" class="rounded-full w-8 h-8">
                      </a>
                </div>
              @else
                <div class="md:ml-4 mt-3 md:mt-0">
                      <a href="#">
                        <img src="{{url('img/noprofile.png')}}" alt="avatar" class="rounded-full w-8 h-8">
                      </a>
                </div>
              @endif
            @endif
        </div>
      </div>
    </nav>
    <div x-data="{isOpen:true}" x-show="isOpen" class="flex flex-col md:flex-row justify-center">
      @if(session()->has('success'))
        <div class="flex flex-col md:flex-row justify-between bg-green-100 border-l-4 border-green-500 rounded-b text-green-900 w-6/12 px-4 py-3 shadow-md" role="alert">
          <div>
            <div class="flex flex-col md:flex-row py-1">
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle" class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path>
              </svg>
              <p class="font-bold">{{session('success')}}</p>
            </div>
          </div>
          <div>
                <button
                    @click="isOpen = false"
                    class="text-3xl leading-none hover:text-gray-300">&times;
                </button>
            </div>
        </div>
      @elseif(session()->has('failure'))
        <div class="flex flex-col md:flex-row justify-between bg-red-100 border-l-4 border-red-500 rounded-b text-red-900 w-6/12 px-4 py-3 shadow-md" role="alert">
            <div>
              <div class="flex flex-col md:flex-row py-1">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path>
                </svg>
                <p class="font-bold">{{session('failure')}}</p>
              </div>
            </div>
            <div>
                <button
                    @click="isOpen = false"
                    class="text-3xl leading-none hover:text-gray-300">&times;
                </button>
            </div>
        </div>
      @elseif(session()->has('warning'))
        <div class="flex flex-col md:flex-row justify-between bg-yellow-100 border-l-4 border-yellow-500 rounded-b text-yellow-900 w-6/12 px-4 py-3 shadow-md" role="alert">
            <div>
              <div class="flex flex-col md:flex-row py-1">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="exclamation-triangle" class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <path fill="currentColor" d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path>
              </svg>
                <p class="font-bold">{{session('warning')}}</p>
              </div>
            </div>
            <div>
                <button
                    @click="isOpen = false"
                    class="text-3xl leading-none hover:text-gray-300">&times;
                </button>
            </div>         
        </div>
      @endif
    </div>
    @yield('content')
    @livewireScripts
    @yield('scripts')
  </body>
</html>