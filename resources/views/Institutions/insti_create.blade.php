@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_lab_name" class="input_title">Institution Name</label>
          <input type="text" class="form-control" name="new_insti_name" id="new_insti_name">
        </div>
        @isset($error)
        <div class="text-danger">{{$error}}</div>
        @endisset
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
