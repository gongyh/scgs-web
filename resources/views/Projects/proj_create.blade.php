@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        @isset($labs)
        <div class="choose_lab input_title">Choose a lab</div>
        <select class="custom-select selectLab" name="selectLab">
          <option selected></option>
          @foreach($labs as $lab)
          <option value="{{$lab->id}}">{{$lab->name}}</option>
          @endforeach
        </select>
        @endisset

        <div class="form-group">
          <label for="new_proj_name">Project Name</label>
          <input type="text" class="form-control" name="new_proj_name" id="new_proj_name">
        </div>
        <div class="form-group">
          <label for="new_doi_num">ProjectID</label>
          <input type="text" class="form-control" name="new_doi_num" id="new_doi_num">
        </div>
        <div class="form-group">
          <label for="new_proj_desc">Description</label>
          <textarea class="form-control" name="new_proj_desc" id="new_proj_desc"></textarea>
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
