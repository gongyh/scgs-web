@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_sample_label">SampleLabel</label>
          <input type="text" class="form-control" name="new_sample_label" id="new_sample_label">
        </div>
        <div class="form-group">
          <label for="new_pair_ends">PairEnds</label>
          <input type="text" class="form-control" name="new_pair_ends" id="new_pair_ends">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
