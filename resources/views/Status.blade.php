@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-sm-3 left-column">
      <div class="list-group list">
        <a class="list-group-item" href="/institutions">Institutions</a>
        <a class="list-group-item" href="/labs">Labs</a>
        <a class="list-group-item" href="/projects">Projects</a>
        <a class="list-group-item" href="/samples">Samples</a>
        <a class="list-group-item" href="/status">Status</a>
      </div>
    </div>
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome to SCGS-Web!
      </div>
      <div class="result">
        <div class="result-info">
          Status
        </div>
        <div class="result-detail">
          <div class="status">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead class="table-dark">
                  <tr>
                    <td scope="col" class="table-header">ID</td>
                    <td scope="col" class="table-header">Runing samples</td>
                  </tr>
                </thead>
                @foreach ($status as $status_item)
                <tr>
                  <th scope="row" class="table-item">{{$status_item->id}}</td>
                  <td class="table-item"><a href='#'>{{$status_item->run_samples}}</a></td>
                </tr>
                @endforeach
              </table>
            </div>
            {{$status->links()}}
          </div>
        </div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3 right-column">
      <div class="others">
        Structure
      </div>
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
