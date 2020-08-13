@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_projName">Project Name</label>
          <input type="text" class="form-control" name="new_projName" id="new_projName" value="{{$project->name}}">
        </div>
        <div class="form-group">
          <label for="new_doiNum">DOI Number</label>
          <input type="text" class="form-control" name="new_doiNum" id="new_doiNum" value="{{$project->doi}}">
        </div>
        <div class="form-group">
          <label for="new_projDesc">Description</label>
          <textarea class="form-control" name="new_projDesc" id="new_projDesc">{{$project->description}}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
