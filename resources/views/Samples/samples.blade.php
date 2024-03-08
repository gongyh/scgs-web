@extends('layouts.app')

@section('content')
<div class="container">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class=""></div>
    <div class="col-md-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
          <li class="breadcrumb-item active" aria-current="page">Samples</li>
        </ol>
      </nav>
      <div class="mb-4">
        <table class="layui-table">
          <colgroup>
            <col width="200">
            <col>
          </colgroup>
          <thead>
          </thead>
          <tbody>
            <tr>
              <td>Project Name:</td>
              <td>{{$project->name}}</td>
            </tr>
            <tr>
              <td>Accession:</td>
              <td>{{$project->doi}}</td>
            </tr>
            <tr>
              <td>Type:</td>
              <td>{{$project->type}}</td>
            </tr>
            <tr>
              <td>Collection Date:</td>
              <td>{{$project->collection_date}}</td>
            </tr>
            <tr>
              <td>Release Date:</td>
              <td>{{$project->release_date}}</td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>{{$project->description}}</td>
            </tr>
            <tr>
              <td>Accession:</td>
              <td>{{$project->doi}}</td>
            </tr>
            <tr>
              <td>Project Status:</td>
              <td>
                @if(strcmp($status,"not analyzed") == 0)
                <span class="badge badge-dark">not analyzed</span>
                @elseif(strcmp($status,"queueing") == 0)
                <span class="badge badge-warning">
                  <span>Queueing</span>
                </span>
                <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop">
                @elseif(strcmp($status,"running") == 0)
                <span class="badge badge-info">
                  <span>Running</span>
                </span>
                <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop">
                @elseif(strcmp($status,"failed") == 0)
                <span class="badge badge-danger">
                  <span>failed</span>
                </span>
                @elseif(strcmp($status,"success") == 0)
                <span class="badge badge-success">
                  <span>success</span>
                <i class="fa-solid fa-clipboard-check"></i>
                </span>
                @else
                @endif
              </td>
            </tr>
            <tr>
              <td>Operation:</td>
              <td>
                @if(($isAdmin || $isPI) && $canRun && strcmp($status,"running") != 0)
                <a href="/execute?projectID={{$projectID}}" class="btn btn-sm btn-primary">Execute
                  <i class="fa-regular fa-square-caret-right" style="font-size:14px"></i>
                </a>
                @endif
                @if(strcmp($status,"success") == 0 && ($isAdmin || $isPI || $is_release))
                <a href="/successRunning?projectID={{$projectID}}" class="btn btn-sm btn-success">Report
                  <i class="fa-solid fa-book-open" style="font-size:14px"></i>
                </a>
                @elseif(strcmp($status,"running") == 0 && ($isAdmin || $isPI))
                <a href="/execute/start?projectID={{$projectID}}" class="btn btn-sm btn-info">Progress
                <i class="fa-solid fa-book-open" style="font-size:14px"></i>
                </a>
                @endif
              </td>
            </tr>
            <tr>
              <td>Phenotype:</td>
              <td>
                <a href="/ramanResult?projectID={{$projectID}}" class="btn btn-sm btn-success">Raman Spectra</a>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="project_sample mt-3">
          <div class="d-flex">
            <div class="proj_title">Samples:</div>
            @if($isAdmin || $isPI)
            <div class="text-danger mt-1">
              (Bulk upload samples by supplying metadata according to the excel template below)
            </div>
            @endif
          </div>
          @if($isAdmin || $isPI)
          <div class="d-flex justify-content-between">
            <div>
              <a href="/samples/template/download" class="mr-2 btn btn-primary">Download excel template
                <i class="fa-solid fa-cloud-arrow-down"></i>
              </a>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_sample_file">
                Upload excel template
                <i class="fa-solid fa-cloud-arrow-up"></i>
              </button>
            </div>
            <a class="btn btn-default btn-sm border pt-2 font-weight-bold ml-3" href="/samples/create?projectID={{$projectID}}">Add Samples</a>
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
            <div class="project_id mr-4 font-large">{{$current_page > 1 ? ($current_page-1) * $pageSize + $loop->iteration : $loop->iteration}}</div>
            <div class="font-normal text-wrap text-break">
              <div class="projectId mt-2">Sample Label :
                @if($isPI || $isAdmin || $is_release)
                @if($selectSample->status == 0 || $selectSample->status == 4)
                {{$selectSample->sampleLabel}}
                @elseif($selectSample->status == 1)
                <a href="/execute/start?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                @elseif($selectSample->status == 2)
                <a href="/failedRunning?uuid={{DB::table('jobs')->where('sample_id',$selectSample->id)->orderBy('id','desc')->value('uuid')}}">{{$selectSample->sampleLabel}}</a>
                @elseif($selectSample->status == 3)
                <a href="/successRunning?sampleID={{$selectSample->id}}">{{$selectSample->sampleLabel}}</a>
                @endif
                @else
                {{$selectSample->sampleLabel}}
                @endif
              </div>
              <div>
                Species : <span class="font-italic">{{isset($selectSample->species_id) ? $sample->getSpeciesName($selectSample->species_id) : 'unknown'}}</span>
              </div>
              <div class="">
                Status :
                @if($selectSample->status == 0)
                <span class="badge badge-dark">not analyzed</span>
                @elseif($selectSample->status == 1)
                <span class="badge badge-info">
                  <span>Running</span>
                  <span class="dot">...</span>
                </span>
                @elseif($selectSample->status == 2)
                <span class="badge badge-danger">
                  <span>Failed</span>
                </span>
                @elseif($selectSample->status == 3)
                <span class="badge badge-success">
                  <span>Success</span>
                  <i class="fa-solid fa-clipboard-check"></i>
                </span>
                @elseif($selectSample->status == 4)
                <span class="badge badge-warning">
                  <span>Queueing</span>
                  <span class="dot">...</span>
                </span>
                @else
                @endif
              </div>
              @if($isPI || $isAdmin)
              <div class="edit-delete-execute">
                <a href="/samples/update?projectID={{$projectID}}&sampleID={{$selectSample->id}}">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="/samples/delete?projectID={{$projectID}}&sampleID={{$selectSample->id}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
                @if($selectSample->status == 0 || $selectSample->status == 2 || $selectSample->status == 3)
                <a href="/execute?sampleID={{$selectSample->id}}" title="execute the pipeline">
                  <i class="fa-solid fa-play ml-1"></i>
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
      @if($selectSamples != null)
      {{$selectSamples->links()}}
      @endif
    </div>
    <!-- right-column -->
    <div class="right-column">
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
