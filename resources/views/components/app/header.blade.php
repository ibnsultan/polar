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
				<li class="dropdown pc-h-item">
					<a
						class="pc-head-link dropdown-toggle me-0"
						data-pc-toggle="dropdown"
						href="#"
						role="button"
						aria-haspopup="false"
						aria-expanded="false"
						>
					    <i class="fas fa-earth-africa text-xs"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end pc-h-dropdown lng-dropdown">
						<a href="#!" class="dropdown-item" data-lng="en">
						    <span>English<small>(UK)</small></span>
						</a>
						<a href="#!" class="dropdown-item" data-lng="fr">
						    <span>français<small>(French)</small></span>
						</a>
						<a href="#!" class="dropdown-item" data-lng="ro">
						    <span>Română<small>(Romanian)</small></span>
						</a>
						<a href="#!" class="dropdown-item" data-lng="cn">
						    <span>中国人<small>(Chinese)</small></span>
						</a>
					</div>
				</li>
				<li class="pc-h-item">
					<a href="#" class="pc-head-link me-0" data-pc-toggle="offcanvas" data-pc-target="#announcement" aria-controls="announcement">
						<i class="fas fa-bolt text-xs"></i>
					</a>
				</li>
				<li class="dropdown pc-h-item">
					<a
						class="pc-head-link dropdown-toggle me-0"
						data-pc-toggle="dropdown"
						href="javascript:void(0)"
						role="button"
						aria-haspopup="false"
						aria-expanded="false"
						>
						<i class="fas fa-bell text-xs"></i>
						<span id="notification-badge" class="badge bg-success-500 text-white rounded-full z-10 absolute right-0 top-0" style="display: none;">0</span>
					</a>
					<div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown p-2">
						<div class="dropdown-header flex items-center justify-between py-4 px-5">
							<h5 class="m-0">Notifications</h5>
							<a href="javascript:void(0)" class="btn btn-link btn-sm" id="mark-all-read-btn">Mark all read</a>
						</div>
						<div class="dropdown-body header-notification-scroll relative py-4 px-5" style="max-height: calc(100vh - 215px)" id="notifications-container">
							<div class="text-center py-4" id="notifications-loading">
								<div class="spinner-border spinner-border-sm" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<p class="mt-2 text-muted">Loading notifications...</p>
							</div>
							<div id="notifications-empty" style="display: none;" class="text-center py-4">
								<i class="fas fa-bell-slash text-muted mb-2" style="font-size: 2rem;"></i>
								<p class="text-muted">No notifications yet</p>
							</div>
							<div id="notifications-list"></div>
						</div>
						<div class="text-center py-2">
							<a href="javascript:void(0)" class="text-danger-500 hover:text-danger-600 focus:text-danger-600 active:text-danger-600" id="clear-all-notifications">
							Clear all Notifications
							</a>
						</div>
					</div>
				</li>

				<x-script>
					document.addEventListener('DOMContentLoaded', function() {
						const notificationBadge = document.getElementById('notification-badge');
						const notificationsContainer = document.getElementById('notifications-container');
						const notificationsLoading = document.getElementById('notifications-loading');
						const notificationsEmpty = document.getElementById('notifications-empty');
						const notificationsList = document.getElementById('notifications-list');
						const markAllReadBtn = document.getElementById('mark-all-read-btn');
						const clearAllBtn = document.getElementById('clear-all-notifications');

						// Load notifications when dropdown is opened
						const notificationDropdown = document.querySelector('.dropdown-notification').closest('.dropdown');
						let notificationsLoaded = false;

						notificationDropdown.addEventListener('show.bs.dropdown', function() {
							if (!notificationsLoaded) {
								loadNotifications();
								notificationsLoaded = true;
							}
						});

						// Load unread count on page load
						loadUnreadCount();
						loadNotifications();

						function loadNotifications() {
							showLoading();
							
							fetch('/app/notifications', {
								method: 'GET',
								headers: {
									'Content-Type': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
								}
							})
							.then(response => response.json())
							.then(data => {
								hideLoading();
								if (data.status && data.notifications) {
									displayNotifications(data.notifications);
									updateUnreadCount(data.unreadCount || 0);
								} else {
									showEmpty();
								}
							})
							.catch(error => {
								console.error('Error loading notifications:', error);
								hideLoading();
								showEmpty();
							});
						}

						function loadUnreadCount() {
							fetch('/app/notifications/unread-count', {
								method: 'GET',
								headers: {
									'Content-Type': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
								}
							})
							.then(response => response.json())
							.then(data => {
								if (data.status) {
									updateUnreadCount(data.unreadCount || 0);

									// hide mark notification as read button and clear notification button if no unread notifications
									if (data.unreadCount === 0) {
										markAllReadBtn.style.display = 'none';
										clearAllBtn.style.display = 'none';
									}
								}
							})
							.catch(error => {
								console.error('Error loading unread count:', error);
							});
						}

						function displayNotifications(notifications) {
							notificationsList.innerHTML = '';
							
							if (notifications.length === 0) {
								showEmpty();
								return;
							}

							notifications.forEach(notification => {
								const notificationElement = createNotificationElement(notification);
								notificationsList.appendChild(notificationElement);
							});
						}

						function createNotificationElement(notification) {
							const div = document.createElement('div');
							div.className = `card mb-2 ${notification.is_read ? 'opacity-60' : ''}`;
							div.dataset.notificationId = notification.id;
							
							const typeIcons = {
								'success': 'fas fa-check-circle text-success',
								'error': 'fas fa-exclamation-triangle text-danger',
								'warning': 'fas fa-exclamation-circle text-warning',
								'info': 'fas fa-info-circle text-primary'
							};
							
							const iconClass = notification.icon || typeIcons[notification.type] || 'fas fa-bell text-primary';
							const timeAgo = formatTimeAgo(new Date(notification.created_at));
							
							div.innerHTML = `
								<div class="card-body">
									<div class="flex gap-4">
										<div class="shrink-0">
											<i class="${iconClass} w-[22px] h-[22px]"></i>
										</div>
										<div class="grow">
											<span class="float-end text-sm text-muted">${timeAgo}</span>
											<h5 class="text-body mb-2">${notification.title}</h5>
											<p class="mb-0">${notification.message}</p>
											${notification.action_url ? `
												<div class="mt-2">
													<a href="${notification.action_url}" class="btn btn-sm btn-primary">
														${notification.action_text || 'View'}
													</a>
												</div>
											` : ''}
										</div>
									</div>
								</div>
							`;
							
							// Add click handler to mark as read
							div.addEventListener('click', function() {
								if (!notification.is_read) {
									markAsRead(notification.id, div);
								}
							});
							
							return div;
						}

						function markAsRead(notificationId, element) {
							fetch(`/app/notifications/${notificationId}/read`, {
								method: 'PATCH',
								headers: {
									'Content-Type': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
								}
							})
							.then(response => response.json())
							.then(data => {
								if (data.status) {
									element.classList.add('opacity-60');
									loadUnreadCount(); // Refresh unread count
								}
							})
							.catch(error => {
								console.error('Error marking notification as read:', error);
							});
						}

						function markAllAsRead() {
							fetch('/app/notifications/mark-all-read', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
								}
							})
							.then(response => response.json())
							.then(data => {
								if (data.status) {
									// Mark all notification elements as read
									const notificationElements = notificationsList.querySelectorAll('.card');
									notificationElements.forEach(el => el.classList.add('opacity-60'));
									updateUnreadCount(0);
								}
							})
							.catch(error => {
								console.error('Error marking all as read:', error);
							});
						}

						// clear only read notifications
						function clearOnlyReadNotifications() {
							fetch('/app/notifications/delete-all-read', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
								}
							})
							.then(response => response.json())
							.then(data => {
								if (data.status) {
									// Remove all read notification elements
									const notificationElements = notificationsList.querySelectorAll('.card');
									notificationElements.forEach(el => {
										if (el.classList.contains('opacity-60')) {
											el.remove();
										}
									});

									toast.success({message: `@lang('All read notifications have been cleared.')`});
								}
							})
							.catch(error => {
								console.error('Error clearing only read notifications:', error);
							});
						}

						function updateUnreadCount(count) {
							if (count > 0) {
								notificationBadge.textContent = count > 99 ? '99+' : count;
								notificationBadge.style.display = 'block';
							} else {
								notificationBadge.style.display = 'none';
							}
						}

						function showLoading() {
							notificationsLoading.style.display = 'block';
							notificationsEmpty.style.display = 'none';
							notificationsList.style.display = 'none';
						}

						function hideLoading() {
							notificationsLoading.style.display = 'none';
							notificationsList.style.display = 'block';
						}

						function showEmpty() {
							notificationsLoading.style.display = 'none';
							notificationsEmpty.style.display = 'block';
							notificationsList.style.display = 'none';
						}

						function formatTimeAgo(date) {
							const now = new Date();
							const diff = now - date;
							const minutes = Math.floor(diff / 60000);
							const hours = Math.floor(diff / 3600000);
							const days = Math.floor(diff / 86400000);
							
							if (minutes < 1) return 'Just now';
							if (minutes < 60) return `${minutes} min ago`;
							if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
							return `${days} day${days > 1 ? 's' : ''} ago`;
						}

						// Event listeners
						markAllReadBtn.addEventListener('click', markAllAsRead);
						
						clearAllBtn.addEventListener('click', function() {
							Swal.fire({
								title: 'Are you sure?',
								text: "This will clear all read notifications.",
								icon: 'warning',
								showCancelButton: true,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: 'Yes, clear them!'
							}).then((result) => {
								if (result.isConfirmed) {
									clearOnlyReadNotifications();
								}
							});
						});
					});
				</x-script>

				<li class="dropdown pc-h-item header-user-profile ml-3">
					<a
						class="pc-head-link dropdown-toggle arrow-none me-0"
						data-pc-toggle="dropdown"
						href="#"
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
                                            <i class="fa-regular fa-user-group mr-2"></i>
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

								@if(can('access_admin_panel'))
									<a href="{{ route('admin.dashboard') }}" class="dropdown-item" target="_blank">
										<span>
											<i class="fa-regular fa-cog mr-2"></i>
											<span>{{ __('Admin Dashboard') }}</span>
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
                                                    <i class="fa-solid fa-plus"></i>
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