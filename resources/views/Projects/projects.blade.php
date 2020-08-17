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
          <!--search bar -->
          <nav class="navbar navbar-light bg-light">
            <form class="form-inline" method="post" action="/projects">
              @csrf
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search_project">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                  <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                </svg>
              </button>
            </form>
          </nav>
        </div>
        <div class="result-detail">

          <!--projects table -->
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
                    <th scope="col" class="table-header"><a href="projects/create"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        </svg></a></th>
                    @endif

                  </tr>
                </thead>
                <tbody>
                  @if(empty($findProjects) && $Projects != null)
                  @foreach ($Projects as $Project)
                  <tr>
                    <th scope="row" class="table-item">{{$Project->id}}</td>
                    <td class="table-item"><a href="/samples?projectID={{$Project->id}}">{{$Project->name}}</a></td>
                    <td class="table-item">{{$Project->doi}}</td>
                    <td class="table-item">{{strlen($Project->description)>30?substr($Project->description,0,30).'...':$Project->description}}</td>
                    <td class="table-item">
                      @if($isAdmin || $isPI->contains($Project->labs_id))
                      <a href="projects/delete?projectID={{$Project->id}}&page={{$current_page}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg>
                      </a>
                      @endif
                    </td>
                    <td class="table-item">
                      @if($isAdmin || $isPI->contains($Project->labs_id))
                      <a href="projects/update?projectID={{$Project->id}}&page={{$current_page}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg>
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                  @elseif(isset($findProjects))
                  @foreach ($findProjects as $findProject)
                  <tr>
                    <th scope="row" class="table-item">{{$findProject->id}}</td>
                    <td class="table-item"><a href="/samples?projectID={{$findProject->id}}">{{$findProject->name}}</a></td>
                    <td class="table-item">{{$findProject->doi}}</td>
                    <td class="table-item desc">{{strlen($findProject->description)>30?substr($findProject->description,0,30).'...':$findProject->description}}</td>

                    <td class="table-item">
                      @if($isAdmin || $isPI->contains($findProject->labs_id))
                      <a href="projects/delete?projectID={{$findProject->id}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg>
                      </a>
                      @endif
                    </td>
                    <td class="table-item">
                      @if($isAdmin || $isPI->contains($findProject->labs_id))
                      <a href="projects/update?projectID={{$findProject->id}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg>
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                  @else
                  <td></td>
                  <td></td>
                  @endif

                </tbody>
              </table>
            </div>

            <!-- pageinate -->
            @if(empty($findProjects) && $Projects != null)
            {{$Projects->links()}}

            @elseif(isset($findProjects))
            {{$findProjects->links()}}
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
