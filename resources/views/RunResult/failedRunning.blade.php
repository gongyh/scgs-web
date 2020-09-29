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
      <div class="text-danger rem15">Error Message</div>
      <div class="bg-white rounded shadow-sm p-2 overflow-auto command_out">
        {{$nextflowLog}}
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3">
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
