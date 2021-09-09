@extends('layouts.app')

@section('content')
<div class="container">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-11">
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
            <div class="font-weight-bold text-dark border-bottom pb-2">
              <div class="rem15">Job Information</div>
              <div class="d-flex mt-2">
                <p>
                  <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapse_detail" aria-expanded="false" aria-controls="collapseExample">
                    Show Detail >>
                  </a>
                </p>
                <p class="ml-2">
                  <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapse_command" aria-expanded="false" aria-controls="collapseExample">
                    Show Command >>
                  </a>
                </p>

                @if(isset($sample_id))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?sampleID={{$sample_id}}" title="Download compress result">
                  <img src="{{asset('/images/zip.jpg')}}" class="download-image" alt="Download compress result">
                </a>
                @elseif(isset($project_user))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?projectID={{$project_id}}" title="Download compress result">
                  <img src="{{asset('/images/zip.jpg')}}" class="download-image" alt="Download compress result">
                </a>
                @endif
              </div>
            </div>
            <div class="collapse" id="collapse_command">
              <div class="card card-body">
                {{$command}}
              </div>
            </div>
            <div class="collapse" id="collapse_detail">
              <div class="card card-body">
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
                <div class="d-none">
                  <div class="mr-3">uuid : </div>
                  <div class="text-success iframe_project_accession">{{$project_accession}}</div>
                </div>
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
              </div>
            </div>
            <!-- quast -->
            <div class="mt-3 border-bottom">
              <div class="font-large">Quast</div>
              <img class="fading_circles_quast" src="images/Fading_circles.gif" alt="">
              @if(isset($project_user))
              <div class="table-responsive mt-3 mb-3">
                <table id="quast_dataTable" class="display">
                  <thead>
                    <tr></tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              @elseif(isset($sample_uuid))
              <div class="table-responsive mt-3 mb-3">
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

            <div class="mt-3 border-bottom">
              <div class="font-large">Blob</div>
              @if(isset($project_user))
              <select id="blob_txt_tabs" class="selectpicker show-tick mt-2" data-live-search="true" data-style="btn-info">
                @foreach($filename_array as $filename)
                <option value={{$filename}}>{{$filename}}</option>
                @endforeach
              </select>
              @endif
              <img class="fading_circles_blob" src="images/Fading_circles.gif" alt="">
              <!-- blob table -->
              <div class="table-responsive mt-2 mb-2">
                <table id="blob_dataTable" class="display">
                  <thead>
                    <tr></tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="mt-3">
              <div class="font-large">Blob Picture</div>
              @if(isset($project_user))
              <select id="blob_pic_tabs" class="selectpicker show-tick mt-2" data-live-search="true" data-style="btn-info">
                @foreach($filename_array as $filename)
                <option value={{$filename}}>{{$filename}}</option>
                @endforeach
              </select>
              <select id="blob_classify" class="selectpicker show-tick mt-2 ml-2" data-live-search="true" data-style="btn-info">
                <option value="superkingdom">superkingdom</option>
                <option value="phylum" selected>phylum</option>
                <option value="order">order</option>
                <option value="family">family</option>
                <option value="genus">genus</option>
                <option value="species">species</option>
              </select>
              @endif
              <button id="draw_blob_pic" class="btn btn-info btn-default mt-2 ml-2">Draw</button>
              <img class="fading_circles_blob" src="images/Fading_circles.gif" alt="">
              <div id="blob_pic" class="w-100 overflow-hidden"></div>
            </div>
          </div>
        </div>

        <!-- MultiQC -->
        <div class="tab-pane fade" id="v-pills-multiqc" role="tabpanel" aria-labelledby="v-pills-multiqc-tab">
          <div id="iframe_browser">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">MultiQC Reports</div>
            </div>
            <div class="embed-responsive embed-responsive-4by3">
              @if(isset($sample_id))
              <iframe class="embed-responsive-item" src='/multiqc?sample_uuid={{$sample_uuid}}' allowfullscreen></iframe>
              @elseif(isset($project_user))
              <iframe class="embed-responsive-item" src='/multiqc?project_uuid={{$project_uuid}}' allowfullscreen></iframe>
              @endif
            </div>
          </div>
        </div>

        <!-- kraken -->
        <div class="tab-pane fade" id="v-pills-krona" role="tabpanel" aria-labelledby="v-pills-krona-tab">
          @if(isset($project_user))
          <select id="kraken_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser">
            <div id="iframe_browser_header" class="overflow-auto">
              <div id="iframe_browser_title">Kraken Reports</div>
            </div>
          </div>
          <div id="kraken_report" class="kraken_report embed-responsive embed-responsive-4by3">
          </div>
        </div>

        <!-- blob -->
        <div class="tab-pane fade" id="v-pills-blob" role="tabpanel" aria-labelledby="v-pills-blob-tab">
          @if(isset($project_user))
          <select id="blob_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="blob_browser overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Blob Reports</div>
            </div>
          </div>
          @if(isset($sample_id))
          <img id="blob_image_sample" src='/blob?sample_uuid={{$sample_uuid}}&sample_name={{$file_prefix}}' width="100%" height="100%">
          @elseif(isset($project_user))
          <img id="blob_image" src="" width="100%" height="100%">
          @endif
        </div>

        <!-- preseq -->
        <div class="tab-pane fade" id="v-pills-preseq" role="tabpanel" aria-labelledby="v-pills-preseq-tab">
          @if(isset($project_user))
          <select id="preseq_proj_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($preseq_array as $preseq)
            <option value={{$preseq}}>{{$preseq}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="preseq_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Preseq Reports</div>
              @if(isset($sample_id))
              <ul id="preseq_tabs" class="d-flex">
                @foreach($preseq_array as $preseq)
                <li><a href="#" class="text-truncate">{{$preseq}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          <div id="preseq_report" class="w-100 overflow-hidden">
          </div>
        </div>

        <!-- arg -->
        <div class="tab-pane fade" id="v-pills-arg" role="tabpanel" aria-labelledby="v-pills-arg-tab">
          @if(isset($project_user))
          <select id="arg_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="arg_report mb-2 overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">ARG Reports</div>
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

        <!-- bowtie -->
        <div class="tab-pane fade" id="v-pills-bowtie" role="tabpanel" aria-labelledby="v-pills-bowtie-tab">
          @if(isset($project_user))
          <select id="bowtie_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="bowtie_report mb-2 overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Bowtie Reports</div>
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

        <!-- checkM -->
        <div class="tab-pane fade" id="v-pills-checkM" role="tabpanel" aria-labelledby="v-pills-checkM-tab">
          <div id="iframe_browser" class="checkM_report mb-2 overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">CheckM Reports</div>
            </div>
          </div>
          <div class="table-responsive">
            <table id="checkM_dataTable" class="display">
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
          <a id="v-pills-checkM-tab" data-toggle="pill" href="#v-pills-checkM" role="tab" aria-controls="v-pills-checkM" aria-selected="false">CheckM</a>
        </div>
      </div>
    </div>
  </div>
  @endsection
