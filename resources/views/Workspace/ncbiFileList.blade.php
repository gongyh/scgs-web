@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">NCBI Files List</li>
        </ol>
      </nav>
      <div class="rem15">Files In Public Directionary</div>
      @foreach($ncbi_files as $ncbi_file)
      <div>
        <span class="badge badge-pill badge-success">Prepared</span>
        <span class="rem1">{{$ncbi_file}}</span>
      </div>
      @endforeach

    </div>
  </div>
</div>
@endsection
