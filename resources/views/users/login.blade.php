@extends('layouts.main')


@section('content')
  <form method="POST" action="{{ route('users.login')}}">
    @csrf
    <div class="min-h-screen flex flex-col items-center justify-center">
      <div class="flex flex-col bg-gray-800 shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
        <div class="flex justify-center items-center content-center">
            <svg class="w-32" viewBox="0 0 24 24" fill="none"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM35.568 7.047l2.557 7.219 2.543-7." fill="#fff"/>
            </svg>
        </div>
        <div class="relative mt-10 h-px bg-gray-800">
          <div class="absolute left-0 top-0 flex justify-center w-full -mt-2">
            <span class="bg-gray-800 px-4 text-xs text-white uppercase">Login With Email or Username</span>
          </div>
        </div>
        <div class="mt-10">
            <div class="flex flex-col mb-6">
              <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Email/Username:</label>
              <div class="relative">
                <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                  <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                  </svg>
                </div>

                <input id="email" type="text" name="email" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Email/Username" autocomplete="off" />
              </div>
            </div>
            <div class="flex flex-col mb-6">
              <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Password:</label>
              <div class="relative">
                <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                  <span>
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </span>
                </div>

                <input id="password" type="password" name="password" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Password" minlength="4" required />
              </div>
            </div>

            <div class="flex items-center mb-6 -mt-4">
              <div class="flex ml-auto">
                <a href="{{url('/user/forget-password')}}" class="inline-flex text-xs sm:text-sm text-white hover:text-orange-500">Forgot Your Password?</a>
              </div>
            </div>
            <div class="flex w-full">
              <button type="submit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-orange-500 hover:bg-orange-700 rounded py-2 w-full transition duration-150 ease-in">
                <span class="mr-2 uppercase">Login</span>
                <span>
                  <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </button>
            </div>
        </div>
        <div class="flex justify-center items-center mt-6">
          <a href="{{url('/signup')}}" class="inline-flex items-center font-bold text-white hover:text-orange-500 text-xs text-center">
            <span>
              <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </span>
            <span class="ml-2">You don't have an account?</span>
          </a>
        </div>
      </div>
    </div>
  </form>
@endsection