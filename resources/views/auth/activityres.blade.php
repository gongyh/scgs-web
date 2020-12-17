@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Activity Result') }}</div>

                <div class="card-body">
                    @if($res === false)
                        The activate link is invalid, please try to <a href="{{route('register')}}">Register>> again</a>
                    @else
                        Your account has been activated, Please <a href="{{route('login')}}">Login>></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
