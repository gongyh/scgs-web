@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_insti_name" class="input_title">Institution Name</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_institution_name" id="new_insti_name">
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
