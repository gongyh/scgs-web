@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <div class="bg-white p-3 rounded shadow-sm">
        <div class="rem15 font-weight-bold font-italic text-success border-bottom pb-2">Job List</div>
        <div class="table-response">
          <table class="table table-condense">
            <thead>
              <tr>
                <th scope="col">Sample Label</th>
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
                <th>
                  <a href="/execute/start?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}</a>
                </th>
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
                <th>
                  <a href="/failedRunning?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}</a>
                </th>
                <td>
                  <span class="badge badge-danger">failed</span>
                </td>
                <td class="start_time">{{$user_job->started}}</td>
                <td class="finish_time">{{$user_job->finished}}</td>
                <td class="Run_time">{{$now - $user_job->started}}</td>
              </tr>
              @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
              <tr class="table-success">
                <th>
                  <a href="/successRunning?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}</a>
                </th>
                <td>
                  <span class="badge badge-success">success</span>
                </td>
                <td class="start_time">{{$user_job->started}}</td>
                <td class="finish_time">{{$user_job->finished}}</td>
                <td class="Run_time">{{$now - $user_job->started}}</td>
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
