@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_sample_label">SampleLabel</label>
          <input type="text" class="form-control" name="new_sample_label" id="new_sample_label">
        </div>

        <div class="form-group">
          <label for="select_application">Choose a application</label>
          <select class="custom-select" name="select_application" id="select_application">
            <option selected>Choose a application</option>
            @foreach($applications as $application)
            <option value="{{$application->id}}">{{$application->name}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="new_species">Species</label>
          <input type="text" class="form-control" name="new_species" id="new_species">
        </div>

        <div class="form-group">
          <label for="new_fileOne">File 1</label>
          <input type="text" class="form-control" name="new_fileOne" id="new_fileOne">
        </div>

        <div class="form-group">
          <label for="new_fileTwo">File 2</label>
          <input type="text" class="form-control" name="new_fileTwo" id="new_fileTwo">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
