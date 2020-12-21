@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
          <li class="breadcrumb-item active" aria-current="page">Samples</li>
          </li>
        </ol>
      </nav>
      <div class="result">
        <div class="result-detail">
          <div class="project_name">
            <div class="proj_title">Project Name:</div>
            <div class="proj_detail">{{$project->name}}</div>
          </div>
          <div class="project_doi">
            <div class="proj_title">Accession:</div>
            <div class="proj_detail">{{$project->doi}}</div>
          </div>
          <div class="project_desc pb-3">
            <div class="proj_title">Description:</div>
            <div class="proj_desc">{{$project->description}}</div>
          </div>
          <div class="project_desc pb-3">
            <div class="proj_title">Project Status:</div>
            <div>
              @if(DB::table('jobs')->where('project_id',$project->id)->count() > 0 && DB::table('jobs')->where('project_id',$project->id)->orderBy('id','desc')->value('status') == 0)
              <span class="badge badge-warning mt-2">
                <span>Waiting</span>
                <span class="dot">...</span>
              </span>
              @elseif(DB::table('jobs')->where('project_id',$project->id)->count() > 0 && DB::table('jobs')->where('project_id',$project->id)->orderBy('id','desc')->value('status') == 1)
              <span class="badge badge-info mt-2">
                <span>Running</span>
                <span class="dot">...</span>
              </span>
              @elseif(DB::table('jobs')->where('project_id',$project->id)->count() > 0 && DB::table('jobs')->where('project_id',$project->id)->orderBy('id','desc')->value('status') == 2)
              <span class="badge badge-danger mt-2">
                <span>failed</span>
              </span>
              @elseif(DB::table('jobs')->where('project_id',$project->id)->count() > 0 && DB::table('jobs')->where('project_id',$project->id)->orderBy('id','desc')->value('status') == 3)
              <span class="badge badge-success mt-2">
                <span>success</span>
              </span>
              @else
              <span class="badge badge-dark mt-2">haven't ran</span>
              @endif
            </div>
            <a href="/execute?projectID={{$projectID}}" class="ml-3 btn btn-primary">Execute the project <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
              </svg>
            </a>
          </div>
          <div class="border-bottom pb-3">
            <a href="/ramanResult?projectID={{$projectID}}" class="btn btn-success">Raman Spectra</a>
            @if(DB::table('jobs')->where('project_id',$projectID)->count() > 0 && DB::table('jobs')->where('project_id',$projectID)->orderBy('id','desc')->value('status') == 3)
            <a href="/successRunning?projectID={{$projectID}}" class="ml-2 btn btn-success">Show Project Report </a>
            @elseif(DB::table('jobs')->where('project_id',$projectID)->count() > 0 && DB::table('jobs')->where('project_id',$projectID)->value('status') == 1)
            <a href="/execute/start?projectID={{$projectID}}" class="ml-2 btn btn-default">Show Pipeline Status</a>
            @endif
          </div>
          <div class="project_sample mt-3">
            <div class="d-flex">
              <div class="proj_title">Sample List:</div>
              @if($isAdmin || $isPI)
              <div class="text-danger mt-1">
                (Upload an excel file to save sample list)
              </div>
              @endif
            </div>
            @if($isAdmin || $isPI)
            <div class="d-flex justify-content-between">
              <div>
                <a href="/samples/template/download" class="mr-2 btn btn-primary">Download excel template <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cloud-download" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z" />
                    <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z" />
                  </svg></a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_sample_file">
                  Upload excel template <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cloud-upload" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z" />
                    <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z" />
                  </svg>
                </button>
              </div>
              <a class="btn btn-default pt-2 font-weight-bold ml-3" href="/samples/create?projectID={{$projectID}}">Add Sample</a>
              <div class="modal" id="myModal_sample_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                      <input type="file" name="sample_file" id="sample_file" multiple class="sample_file file-loading">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @if($selectSamples != null)
            @foreach($selectSamples as $selectSample)
            <div class="d-flex mt-3 p-2 rounded-lg border shadow-sm overflow-auto">
              <div class="project_id mr-4 font-large">{{$selectSample->id}}</div>
              <div class="font-normal text-wrap text-break">
                <div class="projectId mt-2">Sample Label :
                  @if($isPI || $isAdmin)
                  @if(DB::table('jobs')->where('sample_id',$selectSample->id)->count() == 0 || DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 0)
                  {{$selectSample->sampleLabel}}
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 1)
                  <a href="/execute/start?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 2)
                  <a href="/failedRunning?uuid={{DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('uuid')}}">{{$selectSample->sampleLabel}}</a>
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 3)
                  <a href="/successRunning?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                  @endif
                  @else
                  {{$selectSample->sampleLabel}}
                  @endif
                </div>
                <div>
                  Species : <span class="font-italic">{{$sample->getSpeciesName($selectSample->species_id)}}</span>
                </div>
                <div class="">
                  Status :
                  @if(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 0)
                  <span class="badge badge-warning">
                    <span>Waiting</span>
                    <span class="dot">...</span>
                  </span>
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 1)
                  <span class="badge badge-info">
                    <span>Running</span>
                    <span class="dot">...</span>
                  </span>
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 2)
                  <span class="badge badge-danger">
                    <span>failed</span>
                  </span>
                  @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 3)
                  <span class="badge badge-success">
                    <span>success</span>
                  </span>
                  @else
                  <span class="badge badge-dark">haven't ran</span>
                  @endif
                </div>
                @if($isPI || $isAdmin)
                <div class="edit-delete-execute">
                  <a href="/samples/update?projectID={{$projectID}}&sampleID={{$selectSample->id}}">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg>
                  </a>
                  <a href="/samples/delete?projectID={{$projectID}}&sampleID={{$selectSample->id}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                    </svg>
                  </a>
                  @if(DB::table('jobs')->where('sample_id',$selectSample->id)->count() == 0 || DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 2 || DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 3)
                  <a href="/execute?sampleID={{$selectSample->id}}" title="execute the pipeline">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                    </svg>
                  </a>
                  @endif
                </div>
                @endif
              </div>
            </div>
            @endforeach
            @endif
          </div>
        </div>
      </div>
      <div>
        @if($selectSamples != null)
        {{$selectSamples->links()}}
        @endif
      </div>
    </div>
    <!-- right-column -->
    <div class="col-md-3 right-column">
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
