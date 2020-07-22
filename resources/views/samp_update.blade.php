@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action=" " method="post">
        @csrf
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Samples-edit</h5>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Sample Name</span>
              </div>
              <input type="text" class="form-control" name="new-sampname" value="{{$sample->sampleLabel}}">
            </div>
            <button type="commit" class="btn btn-primary">Commit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
