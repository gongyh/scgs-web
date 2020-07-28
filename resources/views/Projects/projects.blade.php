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
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/institutions">institutions</a></li>
              <li class="breadcrumb-item"><a href="/labs?instiID={{$current_insti_id}}">labs</a></li>
              <li class="breadcrumb-item active" aria-current="page">projects</li>
            </ol>
          </nav>
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

                    @if($isAdmin || $isPI)
                    <th scope="col" class="table-header"><a href="projects/create?labID={{$labID}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        </svg></a></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @if($selectProjects !== null)
                  @foreach ($selectProjects as $selectProject)
                  <tr>
                    <th scope="row" class="table-item">{{$selectProject->id}}</td>
                    <td scope="row" class="table-item"><a href="/samples?projectID={{$selectProject->id}}">{{$selectProject->name}}</a></td>
                    <td class="table-item desc">{{$selectProject->description}}</td>

                    @if($isAdmin || $isPI)
                    <td class="table-item">
                      <a href="projects/delete?labID={{$labID}}&projectID={{$selectProject->id}}" class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                    </td>
                    <td>
                      <a href="projects/update?labID={{$labID}}&projectID={{$selectProject->id}}" class="btn btn-primary btn-sm">edit</a>
                    </td>
                    @endif

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
