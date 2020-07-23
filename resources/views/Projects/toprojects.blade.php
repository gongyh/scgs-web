@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-sm-3 left-column">
      <div class="list-group list">
        <a class="list-group-item" href="/institutions">Institutions</a>
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
                <thead>
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Projects</th>
                    <th scope="col" class="table-header">Description</th>
                    <th scope="col" class="table-header">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @if($selectProjects !== null)
                  @foreach ($selectProjects as $selectProject)
                  <tr>
                    <th scope="row" class="table-item">{{$selectProject->id}}</td>
                    <td class="table-item"><a href="{{url('projects/projectInfo',['id'=>$selectProject->id])}}">{{$selectProject->name}}</a></td>
                    <td class="table-item desc">{{$selectProject->description}}</td>
                    <td class="table-item">
                      <a href="{{$isPI ? url('projects/delete',['id'=>$selectProject->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '' : 'disabled'}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                      <a href="{{$isPI ? url('projects/update',['id'=>$selectProject->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '':'disabled'}} ">edit</a>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            @if($selectProjects !== null)
            {{$selectProjects->links()}}
            @endif
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
