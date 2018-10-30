<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Neptun intranet login</title>

    <!-- Fonts -->
   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link rel="shortcut icon" href="/img/favicon.png">
        <title>@yield("title",'NEPTUN intranet')</title>
        
        {!! Html::style(elixir('css/style.css')) !!}


</head>
<body id="app-layout">
    <nav class="navbar" role="navigation" style="margin-bottom: 0">
        <div class="col-xs-12">
            <a class="nav-brand" href="{{ url('/') }}"><strong><img src="/img/logo_bgrnd_white.png" alt="Neptun logo"/></strong></a>
        </div>
    </nav>
    <div class="clearfix"></div>
    <div class="content-wrap">
        @yield('content')
    </div>
   <!-- JS files - start -->
          @yield('preScript')
          
    {!! Html::script(elixir('js/script.js')) !!}
    <!-- JS files - end -->
        @yield('script')

    <div class="clearfix"></div>

     @include('layouts.footer')

</body>
</html>
