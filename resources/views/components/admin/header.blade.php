<header class="pc-header">
	<div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow">
		<!-- [Mobile Media Block] start -->
		<div class="me-auto pc-mob-drp">
			<ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
				<!-- ======= Menu collapse Icon ===== -->
				<li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
					<a href="javascript:void(0)" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
					    <i class="fa-solid fa-bars-staggered"></i>
					</a>
				</li>
				<li class="pc-h-item pc-sidebar-popup lg:hidden">
					<a href="javascript:void(0)" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
					    <i class="fa-solid fa-bars-staggered text-2xl leading-none"></i>
					</a>
				</li>
				<li class="pc-h-item max-md:hidden md:inline-flex">
					<h2>
				</li>
			</ul>
		</div>
		<!-- [Mobile Media Block end] -->
		<div class="ms-auto">
			<ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
				<li class="dropdown pc-h-item">
					<a
						class="pc-head-link dropdown-toggle me-0 -mt-2"
						data-pc-toggle="dropdown"
						href="#"
						role="button"
						aria-haspopup="false"
						aria-expanded="false"
						>
                        <i class="fas fa-moon-stars"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
						<a href="#!" class="dropdown-item" onclick="layout_change('dark')">
							<svg class="pc-icon w-[18px] h-[18px]">
								<use xlink:href="#custom-moon"></use>
							</svg>
							<span>Dark</span>
						</a>
						<a href="#!" class="dropdown-item" onclick="layout_change('light')">
							<svg class="pc-icon w-[18px] h-[18px]">
								<use xlink:href="#custom-sun-1"></use>
							</svg>
							<span>Light</span>
						</a>
						<a href="#!" class="dropdown-item" onclick="layout_change_default()">
							<svg class="pc-icon w-[18px] h-[18px]">
								<use xlink:href="#custom-setting-2"></use>
							</svg>
							<span>Default</span>
						</a>
					</div>
				</li>
				<li class="pc-h-item">
					<a href="javascript:void(0)" class="pc-head-link me-0" data-pc-toggle="offcanvas" data-pc-target="#activities" aria-controls="activities">
						<i class="fas fa-bolt text-xs"></i>
					</a>
				</li>
				<li class="dropdown pc-h-item header-user-profile ml-3">
					<a
						class="pc-head-link dropdown-toggle arrow-none me-0"
						data-pc-toggle="dropdown"
						href="javascript:void(0)"
						role="button"
						aria-haspopup="false"
						data-pc-auto-close="outside"
						aria-expanded="false"
						>
					<img src="{{ user()->profile_photo_url }}" alt="user-image" class="user-avtar w-10 h-10 rounded-full" />
					</a>
					<div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2">
						<div class="dropdown-body py-4 px-5">
							<div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
								<div class="flex mb-1 items-center">
									<div class="shrink-0">
										<img src="{{ user()->profile_photo_url }}" alt="user-image" class="w-10 rounded-full" />
									</div>
									<div class="grow ms-3">
										<h6 class="mb-1">{{ user()->name }} 🖖</h6>
										<span>{{ user()->email }}</span>
									</div>
								</div>
								<hr class="border-secondary-500/10 my-4" />
                                
								<a href="{{ route('profile.show') }}" class="dropdown-item">
									<span>
										<i class="fa-regular fa-user mr-2"></i>
										<span>{{ __('Account Settings') }}</span>
									</span>
								</a>

                                <!-- team setting -->
                                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                    <a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="dropdown-item">
                                        <span>
                                            <i class="fa-regular fa-gear mr-2"></i>
                                            <span>{{ __('Team Management') }}</span>
                                        </span>
                                    </a>
                                @endif
                                
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <a href="{{ route('api-tokens.index') }}" class="dropdown-item">
                                        <span>
                                            <i class="fa-regular fa-key mr-2"></i>
                                            <span>{{ __('APIs and Integrations') }}</span>
                                        </span>
                                    </a>
                                @endif

								<hr class="border-secondary-500/10 my-4" />
                                
                                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                    <p class="text-span mb-3">{{ __('Teams') }}</p>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-switchable-team :team="$team" />
                                    @endforeach
                                    
                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <a href="{{ route('teams.create') }}" class="dropdown-item">
                                            <span> {{ __('Create New Team') }}</span>
                                            <div dir="ltr"
                                                class="flex -space-x-2 overflow-hidden *:flex *:items-center *:justify-center *:rounded-full *:w-[30px] *:h-[30px] hover:*:z-10 *:border-2 *:border-white"
                                                >
                                                <span class="avtar bg-primary text-white">
                                                    <svg class="pc-icon m-0">
                                                        <use xlink:href="#custom-add-outline"></use>
                                                    </svg>
                                                </span>
                                            </div>
                                        </a>
                                    @endcan
                                    <hr class="border-secondary-500/10 my-4" />
                                @endif

								<div class="grid mb-3">
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <button class="btn btn-danger flex items-center justify-center w-full" @click.prevent="$root.submit();">
                                            <svg class="pc-icon me-2 w-[22px] h-[22px]">
                                                <use xlink:href="#custom-logout-1-outline"></use>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</header>