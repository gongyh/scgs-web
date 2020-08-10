@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <ul class="nav nav-tabs">
        @if(Auth::user()->email == 'admin@123.com')
        <li class="nav-item">
          <a class="nav-link" href="/institutions/create">Add Institutions</a>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" href="/workspace/myLab">My Labs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/workspace/myProject">My Projects</a>
        </li>
      </ul>
    </div>
  </div>
</div>
@endsection
