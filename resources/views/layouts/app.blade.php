<html lang="en">
	<head>
        <title> Vanguard Screening Solutions Inc., Login </title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('vendor/images/icons/apple-touch-icon-144-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('vendor/images/icons/apple-touch-icon-114-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('vendor/images/icons/apple-touch-icon-72-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" href="{{ asset('vendor/images/icons/apple-touch-icon-57-precomposed.png') }}">
    	<link rel="shortcut icon" href="{{ asset('vendor/images/vanguard-icon-white.png') }}">

		<script src="{{ asset('js/app.js') }}" defer></script>
        <style src="vue-notifyjs/themes/default.css"></style>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/css/login.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('vendor/css/app.css') }}">
        <style media="screen">
            [v-cloak] {
                display: none;
            }
        </style>
	</head>

    <img src="{{ asset('vendor/images/blurred-bg/blurred-bg-5.jpg') }}" class="login-img wow fadeIn" alt="">
    <div class="center-vertical" id="app">
        @yield('content')
    </div>

    <?php $moduleMessage = isset($assets['message']) ? json_encode($assets['message']) : "false" ?>

    <script>

    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>

    // define route
    var route = "{{ ($assets['route']) ?? false }}";
    if (! route) {
        console.warn('warning: "route" variable no assign value for ajax http call');
    }

    // site url of application
    var url = "{{ url('') }}";

    var moduleMessage = {!! $moduleMessage !!};
    if (! moduleMessage) {
        //popupNotifier('This message is for developer. "moduleMessage" variable no assign value. System may not be usable');
    }

    // get current url
    var currentUrl = url+route;

    </script>
    <!-- <script src="{{ asset('vendor/js/login.js') }}"></script>     -->
</html>
