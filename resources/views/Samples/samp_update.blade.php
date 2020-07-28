@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_sampleLabel">SampleLabel</label>
          <input type="text" class="form-control" name="new_sampleLabel" id="new_sampleLabel" value="{{$sample->sampleLabel}}">
        </div>
        <div class="form-group">
          <label for="new_pairEnds">PairEnds</label>
          <input type="text" class="form-control" name="new_pairEnds" id="new_pairEnds" value="{{$sample->pairends}}">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
