<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title') | Silk Road</title>

	<!-- CSS -->
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
	@hasSection('no-content')
		@yield('no-content')
	@else
	<main>
		@include('includes.components.navbar')
        <div class="content">
         	@yield('content')
		</div>
		@include('includes.components.footer')
	</main>
	@endif
</body>
</html>