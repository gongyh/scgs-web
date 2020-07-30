@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="choose_insti input_title">Choose a institution</div>
        <select class="custom-select selectInstitution" name="selectInstitution">
          <option selected>Choose a institution</option>

          @foreach($institutions as $institution)
          <option value="{{$institution->id}}">{{$institution->name}}</option>
          @endforeach
        </select>

        <div class="form-group">
          <label for="new_lab_name" class="input_title">Lab Name</label>
          <input type="text" class="form-control" name="new_lab_name" id="new_lab_name">
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
