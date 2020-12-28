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
        @isset($labs)
        <div class="choose_lab input_title">Choose a lab<span class="text-danger font-weight-bold">*</span></div>
        <select class="custom-select selectLab w-75" name="selectLab">
          <option></option>
          @foreach($labs as $lab)
          <option value="{{$lab->id}}">{{$lab->name}}</option>
          @endforeach
        </select>
        @endisset

        <div class="form-group">
          <label for="new_proj_name">Project Name</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="new_proj_name" id="new_proj_name" value={{old('new_proj_name')?old('new_proj_name'):''}}>
        </div>
        <div class="form-group">
          <label for="new_project_id">ProjectID</label><span class="text-danger font-weight-bold">*</span>
          <input type="text" class="form-control w-75" name="new_project_id" id="new_project_id" value={{old('new_project_id')?old('new_project_id'):''}}>
        </div>
        <div class="form-group">
          <div>
            <label for="new_type">Type</label><span class="text-danger font-weight-bold">(Choose a type)*</span>
          </div>
          <select id="type">
            <option value=""></option>
            @foreach($types as $type)
            <option value={{$type}}>{{$type}}</option>
            @endforeach
          </select>
          <div class="text-danger font-weight-bold mt-2">Or input below</div>
          <input type="text" class="form-control w-75" name="new_type" id="new_type" value={{old('new_type')?old('new_type'):''}}>
        </div>
        <div class="form-group">
          <div>
            <label for="new_collection_date">Collection Date</label><span class="text-danger font-weight-bold">*</span>
          </div>
          <div class="form-group">
            <div class="form-group">
              <div class="input-group date w-75" id="datetimepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker" />
                <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="glyphicon glyphicon-calendar"></i></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="new_location">Location</label><span class="text-danger font-weight-bold"> (Country:City)*</span>
          <input type="text" class="form-control w-75" name="new_location" id="new_location" value={{old('new_location')?old('new_location'):''}}>
        </div>
        <div class="form-group">
          <label for="new_proj_desc">Description</label><span class="text-danger font-weight-bold">*</span>
          <textarea class="form-control w-75" name="new_proj_desc" id="new_proj_desc">{{old('new_proj_desc')?old('new_proj_desc'):''}}</textarea>
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

        @isset($pi_error)
        <div class="alert alert-danger">
          <ul>
            <li>{{ $pi_error }}</li>
          </ul>
        </div>
        @endisset
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
