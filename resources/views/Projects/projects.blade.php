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
          <a class="mt-3" href="projects/create">
            <i class="fa-solid fa-folder-plus" style="font-size:32px"></i>
          </a>
          @endif
        </div>
        <div class="overflow-auto">
          @if($Projects != null)
          @foreach ($Projects as $Project)
          <div class="d-flex mt-3 p-2 rounded-lg border shadow-sm project_section">
            <div class="project_id mr-4 font-large">{{$current_page > 1 ? ($current_page-1) * $pageSize + $loop->iteration : $loop->iteration}}</div>
            <div class="font-normal">
              <div class="project_title font-normal text-wrap text-break"><a href="/samples?projectID={{$Project->id}}">{{$Project->name}}</a></div>
              <div>
                <span class="text-primary">Accession</span> : <span>{{$Project->doi}}</span>
              </div>
              <div>
                <span>Type</span> : <span class="project_type">{{$Project->type}}</span>
              </div>
              <div>
                <span>Samples</span> : <span>{{DB::table('samples')->where('projects_id',$Project->id)->count()}}</span>
              </div>
              <div class="project_desc text-wrap text-break">Description : {{strlen($Project->description)>200?substr($Project->description,0,200).'...':$Project->description}}
              </div>
              <div class="text-black-50">Lab : {{$Project->getLabName($Project->labs_id)}}</div>
              <div class="edit-delete">
                @if($isAdmin || $isPI->contains($Project->labs_id))
                <a href="projects/update?projectID={{$Project->id}}&page={{$current_page}}">
                  <i class="fa-solid fa-pen-to-square" style="font-size:22px"></i>
                </a>
                @endif
                @if($isAdmin || $isPI->contains($Project->labs_id))
                <a href="projects/delete?projectID={{$Project->id}}&page={{$current_page}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">
                  <i class="fa-solid fa-trash-can" style="font-size:22px"></i>
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
            <form action="" method="POST">
              <h5 class="card-title font-weight-bold">Filter</h5>
              <hr>
              <h6 class="card-subtitle mb-2 text-muted font-weight-bold">Type</h6>
              @csrf
              <select id="search_type" name="select_type" class="selectpicker show-tick mb-2 border rounded" data-live-search="true" data-style="btn-default">
                @if(isset($select_type))
                <option value="">Anything</option>
                @foreach($types as $type)
                <option value={{$type}} {{($select_type == $type)?'selected':''}}>{{$type}}</option>
                @endforeach
                @else
                <option value="">Anything</option>
                @foreach($types as $type)
                <option value={{$type}}>{{$type}}</option>
                @endforeach
                @endif
              </select>
              <h6 class="card-subtitle mt-2 mb-2 text-muted font-weight-bold">Release Date</h6>
              <select name="select_date" id="search_date" class="selectpicker show-tick mb-2 border rounded" data-live-search="true" data-style="btn-default">
                @if(isset($select_date))
                <option value="">Anything</option>
                @foreach($dates as $date)
                <option value={{$date}} {{($select_date == $date)?'selected':''}}>{{$date}}</option>
                @endforeach
                @else
                <option value="">Anything</option>
                @foreach($dates as $date)
                <option value={{$date}}>{{$date}}</option>
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
