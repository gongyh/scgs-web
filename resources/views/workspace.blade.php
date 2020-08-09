@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <ul class="nav nav-tabs">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Mine</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="/myLab">My Labs</a>
            <a class="dropdown-item" href="/myProject">My Projects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Other Operations</a>
        </li>
      </ul>
    </div>
  </div>
</div>
@endsection
