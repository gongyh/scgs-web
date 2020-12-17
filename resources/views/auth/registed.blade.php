@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    Activate link has send to {{$user->email}},please before {{$user->activity_expire}} to activate your count.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
