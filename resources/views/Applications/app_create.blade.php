@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_app_name" class="input_title">Application Name</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_app_name" id="new_app_name">
        </div>

        <div class="form-group">
          <label for="new_app_desc">Description</label><span class="text-danger">*</span>
          <textarea class="form-control" name="new_app_desc" id="new_app_desc"></textarea>
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
