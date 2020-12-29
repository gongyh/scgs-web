@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Sample Files</li>
        </ol>
      </nav>
      <div class="rem15">Files In Your Dictionary</div>
      @foreach($fileList as $file)
      <div>
        <span class="badge badge-pill badge-success">Prepared</span>
        <span class="rem1">{{$file}}</span>
      </div>
      @endforeach
      <label for="addSampleFiles" class="control-label"></label>
      <input type="file" name="addSampleFiles" id="addSampleFiles" multiple>
    </div>
  </div>
</div>
@endsection
