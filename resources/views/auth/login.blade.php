@extends('layouts.app')

@section('content')

@if(Session::has('message'))
    <p class="alert {{ Session::pull('alert-class', 'alert-info') }}">{{ Session::pull('message') }}</p>
@endif

<div class="col-md-4 col-md-offset-4 login nopadding">
    <h1 class="page-title">
        Login
    </h1>
    <div class="panel panel-default">
       <!-- <div class="panel-heading">Login</div> -->
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                    <div class="col-md-12">
                        <input type="text" class="form-control" name="username"  value="{{ old('text') }}" placeholder="Benutzername">

                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input type="password" class="form-control" name="password" placeholder="Passwort">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <div class="checkbox nopadding-top">
                            <input type="checkbox" name="remember" id="remember" /> 
                            <label for="remember">Angemeldet bleiben</label> 
                        </div>
                    </div>

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary pull-right">
                           Anmelden
                        </button>

                        <!--<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>-->
                    </div>
                </div>
            </form>
        </div>
       <!-- </div> -->
    </div>
</div>

@endsection
