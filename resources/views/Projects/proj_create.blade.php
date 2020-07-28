@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_proj_name">Project Name</label>
          <input type="text" class="form-control" name="new_proj_name" id="new_proj_name">
        </div>
        <div class="form-group">
          <label for="new_doi_num">DOI Number</label>
          <input type="text" class="form-control" name="new_doi_num" id="new_doi_num">
        </div>
        <div class="form-group">
          <label for="new_proj_desc">Description</label>
          <input type="text" class="form-control" name="new_proj_desc" id="new_proj_desc">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
