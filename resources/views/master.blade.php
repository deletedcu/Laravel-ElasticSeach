<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="shortcut icon" href="/img/NeptunFavicon.png">
        <title>@yield("title",'NEPTUN Intranet')</title>
    
        {!! Html::style(elixir('css/style.css')) !!}
                <!-- CSS files - end -->
    
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <meta name="csrf-token" content="{{csrf_token()}}"/>
    </head>

    <body class="@yield('bodyClass')">
        <!-- loader div-->
        <!-- This will be used later so don't remove it-->
        <!--
        <div id="page-loader">
      	    <div class="centered-columns">
          	    <div class="centered-column">
              	    <span class="fa fa-spin fa-spinner"></span>
                </div>
            </div>
        </div>
        -->
        <!--End loader div-->
   
    <div class="page-title"> 
        <div class="">
            <div class="col-xs-12 col-md-12 ">
                <div class="fixed-row @yield('page-title-class')">
                    <div class="fixed-position ">
                        <h1 class="page-title"><span class="white-bgrnd inline-block">@yield('page-title')</span></h1>
                    </div>
                </div>
            </div>
        </div>
    <div class="clearfix"></div>
        
    </div>

    <div id="wrapper">
        
        <!-- Header search -->
        @include('layouts.top')
    
        <!-- Sidebar navigation -->
        @include('layouts.sidebar')
    
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid first">
                <!-- Backend content -->
                @yield('content')
    
                @yield('modal')
                
                <!--search results-->
                @yield('searchResults')<!-- End search results-->
            
            </div> <!-- End container fluid-->
            
            @include('layouts.footer') 
        </div> <!-- End #wrapper-->
        
        <!-- Right sidebar -->
       
        
        
        <!-- Return to Top -->
        <a href="#" id="return-to-top"><i class="fa fa-chevron-up"></i></a>
    </div>
 
    
    <!-- JS files - start -->
    @yield('preScript')
    <!--<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>-->
    {!! Html::script(elixir('js/script.js')) !!}
    @yield('afterScript')
    <!-- JS files - end -->
    
    @yield('script')
       
    </body>
</html>
