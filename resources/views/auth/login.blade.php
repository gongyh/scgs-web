@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Login') }}</div>

          <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
              @csrf

              <div class="form-group row">
                <label for="account" class="col-sm-4 col-form-label text-md-right">
                  {{ __('Name or Email') }}
                            </label>

                  <div class="col-md-6">
                    <input id="account" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="account" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('account'))
                        <span class="help-block" style="color: #d73925;">
                            <strong>{{ $errors->first('account') }}</strong>
                        </span>
                    @endif

                  </div>
              </div>

              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
              </div>

              <div class="form-group row">
                <label for="captcha" class="col-md-4 col-form-label text-md-right">Captcha</label>

                <div class="col-md-6">
                  <div class="d-flex">
                    <input id="captcha" class="w-50 form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" name="captcha" required>

                    <img class="thumbnail captcha ml-2" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="click to get a new captcha">

                  </div>

                </div>
                @if($errors->has('captcha'))
                <div class="col-md-4"></div>
                <div class="col-md-6">
                  <span class="captcha-error">
                    <strong>{{$errors->first('captcha')}}</strong>
                  </span>
                </div>
                @endif

              </div>

              <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                      {{ __('Remember Me') }}
                                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                                </button>

                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                      {{ __('Forgot Your Password?') }}
                                    </a>
                      @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
