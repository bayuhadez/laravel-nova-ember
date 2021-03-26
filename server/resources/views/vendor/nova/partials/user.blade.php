<dropdown-trigger class="h-9 flex items-center" slot-scope="{toggle}" :handle-click="toggle">
    <img src="https://secure.gravatar.com/avatar/{{ md5(auth()->user()->email) }}?size=512" class="rounded-full w-8 h-8 mr-3"/>

    <span class="text-90">
        {{ auth()->user()->name }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
		@if (\App\Services\LoginService::getSessionLoginFromClientApp())
			<li>
				<a href="{{ route('nova.logoutThenGoToFrontPage') }}" class="block no-underline text-90 hover:bg-30 p-3">
					{{ __('Front Page') }}
				</a>
			</li>
			<li>
				<a href="{{ route('nova.logoutThenGoToClientLogoutPage') }}" class="block no-underline text-90 hover:bg-30 p-3">
					{{ __('Logout') }}
				</a>
			</li>
		@else
			<li>
				<a href="{{ route('nova.logout') }}" class="block no-underline text-90 hover:bg-30 p-3">
					{{ __('Logout') }}
				</a>
			</li>
		@endif
    </ul>
</dropdown-menu>
