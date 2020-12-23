@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-4">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="name">Project Name</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="name" id="name" value="{{$project->name}}">
        </div>
        <div class="form-group">
          <label for="doi">ProjectID</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="doi" id="doi" value="{{$project->doi}}">
        </div>
        <div class="form-group">
          <label for="type">Type</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="type" id="type" value="{{$project->type}}">
        </div>
        <div class="form-group">
          <div>
            <label for="collection_date">Collection Date</label><span class="text-danger font-weight-bold">*</span>
          </div>
          <input class="mt-1 datepicker_update" type="text" name="collection_date" placeholder="Choose Date" value={{$project->collection_date}}>
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
