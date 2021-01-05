@extends('layouts.app')

@push('plotting-js')
<script src="{!! mix('js/plotting.js')!!}"></script>
@endpush

@section('content')
<div class="container-fluid">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
    </div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item"><a href="/workspace/myProject">My Projects</a></li>
          <li class="breadcrumb-item"><a href="/workspace/samples?projectID={{$project_id}}">My Samples</a></li>
          <li class="breadcrumb-item active" aria-current="page">Success</li>
        </ol>
      </nav>
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <div class="bg-white p-3 rounded shadow">
            <div class="d-flex justify-content-between font-weight-bold text-dark border-bottom pb-2">
              <div class="rem15">Job Information</div>
              @if(isset($sample_id))
              <a class="btn btn-default border" href="/successRunning/resultDownload?sampleID={{$sample_id}}">
                <span class="rem1">Download results.zip </span>
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
                  <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z" />
                  <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z" />
                </svg>
              </a>
              @elseif(isset($project_user))
              <a class="btn btn-default border" href="/successRunning/resultDownload?projectID={{$project_id}}">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
                  <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z" />
                  <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z" />
                </svg>
              </a>
              @endif
            </div>

            @if(isset($sample_user))
            <div class="d-flex text-dark rem15 mt-2">
              <div class="mr-3">User : </div>
              <div class="text-success iframe_sample_user">{{$sample_user}}</div>
            </div>
            @elseif(isset($project_user))
            <div class="d-flex text-dark rem15 mt-2">
              <div class="mr-3">User : </div>
              <div class="text-success iframe_project_user">{{$project_user}}</div>
            </div>
            @endif
            @if(isset($sample_uuid))
            <div class="d-none">
              <div class="mr-3">uuid : </div>
              <div class="text-success iframe_sample_uuid">{{$sample_uuid}}</div>
            </div>
            @elseif(isset($project_uuid))
            <div class="d-none">
              <div class="mr-3">uuid : </div>
              <div class="text-success iframe_project_uuid">{{$project_uuid}}</div>
            </div>
            @endif
            @if(isset($file_prefix))
            <div class="d-flex text-dark rem15 mt-2">
              <div class="mr-3">Sample : </div>
              <div class="text-success iframe_sample_name">{{$file_prefix}}</div>
            </div>
            @endif
            <div class="d-flex text-dark rem15 mt-2">
              <div class="mr-3">Started : </div>
              <div class="text-success start_time">{{$started}}</div>
            </div>
            <div class="d-flex text-dark rem15 mt-2">
              <div class="mr-3">Finished : </div>
              <div class="text-success finish_time">{{$finished}}</div>
            </div>
            <div class="d-flex rem15 mt-2 pb-2 border-bottom">
              <div class="mr-3">Time Period : </div>
              <div class="text-success Run_time">{{$period}}</div>
            </div>
            <p class="mt-3">
              <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Show Command >>
              </a>
            </p>
            <div class="collapse" id="collapseExample">
              <div class="card card-body">
                {{$command}}
              </div>
            </div>
            @if(isset($project_user))
            <div class="table-responsive mt-2">
              <table id="quast_dataTable" class="display">
                <thead>
                  <tr></tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            @elseif(isset($sample_uuid))
            <div class="table-responsive mt-2">
              <table id="quast_dataTable" class="display">
                <thead>
                  <tr></tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-multiqc" role="tabpanel" aria-labelledby="v-pills-multiqc-tab">
          <div id="iframe_browser">
            <div id="iframe_browser_header">
              <span id="iframe_browser_title">MultiQC Reports</span>
            </div>
            <div class="embed-responsive embed-responsive-4by3">
              @if(isset($sample_id))
              <iframe class="embed-responsive-item" src={{'results/'.$project_accession.'/'.$sample_uuid.'/MultiQC/multiqc_report.html'}} allowfullscreen></iframe>
              @elseif(isset($project_user))
              <iframe class="embed-responsive-item" src={{'results/'.$project_accession.'/'.$project_uuid.'/MultiQC/multiqc_report.html'}} allowfullscreen></iframe>
              @endif
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-krona" role="tabpanel" aria-labelledby="v-pills-krona-tab">
          <div id="iframe_browser">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
              </div>
              <span id="iframe_browser_title">Kraken Reports</span>
              @if(isset($project_user))
              <ul id="kraken_tabs">
                @foreach($filename_array as $filename)
                <li><a href="#">{{$filename}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          <div id="kraken_report" class="kraken_report embed-responsive embed-responsive-4by3">
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-blob" role="tabpanel" aria-labelledby="v-pills-blob-tab">
          <div id="iframe_browser" class="blob_browser">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
              </div>
              <span id="iframe_browser_title">Blob Reports</span>
              @if(isset($project_user))
              <ul id="blob_tabs">
                @foreach($filename_array as $filename)
                <li><a href="#">{{$filename}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          @if(isset($sample_id))
          <img id="blob_image_sample" src={{'results/'.$project_accession.'/'.$sample_uuid.'/blob/'.$file_prefix.'/'.$file_prefix.'.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png'}} width="100%" height="100%">
          @elseif(isset($project_user))
          <img id="blob_image" src="" width="100%" height="100%">
          @endif
        </div>
        <div class="tab-pane fade" id="v-pills-preseq" role="tabpanel" aria-labelledby="v-pills-preseq-tab">
          <div id="iframe_browser" class="preseq_report">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
              </div>
              <span id="iframe_browser_title">Preseq Reports</span>
              @if(isset($project_user))
              <ul id="preseq_tabs">
                @foreach($preseq_array as $preseq)
                <li><a href="#">{{$preseq}}</a></li>
                @endforeach
              </ul>
              @elseif(isset($sample_id))
              <ul id="preseq_tabs">
                @foreach($preseq_array as $preseq)
                <li><a href="#">{{$preseq}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
            <div id="preseq_report" class="w-100 overflow-hidden">
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-arg" role="tabpanel" aria-labelledby="v-pills-arg-tab">
          <div id="iframe_browser" class="arg_report mb-2">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
              </div>
              <span id="iframe_browser_title">ARG Reports</span>
              @if(isset($project_user))
              <ul id="arg_tabs">
                @foreach($filename_array as $filename)
                <li><a href="#">{{$filename}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          <table id="arg_dataTable" class="table display">
            <thead>
              <tr>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="tab-pane fade" id="v-pills-bowtie" role="tabpanel" aria-labelledby="v-pills-bowtie-tab">
          <div id="iframe_browser" class="bowtie_report mb-2">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
              </div>
              <span id="iframe_browser_title">Bowtie Reports</span>
              @if(isset($project_user))
              <ul id="bowtie_tabs">
                @foreach($filename_array as $filename)
                <li><a href="#">{{$filename}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          <div class="table-responsive">
            <table id="bowtie_dataTable" class="display">
              <thead>
                <tr></tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-md-1">
      <div class="result-info">
        <div class="w-75 nav flex-column nav-pills list-switch left demo-chooser" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <a class="active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
          <a id="v-pills-multiqc-tab" data-toggle="pill" href="#v-pills-multiqc" role="tab" aria-controls="v-pills-multiqc" aria-selected="false">MultiQC</a>
          <a id="v-pills-krona-tab" data-toggle="pill" href="#v-pills-krona" role="tab" aria-controls="v-pills-krona" aria-selected="false">Krona</a>
          <a id="v-pills-blob-tab" data-toggle="pill" href="#v-pills-blob" role="tab" aria-controls="v-pills-blob" aria-selected="false">Blob</a>
          <a id="v-pills-preseq-tab" data-toggle="pill" href="#v-pills-preseq" role="tab" aria-controls="v-pills-preseq" aria-selected="false">Preseq</a>
          <a id="v-pills-arg-tab" data-toggle="pill" href="#v-pills-arg" role="tab" aria-controls="v-pills-arg" aria-selected="false">ARG</a>
          <a id="v-pills-bowtie-tab" data-toggle="pill" href="#v-pills-bowtie" role="tab" aria-controls="v-pills-bowtie" aria-selected="false">Bowtie</a>
        </div>
      </div>
    </div>
  </div>
  @endsection
