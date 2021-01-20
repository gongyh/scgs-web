@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="name">Project Name</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="name" id="name" value="{{$project->name}}">
        </div>
        <div class="form-group">
          <div>
            <label for="new_type">Type</label><span class="text-danger font-weight-bold">(Choose a type)*</span>
          </div>
          <div class="input-group w-75">
            <div class="input-group-btn">
              <button class="btn btn-default border dropdown-toggle" data-toggle="dropdown">
                Types <span class="caret"></span>
              </button>
              <ul class="dropdown-menu types">
                @foreach($types as $type)
                <li class="type">{{$type}}</li>
                @endforeach
              </ul>
            </div>
            <input type="text" id="new_type" class="form-control" name="type" value="{{$project->type}}">
          </div>
        </div>
        <div class="form-group">
          <div>
            <label for="new_collection_date">Collection Date</label><span class="text-danger font-weight-bold">*</span>
          </div>
          <div class="input-group date w-75" id="datetimepicker3" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker3" name="collection_date" value="{{$project->collection_date}}">
            <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
              <div class="input-group-text">
                <i class="glyphicon glyphicon-calendar"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div>
            <label for="new_release_date">Release Date</label><span class="text-danger font-weight-bold">*</span>
          </div>
          <div class="input-group date w-75" id="datetimepicker4" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4" name="release_date" value="{{$project->release_date}}">
            <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
              <div class="input-group-text">
                <i class="glyphicon glyphicon-calendar"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="location">Location</label><span class="text-danger font-weight-bold"> (Country:City)*</span>
          <input type="text" class="form-control w-75" name="location" id="location" value="{{$project->location}}">
        </div>
        <div class="form-group">
          <label for="description">Description</label><span class="text-danger font-weight-bold">*</span>
          <textarea class="form-control w-75" name="description" id="description">{{$project->description}}</textarea>
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
