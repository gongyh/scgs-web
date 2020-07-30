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
            <div class="proj_detail">{{$project->description}}</div>
          </div>
          <div class="project_sample">
            <div class="proj_title">Sample List:</div>
            <table class="sampleTable table table-sm">
              <thead>
                <tr>
                  <th scope="col">SampleID</th>
                  <th scope="col">PairEnds</th>
                  <th scope="col">SampleLabel</th>

                  @if($isAdmin || $isPI)
                  <th scope="col" class="table-header"><a href="samples/create?projectID={{$projectID}}"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                        <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                      </svg></a></th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @if($selectSamples !== null)
                @foreach($selectSamples as $selectSample)
                <tr>
                  <th scope="row">{{$selectSample->id}}</th>
                  <td>{{$selectSample->pairends}}</td>
                  <td>
                    <a href="#">{{$selectSample->sampleLabel}}</a>
                  </td>

                  @if($isAdmin || $isPI)
                  <td class="table-item">
                    <a href="samples/delete?projectID={{$projectID}}&sampleID={{$selectSample->id}}" class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                    <a href="samples/update?projectID={{$projectID}}&sampleID={{$selectSample->id}}" class="btn btn-primary btn-sm">edit</a>
                  </td>
                  @endif
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
          @if($selectSamples !== null)
          {{$selectSamples->links()}}
          @endif
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
