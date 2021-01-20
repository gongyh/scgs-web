@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-3">
      <form method="post" action="">
        @csrf
        <div class="choose_insti input_title">Choose a institution<span class="text-danger">*</span></div>
        <select class="custom-select selectInstitution" name="choose_a_institution">
          <option selected></option>

          @foreach($institutions as $institution)
          <option value="{{$institution->id}}">{{$institution->name}}</option>
          @endforeach
        </select>

        <div class="form-group">
          <label for="new_lab_name" class="input_title">Lab Name</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_lab_name" id="new_lab_name">
        </div>
        <!-- error message -->
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
              @endforeach
          </ul>
        </div>
        @endif

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
