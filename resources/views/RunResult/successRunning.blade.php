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
      <div class="bg-white p-3 rounded shadow">
        <div class="rem15 font-weight-bold font-italic text-dark border-bottom pb-2">Job Information</div>
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
        <div class="rem15 font-italic">command</div>
        <div class="rem1 bg-light rounded">{{$command}}</div>
      </div>
      <div class="download_zip mt-5">
        <a href="/successRunning/resultDownload?sampleID={{$sample_id}}">
          <img src="{{asset('/images/zip.jpg')}}" class="download_result" alt="Responsive image">
          <span class="rem1">download results.zip</span>
        </a>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3">
      <div class="result-info">
        <div class="rem15 text-info">Results</div>
        <div class="menu overflow-auto rem1">
          <a class="fancy-hover border-bottom">reference genome</a>
          <a class="fancy-hover border-bottom">fastqc</a>
          <a class="fancy-hover border-bottom">trim_galore</a>
          <a class="fancy-hover border-bottom">bowtie2</a>
          <a class="fancy-hover border-bottom">circlize</a>
          <a class="fancy-hover border-bottom">gatk</a>
          <a class="fancy-hover border-bottom">preseq</a>
          <a class="fancy-hover border-bottom">monovar</a>
          <a class="fancy-hover border-bottom">aneufinder</a>
        </div>
      </div>
    </div>
    @endsection
