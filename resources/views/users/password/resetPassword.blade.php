@extends('layouts.main')


@section('content')
  <form method="POST" action="{{ route('users.resetPass',$token)}}">
    @csrf
    <div class="mt-10 flex flex-col items-center justify-center">
      <div class="flex flex-col bg-gray-800 shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
        <div class="flex justify-center items-center content-center">
            <svg class="w-32" viewBox="0 0 24 24" fill="none"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM35.568 7.047l2.557 7.219 2.543-7." fill="#fff"/>
            </svg>
        </div>
        <div class="relative mt-10 h-px bg-gray-800">
          <div class="absolute left-0 top-0 flex justify-center w-full -mt-2">
            <span class="bg-gray-800 px-4 text-xs text-white uppercase">Please enter your new password..</span>
          </div>
        </div>
        <div class="mt-10">
            <div class="flex flex-col mb-6">
              <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-white">New Password</label>
              <div class="relative">
                <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                  <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>

                <input id="newPass" type="password" name="newPass" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" autocomplete="off" placeholder="New Password" required />
              </div>
            </div>
            <div class="flex w-full">
              <button type="submit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-orange-500 hover:bg-orange-700 rounded py-2 w-full transition duration-150 ease-in">
                <span class="mr-2 uppercase">Reset</span>
                <span>
                  <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </button>
            </div>
        </div>
      </div>
    </div>
  </form>
@endsection

