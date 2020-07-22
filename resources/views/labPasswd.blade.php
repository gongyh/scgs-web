@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="exampleInputEmail1">Lab Login</label>
          <input type="text" class="form-control" id="exampleInputLogin" name="labLogin">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" name="labPw">
          @isset($error)
          <small id="emailHelp" class="form-text text-danger">{{$error}}</small>
          @endisset
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
