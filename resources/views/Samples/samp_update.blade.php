@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_sampleLabel">SampleLabel</label>
          <input type="text" class="form-control" name="new_sampleLabel" id="new_sampleLabel" value="{{$sample->sampleLabel}}">
        </div>

        <div class="form-group">
          <div>Choose a application</div>
          <select class="custom-select">
            <option selected>Choose a application</option>
            @foreach($applications as $application)
            <option value="{{$application->id}}">{{$application->name}}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="species">Species</label>
          <input type="text" class="form-control" name="species" id="species" value="{{$sample->species_id}}">
        </div>

        <div class="form-group">
          <label for="fileOne">File 1</label>
          <input type="text" class="form-control" name="fileOne" id="fileOne">
        </div>

        <div class="form-group">
          <label for="fileTwo">File 2</label>
          <input type="file" class="form-control" name="fileTwo" id="fileTwo">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
