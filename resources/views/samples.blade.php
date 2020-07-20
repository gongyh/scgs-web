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
          Sample
        </div>
        <div class="result-detail">
          <div class="labs">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead class="table-dark">
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Samples</th>
                    <th scope="col" class="table-header">Species</th>
                    <th scope="col" class="table-header">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($samples as $sample)
                  <tr>
                    <th scope="row" class="table-item">{{$sample->id}}</td>
                    <td class="table-item"><a href='#'>{{$sample->sampleLabel}}</a></td>
                    <td class="table-item">{{$sample->species}}</td>
                    <td class="table-item">
                      <a href="{{url('samples/delete',['id'=>$sample->id])}}" onclick="if(confirm('Are you sure to delete?')==false) return false;" class="btn btn-primary btn-sm">delete</a>
                      <a href="{{url('samples/update',['id'=>$sample->id])}}" class="btn btn-primary btn-sm">edit</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{$samples->links()}}
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
