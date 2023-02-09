<div @click.away="isMenu=false" x-show="isMenu" x-transition.opacity id="default-sidebar" class="fixed md:top-14 md:right-16 top-72 items-center z-40 w-56 h-auto px-2 py-3 rounded-lg overflow-y-auto bg-gray-800">
        <ul class="space-y-2">
            <li>
                <a href="{{url('/myratings')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-600">
                    <i class="fa fa-star-half-o ml-3 text-2xl text-gray-500" aria-hidden="true"></i>
                    <span class="ml-3 text-sm text-white">My Ratings</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-600">
                    <i class="fa fa-user-circle ml-3 text-2xl text-gray-500" aria-hidden="true"></i>
                    <span class="ml-3 text-sm text-white">Change Avatar</span>
                </a>
            </li>
            <li>
                <a href="{{route('users.change')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-600">
                    <i class="fa fa-lock ml-3 text-gray-500" aria-hidden="true"></i>
                    <span class="ml-3 text-sm text-white">Change Password</span>
                </a>
            </li>
            <li>
                <a href="{{route('users.logout')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-600">
                    <i class="fa fa-sign-out ml-3 text-2xl text-gray-500" aria-hidden="true"></i>
                    <span class="ml-3 text-sm text-white">Logout</span>
                </a>
            </li>
        </ul>
</div>