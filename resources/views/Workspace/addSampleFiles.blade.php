@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Sample Files</li>
        </ol>
      </nav>
      <label for="addSampleFiles" class="control-label"></label>
      <input type="file" name="addSampleFiles" id="addSampleFiles" multiple>
    </div>
  </div>
</div>
@endsection
