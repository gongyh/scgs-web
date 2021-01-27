@extends('layouts.app')

@section('content')
<div class="container">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-3">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item active" aria-current="page">My Projects</li>
        </ol>
      </nav>
      <div class="mb-4">
        @if(isset($labID))
        <a class="" href="/projects/create?labID={{$labID}}&from=myProject"><svg class="bi bi-plus-square-fill" width="2.5em" height="2.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z" />
          </svg></a>
        @else
        <a class="" href="/projects/create?from=myProject"><svg class="bi bi-plus-square-fill" width="2.5em" height="2.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z" />
          </svg></a>
        @endif
        @if($myProjects != null)
        @foreach($myProjects as $myProject)
        <div class="d-flex mt-3 p-2 rounded-lg border shadow-sm overflow-auto">
          <div class="project_id mr-4 font-large">{{$current_page > 1 ? ($current_page-1) * $pageSize + $loop->iteration : $loop->iteration}}</div>
          <div class="font-normal">
            <div class="project_title font-normal text-wrap text-break"><a href="/workspace/samples?projectID={{$myProject->id}}">{{$myProject->name}}</a></div>
            <div class="mt-2">
              <span class="text-primary">Accession</span> : <span>{{$myProject->doi}}</span>
            </div>
            <div class="mt-2">
              <span>Type</span> : <span>{{$myProject->type}}</span>
            </div>
            <div class="mt-2 project_desc text-wrap text-break">Project Description : {{strlen($myProject->description)>200?substr($myProject->description,0,200).'...':$myProject->description}}</div>
            <div class="mt-2 text-black-50">Lab : {{$myProject->getLabName($myProject->labs_id)}}</div>
            <div class="edit-delete">
              <a href="/projects/update?projectID={{$myProject->id}}&from=myProject">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                </svg>
              </a>
              <a href="/projects/delete?projectID={{$myProject->id}}&from=myProject" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
        @endforeach
        @endif
      </div>
      <!-- pageinate -->
      @isset($myProjects)
      {{$myProjects->links()}}
      @endisset
    </div>
    <!-- right-column -->
    <div class="">
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
