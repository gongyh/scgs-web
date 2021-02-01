@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <!-- left column -->
    <!-- middle column -->
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Projects</li>
          </li>
        </ol>
      </nav>
      <div class="mb-4">
        <div class="d-flex justify-content-between">
          <!--search bar -->
          <nav class="navbar navbar-light proj_search">
            <form class="form-inline" method="post" action="/projects">
              @csrf
              <input class="form-control mr-sm-2" type="search" placeholder="Search..." aria-label="Search" name="search_project">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                  <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                </svg>
              </button>
            </form>
          </nav>
          @if($isAdmin || $isPI)
          <a class="mt-2" href="projects/create"><svg class="bi bi-plus-square-fill" width="2.5em" height="2.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z" />
            </svg></a>
          @endif
        </div>
        <div class="overflow-auto">
          @if($Projects != null)
          @foreach ($Projects as $Project)
          <div class="d-flex mt-3 p-2 rounded-lg border shadow-sm project_section">
            <div class="project_id mr-4 font-large">{{$current_page > 1 ? ($current_page-1) * $pageSize + $loop->iteration : $loop->iteration}}</div>
            <div class="font-normal">
              <div class="project_title font-normal text-wrap text-break"><a href="/samples?projectID={{$Project->id}}">{{$Project->name}}</a></div>
              <div class="mt-2">
                <span class="text-primary">Accession</span> : <span>{{$Project->doi}}</span>
              </div>
              <div class="mt-2">
                <span>Type</span> : <span class="project_type">{{$Project->type}}</span>
              </div>
              <div class="mt-2">
                <span>Samples</span> : <span>{{DB::table('samples')->where('projects_id',$Project->id)->count()}}</span>
              </div>
              <div class="mt-2 project_desc text-wrap text-break">Description : {{strlen($Project->description)>200?substr($Project->description,0,200).'...':$Project->description}}
              </div>
              <div class="mt-2 text-black-50">Lab : {{$Project->getLabName($Project->labs_id)}}</div>
              <div class="edit-delete">
                @if($isAdmin || $isPI->contains($Project->labs_id))
                <a href="projects/update?projectID={{$Project->id}}&page={{$current_page}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                  </svg>
                </a>
                @endif
                @if($isAdmin || $isPI->contains($Project->labs_id))
                <a href="projects/delete?projectID={{$Project->id}}&page={{$current_page}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                  </svg>
                </a>
                @endif
              </div>
            </div>
          </div>
          @endforeach
          @endif

          @if($Projects->count() == 0)
          <div class="mt-4">
            <i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>
          </div>
          @endif
        </div>
      </div>
      <!-- pageinate -->
      @if($Projects != null)
      {{$Projects->links()}}

      @elseif(isset($findProjects))
      {{$findProjects->links()}}
      @endif
      <!-- right-column -->
    </div>
    <div class="col-md-3 right-column">
      <div class="other-info">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title font-weight-bold">Filter</h5>
            <hr>
            <h6 class="card-subtitle mb-2 text-muted font-weight-bold">Type</h6>
            <form action="" method="POST">
              @csrf
              <select id="search_type" name="select_type" class="selectpicker show-tick mb-2 border rounded" data-live-search="true" data-style="btn-default">
                @if(isset($select_type))
                <option value=""></option>
                @foreach($types as $type)
                <option value={{$type}} {{($select_type == $type)?'selected':''}}>{{$type}}</option>
                @endforeach
                @else
                <option value=""></option>
                @foreach($types as $type)
                <option value={{$type}}>{{$type}}</option>
                @endforeach
                @endif
              </select>
              <button class="btn btn-primary mt-2" type="submit"><i class="fa fa-filter"></i><span> Find items</span></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
