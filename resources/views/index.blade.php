@extends('layouts.main')

@section('content')
    @guest
        <div class="flex-center position-ref full-height">
            <div class="container">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="off" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
            <form method="POST" action="{{ action('MainController@search') }}" role="search">
                @csrf
                <div class="input-group">
                    <input id="key" type="text" name="key" class="form-control" placeholder="Enter EACODE..." aria-label="key" aria-describedby="basic-addon2" autocomplete="off" required autofocus>
                    <button type="submit" name='submit' value='search' class="btn"><i class="fas fa-search fa-sm text-white-50"></i>Search</button>
                </div>
            </form>
            <div class="animation">
                <h5 class="line-1 anim-typewriter">Start search by typing the eacode......</h5>
            </div>
        @endguest
@endsection