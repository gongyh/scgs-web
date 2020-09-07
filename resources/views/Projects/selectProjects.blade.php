@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-sm-3 left-column">
      <div class="list-group list">

      </div>
    </div>
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome!
      </div>
      <div class="result">
        <div class="result-info">

        </div>
        <div class="result-detail">
          <div class="projects">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead>
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Project</th>
                    <th scope="col" class="table-header">ProjectID</th>
                    <th scope="col" class="table-header">Description</th>
                    <th></th>

                    @if($isAdmin || $isPI)
                    <th scope="col" class="add"><a href="/projects/create?labID={{$labID}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
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
                    <td scope="row" class="table-item desc">{{$selectProject->doi}}</td>
                    <td class="table-item">{{strlen($selectProject->description)>30?substr($selectProject->description,0,30).'...':$selectProject->description}}</td>

                    @if($isAdmin || $isPI)
                    <td class="delete">
                      <a href="/projects/delete?labID={{$labID}}&projectID={{$selectProject->id}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg></a>
                    </td>
                    <td class="update">
                      <a href="/projects/update?labID={{$labID}}&projectID={{$selectProject->id}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg></a>
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

      <div class="other-info">

      </div>
    </div>
  </div>
</div>
@endsection
