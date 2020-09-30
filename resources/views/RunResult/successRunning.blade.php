@extends('layouts.app')

@section('content')
<div class="container-fluid">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <div class="bg-white p-3 rounded shadow">
        <div class="rem15 font-weight-bold font-italic text-dark border-bottom pb-2">Job Information</div>
        <div class="d-flex text-dark rem15 font-italic mt-2">
          <div class="mr-3">started : </div>
          <div class="text-success start_time">{{$started}}</div>
        </div>
        <div class="d-flex text-dark rem15 font-italic mt-2">
          <div class="mr-3">finished : </div>
          <div class="text-success finish_time">{{$finished}}</div>
        </div>
        <div class="d-flex rem15 font-italic mt-2 pb-2 border-bottom">
          <div class="mr-3">time period : </div>
          <div class="text-success Run_time">{{$period}}</div>
        </div>
        <div class="rem15 font-italic">command</div>
        <div class="rem1">{{$command}}</div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3">
      <div class="other-info">

      </div>

    </div>
    @endsection
