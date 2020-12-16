@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Workspace</li>
        </ol>
      </nav>
      <br/>
      @include('components.workspace_nav')
    </div>
  </div>
</div>
@endsection
