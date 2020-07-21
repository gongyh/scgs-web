@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-sm-3 left-column">
      <div class="list-group list">
        <a class="list-group-item" href="/institutions">Institutions</a>
        <a class="list-group-item" href="/labs">Labs</a>
        <a class="list-group-item" href="/projects">Projects</a>
        <a class="list-group-item" href="/samples">Samples</a>
        <a class="list-group-item" href="/status">Status</a>
      </div>
    </div>
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome!
      </div>
      <div class="result">
        <div class="result-info">
          {{$project->name}}
        </div>
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
                  <th scope="col">SampleLabel</th>
                  <th scope="col">Species</th>
                </tr>
              </thead>
              <tbody>
                <th scope="row">##</th>
                <td>XXXX</td>
                <td>XXXX</td>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3 right-column">
      <div class="others">
        Structure
      </div>
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
