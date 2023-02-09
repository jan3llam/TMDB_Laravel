@extends('layouts.main')


@section('content')
  <form method="POST" action="{{route('users.signup')}}" enctype="multipart/form-data">
    @csrf
    <div class="min-h-screen flex flex-col items-center justify-center">
      <div class="flex flex-col bg-gray-800 mt-10 shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
          <div class="flex justify-center items-center content-center">
              <svg class="w-32" viewBox="0 0 24 24" fill="none"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4zM35.568 7.047l2.557 7.219" fill="#fff"/>
              </svg>
          </div>
          <div class="relative mt-10 h-px bg-gray-800">
            <div class="absolute left-0 top-0 flex justify-center w-full -mt-2">
              <span class="bg-gray-800 px-4 text-xs text-white uppercase">Fill below data to create a new account</span>
            </div>
          </div>
          <div class="mt-10">
            <div class="flex flex-col mb-6">
                <label for="firstname" class="mb-1 text-xs sm:text-sm tracking-wide text-white">First name:</label>
                <div class="relative">
                  <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>
                    </svg>
                  </div>

                  <input id="firstname" type="text" name="firstname" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="First name" minlength="2" autocomplete="off" required />
                </div>
              </div>
              <div class="flex flex-col mb-6">
                <label for="lastname" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Last name:</label>
                <div class="relative">
                  <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M16,4c0-1.11,0.89-2,2-2s2,0.89,2,2s-0.89,2-2,2S16,5.11,16,4z M20,22v-6h2.5l-2.54-7.63C19.68,7.55,18.92,7,18.06,7h-0.12 c-0.86,0-1.63,0.55-1.9,1.37l-0.86,2.58C16.26,11.55,17,12.68,17,14v8H20z M12.5,11.5c0.83,0,1.5-0.67,1.5-1.5s-0.67-1.5-1.5-1.5 S11,9.17,11,10S11.67,11.5,12.5,11.5z M5.5,6c1.11,0,2-0.89,2-2s-0.89-2-2-2s-2,0.89-2,2S4.39,6,5.5,6z M7.5,22v-7H9V9 c0-1.1-0.9-2-2-2H4C2.9,7,2,7.9,2,9v6h1.5v7H7.5z M14,22v-4h1v-4c0-0.82-0.68-1.5-1.5-1.5h-2c-0.82,0-1.5,0.68-1.5,1.5v4h1v4H14z"/>
                    </svg>
                  </div>

                  <input id="lastname" type="text" name="lastname" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Last name" minlength="2" autocomplete="off" required />
                </div>
              </div>
              <div class="flex flex-col mb-6">
                <label for="username" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Username:</label>
                <div class="relative">
                  <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                  </div>

                  <input id="username" type="text" name="username" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Username" minlength="4" autocomplete="off" required />
                </div>
              </div>
              <div class="flex flex-col mb-6">
                <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Email:</label>
                <div class="relative">
                  <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                  </div>

                  <input id="email" type="email" name="email" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Email" autocomplete="off" required />
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

                  <input id="password" type="password" name="password" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Password" minlength="6" onchange='check_pass();' required />
                </div>
              </div>
              <div class="flex flex-col mb-6">
                <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Confirm Password:</label>
                <div class="relative">
                  <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                    <span>
                      <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                      </svg>
                    </span>
                  </div>

                  <input id="confirmPass" type="password" name="confirmPass" class="text-sm text-black sm:text-base bg-gray-100 placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-600" placeholder="Confirm Password" minlength="6" onchange='check_pass();' required />
                </div>
                <span id='alert'></span>
              </div>
              <div class="flex flex-col mb-6">
                <label for="avatar" class="mb-1 text-xs sm:text-sm tracking-wide text-white">Avatar:</label>
                <div>
                  <input id="avatar" type="file" name="avatar" class="rounded-lg" multiple="false" accept="image/jpg, image/png, image/jpeg" />
                </div>
              </div>
              <div class="flex w-full">
                <button type="submit" id="submit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-orange-500 hover:bg-orange-700 rounded py-2 w-full transition duration-150 ease-in">
                  <span class="mr-2 uppercase">Sign Up</span>
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


@section('scripts')

<script type="text/javascript">
  
  function check_pass() {
    if (document.getElementById('password').value == document.getElementById('confirmPass').value) {
        document.getElementById('alert').innerHTML='Matching!';
        document.getElementById('alert').style.color = "#16a34a";
        document.getElementById('submit').removeAttribute('disabled');
    } else {
        document.getElementById('alert').innerHTML='Not Matching!';
        document.getElementById('alert').style.color = "#dc2626";
        document.getElementById('submit').setAttribute('disabled',true);
    }
  }


</script>


@endsection