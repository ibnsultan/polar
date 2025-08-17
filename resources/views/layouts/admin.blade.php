<!doctype html>
<html lang="en" class="{{ config('jetstream.theme.preset') }}">
	<head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' | ' : '' }} {{ config('app.name', 'Laravel') }}</title>
        <script> localStorage.setItem('layout', 'vertical'); </script>

		<!-- fonts -->
		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="/assets/fonts/fontawesome/all.min.css" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

		<!-- stylesheets -->
		<link rel="stylesheet" href="/vendor/toast/toast.min.css" />
		<link rel="stylesheet" href="/vendor/flatpickr/flatpickr.min.css" />
		<link rel="stylesheet" href="/assets/css/style.css" id="main-style-link" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])        
        @livewireStyles
		@stack('styles')
	</head>
    
	<body>
		<!-- [ Pre-loader ] start -->
		<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034] flex items-center justify-center">
			<div class="loader-track h-[5px] w-[300px] overflow-hidden bg-primary-500/40 relative">
				<div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 transition-[transform_0.2s_linear] origin-left animate-[2.1s_cubic-bezier(0.65,0.815,0.735,0.395)_0s_infinite_normal_none_running_loader-animate]"></div>
			</div>
		</div>

        <!-- admin sidebar -->
        <x-admin.sidebar />
        
        <!-- admin header -->
        <x-admin.header />

        <!-- Page Content -->
        {{ $slot }}

		<!-- admin footer -->
		<x-admin.footer />

		<!-- base plugins -->
		<script src="/assets/js/plugins/simplebar.min.js"></script>
		<script src="/assets/js/plugins/popper.min.js"></script>
		<script src="/assets/js/component.js"></script>
		<script src="/assets/js/theme.js"></script>

		<!-- other plugins -->
		<script src="/vendor/toast/toast.min.js"></script>
		<script src="/vendor/select/select.js"></script>
		<script src="/vendor/flatpickr/flatpickr.min.js"></script>
		<script src="/vendor/swal/sweetalert.min.js"></script>

		<!-- main script -->
		<script src="/assets/js/script.js"></script>
		<script src="/assets/js/app.js"></script>

		@stack('scripts')

		<script> layout_rtl_change('false'); </script>
        @livewireScripts

	</body>
</html>
