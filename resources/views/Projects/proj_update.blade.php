@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="name">Project Name</label>
          <input type="text" class="form-control" name="name" id="name" value="{{$project->name}}">
        </div>
        <div class="form-group">
          <label for="doi">ProjectID</label>
          <input type="text" class="form-control" name="doi" id="doi" value="{{$project->doi}}">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control" name="description" id="description">{{$project->description}}</textarea>
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
