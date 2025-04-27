<!doctype html>
<html lang="en" class="{{ config('jetstream.theme.preset') }}" data-pc-sidebar-caption="false">
	<head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
		<link rel="icon" href="/assets/images/favicon.svg" type="image/x-icon" />
        
		<link rel="stylesheet" href="/assets/fonts/phosphor/duotone/style.css" />
		<link rel="stylesheet" href="/assets/fonts/fontawesome/all.min.css" />

		<style>
			[data-pc-layout="compact"] .logo-lg {
				max-width: 60px !important;
			}
		</style>

        <script>
            localStorage.setItem('layout', '{{ config('jetstream.theme.layout') }}');
        </script>
        
		<link rel="stylesheet" href="/assets/css/style.css" id="main-style-link" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
	</head>
    
	<body>
		<!-- [ Pre-loader ] start -->
		<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
			<div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0 bg-primary-500/10">
				<div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 transition-[transform_0.2s_linear] origin-left animate-[2.1s_cubic-bezier(0.65,0.815,0.735,0.395)_0s_infinite_normal_none_running_loader-animate]"></div>
			</div>
		</div>

        <!-- sidebar -->
        <x-app.sidebar />
        <x-app.header />

        <!-- Page Content -->
        {{ $slot }}

		<!-- page footer -->
		<x-app.footer />
        
        <x-app.canvas.announcements />

		<script src="/assets/js/plugins/simplebar.min.js"></script>
		<script src="/assets/js/plugins/popper.min.js"></script>
		<script src="/assets/js/icon/custom-font.js"></script>
		<script src="/assets/js/component.js"></script>
		<script src="/assets/js/theme.js"></script>
		<script src="/assets/js/script.js"></script>
        
		<script>
			layout_rtl_change('false');
		</script>
        
        @livewireScripts

	</body>
</html>