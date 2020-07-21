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
      </div>
    </div>

    <!-- middle column -->
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome to SCGS-Web!
      </div>
      <div class="result">
        <div class="result-info">
          Institution
        </div>
        <div class="result-detail">
          <div class="institutions">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Institutions</th>
                    <th scope="col" class="table-header">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($institutions as $institution)
                  <tr>
                    <th scope="row" class="table-item" name="insti-id">{{$institution->id}}</td>
                    <td class="table-item" name="insti-name">
                      <a href="/institutions/labs?instiID={{$institution->id}}">{{$institution->name}}</a></td>
                    <td class="table-item">
                      <a href="{{url('institutions/delete',['id'=>$institution->id])}}" onclick="if(confirm('Are you sure to delete?')==false) return false;" class="btn btn-primary btn-sm">delete</a>
                      <a href="{{url('institutions/update',['id'=>$institution->id])}}" class="btn btn-primary btn-sm">edit</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{$institutions->links()}}
          </div>
        </div>
      </div>
    </div>

    <!-- right-column -->
    <div class="col-sm-3 right-column">
      <div class="others">
        Notice
      </div>
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
