@extends('layouts.app')

@section('content')
<div class="container-fluid">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <div class="bg-white p-3 rounded shadow">
            <div class="d-flex justify-content-between font-weight-bold font-italic text-dark border-bottom pb-2">
              <div class="rem15">Job Information</div>
              <a class="btn btn-default" href="/successRunning/resultDownload?sampleID={{$sample_id}}">
                <span class="rem1">Download results.zip </span>
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
                  <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z" />
                  <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z" />
                </svg>
              </a>
            </div>

            <div class="d-flex text-dark rem15 font-italic mt-2">
              <div class="mr-3">started : </div>
              <div class="text-success start_time">{{$started}}</div>
            </div>
            <div class="d-flex text-dark rem15 font-italic mt-2">
              <div class="mr-3">finished : </div>
              <div class="text-success finish_time">{{$finished}}</div>
            </div>
            <div class="d-flex rem15 font-italic mt-2 pb-2 border-bottom">
              <div class="mr-3">time period : </div>
              <div class="text-success Run_time">{{$period}}</div>
            </div>
            <p class="mt-3">
              <a class="btn btn-light" type="a" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Show Command >>
              </a>
            </p>
            <div class="collapse" id="collapseExample">
              <div class="card card-body">
                {{$command}}
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
          <div id="iframe_browser">
            <div id="iframe_browser_header">
              <div id="iframe_browser_buttons">
                <span></span>
                <span></span>
                <span></span>
              </div>
              <span id="iframe_browser_title">MultiQC Example Reports</span>
            </div>
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src={{'results/'.$sample_user.'/'.$sample_uuid.'/MultiQC/multiqc_report.html'}} allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- right-column -->
    <div class="col-md-1">
      <div class="result-info">
        <div class="ml-3 rem15 text-info">Results</div>
        <div class="w-50 nav flex-column nav-pills list-switch left demo-chooser" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <a class="active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
          <a id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">MultiQC</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
