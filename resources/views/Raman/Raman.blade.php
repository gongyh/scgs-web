@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row middle-area">
    <!-- left column -->
    <!-- middle-area -->
    <div class=""></div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
          <li class="breadcrumb-item active" aria-current="page"><a href="/samples?projectID={{$projectID}}">{{$projectName}}</a></li>
          </li>
        </ol>
      </nav>
      <div class="container">
        <br/>
        <table class="table table-striped table-hover table-sm">
        <thead>
          <tr>
            <th scope="col">ID_Cell</th>
            <th scope="col">Timepoint</th>
          </tr>
        <thead>
        <tbody>
        @foreach ($spectra as $spectrum)
          <tr>
            <td>{{$spectrum->ID_Cell}}</td>
            <td>{{$spectrum->Timepoint}}</td>
          </tr>
        @endforeach
        </tbody>
        </table>
        {{ $spectra->appends(request()->input())->links() }}
      </div>
    </div>
    <!-- right-column -->
    <div class="right-column">
      <div class="other-info">

      </div>
    </div>
  </div>
</div>

@endsection

