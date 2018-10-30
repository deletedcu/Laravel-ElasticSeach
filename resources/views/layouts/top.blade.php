<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="">
        <div class="col-xs-12 col-sm-3">
            <a class="nav-brand" href="/"><strong><img src="/img/logo_bgrnd_white.png" alt="Neptun logo"/></strong></a> 
        </div>
        <div class="col-xs-12 col-sm-2 col-md-3 session-flash"> 
            <div class="clearfix"></div>
            
            @if(Session::has('message'))
                <p class="alert {{ Session::pull('alert-class', 'alert-info') }}">{!! Session::pull('message') !!}</p>
            @elseif(Session::has('messageSecondary'))
                <p class="alert {{ session('alert-class secondary', 'alert-info') }}">{!! Session::pull('messageSecondary') !!}</p>
            @endif
            
            @if (isset($errors) && count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-xs-12 col-sm-7 col-md-6">
            <ul class="nav navbar-nav icons pull-right icons-top">
                <li><a href="/"><span class="nav-icon icon-home" title="Zur Startseite"></span><span class="white-bgrnd">Home</span></a></li>
                <li><a href="{{ url('benutzer/profil') }}" title="Mein Benutzerprofil"><span class="nav-icon icon-profil"></span><span class="white-bgrnd">Profil</span></a></li>
                <li><a href="{{ url('logout') }}" title="Abmelden"><span class="nav-icon icon-logout"></span><span class="white-bgrnd">Log out</span></a></li>
                <li><a href="{{ url('kontakt') }}" title="NEPTUN Kontaktieren"><span class="nav-icon icon-kontakt"></span><span class="white-bgrnd">Kontakt</span></a></li>
                <li>
                    {{ Form::open(['route'=>['suche.index'], 'method'=>'GET']) }}
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" name="parameter" placeholder="{{ trans('navigation.searchParameter') }}" required >
                        <!--<span class="input-group-btn">
                    <button type="submit" name="search" class="btn btn-default" title="Suche">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ action('SearchController@searchAdvanced') }}" class="btn btn-default" title="Erweiterte Suche ...">
                        <i class="fa fa-list"></i>
                    </a>
                </span> -->

                    </div>
                    {{ Form::close() }}

                </li>
            </ul>
        </div>
     </div>

    <!-- /.navbar-top-links -->
</nav>
    <div class="clearfix"></div>
