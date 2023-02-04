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
  </head>
  <body class="font-sans bg-gray-900 text-white">
    <nav class="border-b border-gray-800">
      <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between px-4 py-6">
        <ul class="flex flex-col md:flex-row items-center">
          <li>
            <a href="{{route('movies.index')}}">
                  <svg class="w-32" viewBox="0 0 96 24" fill="none"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM35.568 7.047l2.557 7.219 2.543-7.22h2.693V17h-2.057v-2.72l.205-4.697L38.822 17h-1.408l-2.68-7.41.206 4.69V17h-2.051V7.047h2.68zm9.147 6.186c0-.733.141-1.387.424-1.962a3.108 3.108 0 011.216-1.333c.534-.314 1.151-.471 1.853-.471.998 0 1.812.305 2.44.916.634.61.987 1.44 1.06 2.488l.014.506c0 1.135-.317 2.046-.95 2.734-.634.684-1.484 1.026-2.55 1.026-1.067 0-1.919-.342-2.557-1.026-.633-.683-.95-1.613-.95-2.789v-.089zm1.975.144c0 .702.133 1.24.397 1.613.264.37.642.554 1.135.554.478 0 .852-.182 1.12-.547.27-.37.404-.957.404-1.764 0-.688-.134-1.221-.403-1.6-.27-.377-.647-.567-1.135-.567-.483 0-.857.19-1.121.568-.264.373-.397.954-.397 1.743zm8.908 1.21l1.374-4.983h2.064L56.541 17h-1.887L52.16 9.604h2.065l1.374 4.983zM61.996 17h-1.982V9.604h1.982V17zm-2.099-9.31c0-.297.098-.54.294-.732.2-.191.472-.287.814-.287.337 0 .606.096.806.287.201.191.301.435.301.731 0 .301-.102.547-.307.739-.2.191-.467.287-.8.287s-.602-.096-.807-.287a.975.975 0 01-.3-.739zm7.137 9.447c-1.085 0-1.969-.333-2.652-.998-.68-.666-1.019-1.552-1.019-2.66v-.19c0-.744.144-1.407.43-1.99a3.143 3.143 0 011.218-1.354c.528-.319 1.13-.478 1.804-.478 1.012 0 1.807.319 2.386.957.584.638.875 1.543.875 2.714v.806h-4.71c.064.483.255.87.574 1.162.324.292.732.438 1.224.438.761 0 1.356-.276 1.784-.827l.97 1.087a2.99 2.99 0 01-1.202.984 3.98 3.98 0 01-1.682.349zm-.225-6.07c-.392 0-.711.132-.957.396-.242.264-.397.643-.465 1.135h2.748v-.158c-.01-.437-.128-.774-.356-1.011-.228-.242-.551-.363-.97-.363zm10.144 3.882h-3.596L72.674" fill="#fff"/>
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
                        <img src="img/noprofile.png" alt="avatar" class="rounded-full w-8 h-8">
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