@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <form method="post" action="">
        @csrf
        <div class="d-flex">
          <div class="col-md-6">
            <div class="choose_lab input_title">Choose a lab<span class="text-danger font-weight-bold">*</span></div>
            <select class="custom-select selectLab w-75" name="selectLab">
              @foreach($labs as $lab)
              <option value="{{$lab->id}}">{{$lab->name}}</option>
              @endforeach
            </select>

            @if($create_lab_msg)
            <div class="text-danger font-weight-bold create_lab_msg">You haven't have a lab yet , please create a lab first!</div>
            @endif

            <div class="form-group">
              <label for="new_proj_name">Project Name</label><span class="text-danger font-weight-bold">*</span>
              <input type="text" class="form-control w-75" name="new_proj_name" id="new_proj_name" value={{old('new_proj_name')?old('new_proj_name'):''}}>
            </div>
            <div class="form-group">
              <div>
                <label for="new_type">Type</label><span class="text-danger font-weight-bold"> (Choose a type)*</span>
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
                <input type="text" id="new_type" class="form-control" name="new_type">
              </div>
            </div>
            <div class="form-group">
              <div>
                <label for="new_collection_date">Collection Date</label><span class="text-danger font-weight-bold">*</span>
              </div>
              <div class="input-group date w-75" id="datetimepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker" name="new_collection_date">
                <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
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
              <div class="input-group date w-75" id="datetimepicker1" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="new_release_date">
                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                  <div class="input-group-text">
                    <i class="glyphicon glyphicon-calendar"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
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
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
