@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <form action=" " method="post">
        @csrf
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Institutions-edit</h5>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Institution Name<span class="text-danger">*</span></span>
              </div>
              <input type="text" class="form-control" name="name" value="{{$institution->name}}">
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
            <button type="commit" class="btn btn-primary">Commit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
