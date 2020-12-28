@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pipeline Status</li>
          </li>
        </ol>
      </nav>
      <div class="bg-white p-3 rounded shadow-sm">
        <div class="rem15 font-weight-bold text-success border-bottom pb-2">Job List</div>
        <div class="table-response">
          <table class="table table-condense">
            <thead>
              <tr>
                <th scope="col">Project</th>
                <th scope="col">Sample</th>
                <th scope="col">Job Status</th>
                <th scope="col">Start Time</th>
                <th scope="col">Finish Time</th>
                <th scope="col">Time Period</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user_jobs as $user_job)
              @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
              <tr class="table-warning">
                @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('sample_id') !== null)
                <td></td>
                <th>
                  <a href="/execute/start?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('library_id')}}</a>
                </th>
                @else
                <th>
                  <a href="/execute/start?projectID={{$user_job->project_id}}">{{$projects->where('id',$user_job->project_id)->value('doi')}}</a>
                </th>
                <td></td>
                @endif
                <td>
                  <span class="badge badge-primary">
                    <span>Running</span>
                    <span class="dot">...</span>
                  </span>
                </td>
                <td class="start_time">{{$user_job->started}}</td>
                <td> -- </td>
                <td class="Run_time">{{$now - $user_job->started}}</td>
              </tr>
              @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
              <tr class="table-danger">
                @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('sample_id') !== null)
                <td></td>
                <th>
                  <a href="/failedRunning?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('library_id')}}</a>
                </th>
                @else
                <th>
                  <a href="">{{$projects->where('id',$user_job->project_id)->value('doi')}}</a>
                </th>
                <td></td>
                @endif
                <td>
                  <span class="badge badge-danger">failed</span>
                </td>
                <td class="start_time">{{$user_job->started}}</td>
                <td class="finish_time">{{$user_job->finished}}</td>
                <td class="Run_time">{{$user_job->finished - $user_job->started}}</td>
              </tr>
              @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
              <tr class="table-success">
                @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('sample_id') !== null)
                <td></td>
                <th>
                  <a href="/successRunning?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('library_id')}}</a>
                </th>
                @else
                <th>
                  <a href="/successRunning?projectID={{$user_job->project_id}}">{{$projects->where('id',$user_job->project_id)->value('doi')}}</a>
                </th>
                <td></td>
                @endif
                <td>
                  <span class="badge badge-success">success</span>
                </td>
                <td class="start_time">{{$user_job->started}}</td>
                <td class="finish_time">{{$user_job->finished}}</td>
                <td class="Run_time">{{$user_job->finished - $user_job->started}}</td>
              </tr>
              @endif
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
