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
      <div class="result">
        <div class="result-detail">
          <div class="project_name">
            <div class="proj_title">Project Name:</div>
            <div class="proj_detail">{{$project->name}}</div>
          </div>
          <div class="project_doi">
            <div class="proj_title">Doi Number:</div>
            <div class="proj_detail">{{$project->doi}}</div>
          </div>
          <div class="project_desc">
            <div class="proj_title">Description:</div>
            <div class="proj_desc">{{$project->description}}</div>
          </div>
          <div class="project_sample">
            <div class="proj_title">Sample List:</div>
            <div class="mt-3 table-responsive bg-light shadow-sm rounded-lg">
              <table class="table table-condense">
                <thead>
                  <tr>
                    <th scope="col" class="table-header">SampleID</th>
                    <th scope="col" class="table-header">SampleLabel</th>
                    <th scope="col" class="table-header">Application</th>
                    <th scope="col" class="table-header">Species</th>
                    <th scope="col" class="table-header">Pairends</th>
                    <th scope="col" class="table-header">Status</th>
                    <th></th>
                    @if($isAdmin || $isPI)
                    <th scope="col" class="add"><a href="/samples/create?projectID={{$projectID}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        </svg></a></th>
                    @endif
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @if($selectSamples != null)
                  @foreach($selectSamples as $selectSample)
                  <tr>
                    <th scope="row" class="table-item">{{$selectSample->id}}</th>
                    <td class="table-item">
                      @if(Auth::check())
                      @if(DB::table('jobs')->where('sample_id',$selectSample->id)->count() == 0 || DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 0)
                      <span>{{$selectSample->sampleLabel}}</span>
                      @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 1)
                      <a href="/execute/start?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                      @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 2)
                      <a href="/failedRunning?uuid={{DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('uuid')}}">{{$selectSample->sampleLabel}}</a>
                      @elseif(DB::table('jobs')->where('sample_id',$selectSample->id)->count() > 0 && DB::table('jobs')->where('sample_id',$selectSample->id)->value('status') == 3)
                      <a href="/successRunning?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                      @endif
                      @else
                      <span>{{$selectSample->sampleLabel}}</span>
                      @endif
                    </td>
                    <td class="table-item">{{$sample->getAppName($selectSample->applications_id)}}</td>
                    <td class="table-item">{{$sample->getSpeciesName($selectSample->species_id)}}</td>
                    <td class="table-item">{{$selectSample->pairends == 1 ? 'Yes':'No'}}</td>
                    @if(Auth::check())
                    <td class="table-item">
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
                    </td>
                    @endif
                    @if($isAdmin || $isPI)
                    <td class="delete">
                      <a href="/samples/delete?projectID={{$projectID}}&sampleID={{$selectSample->id}}" onclick="if(confirm('Are you sure to delete?')==false) return false;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg></a>
                    </td>
                    <td class="update">
                      <a href="/samples/update?projectID={{$projectID}}&sampleID={{$selectSample->id}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg></a>
                    </td>
                    <td class="exec" title="execute the sample">
                      @if(DB::table('jobs')->where('sample_id',$selectSample->id)->count() == 0 || DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 2 || DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('status') == 3)
                      <a href="/execute?sampleID={{$selectSample->id}}">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                        </svg>
                      </a>
                      @endif
                    </td>
                    @endif
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          @if($selectSamples != null)
          {{$selectSamples->links()}}
          @endif
        </div>
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
