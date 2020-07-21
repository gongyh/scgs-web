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
        Welcome!
      </div>
      <div class="result">
        <div class="result-info">
          Project
        </div>
        <div class="result-detail">
          <div class="projects">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead class="table-dark">
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Projects</th>
                    <th scope="col" class="table-header">Description</th>
                    <th scope="col" class="table-header">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($projects as $project)
                  <tr>
                    <th scope="row" class="table-item">{{$project->id}}</td>
                    <td class="table-item"><a href="{{url('projects/projectInfo',['id'=>$project->id])}}">{{$project->name}}</a></td>
                    <td class="table-item desc">{{$project->description}}</td>
                    <td class="table-item">
                      <a href="{{$isPI ? url('projects/delete',['id'=>$project->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '' : 'disabled'}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                      <a href="{{$isPI ? url('projects/update',['id'=>$project->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '':'disabled'}} ">edit</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{$projects->links()}}
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
