@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
  <aside class="col-md-3 bg-light">
  {!! parsedown($nav) !!}
  </aside>
  <div class="col-md-9">
  {!! parsedown($md) !!}
  </div>
</div>
</div>
@endsection
