@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item active" aria-current="page">Manage Running Samples/Projects</li>
          </li>
        </ol>
      </nav>
      <div class="bg-white p-3 rounded shadow-sm">
        <div class="rem15 font-weight-bold text-success border-bottom pb-2">Running List</div>
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
                <th scope="col">Operation</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($running_jobs as $running_job)
              <tr class="table-warning">
                @if(DB::table('jobs')->where('uuid',$running_job->uuid)->value('sample_id') !== null)
                <td></td>
                <th>{{$samples->where('id',$running_job->sample_id)->value('library_id')}}</th>
                @else
                <th>{{$projects->where('id',$running_job->project_id)->value('doi')}}</th>
                <td></td>
                @endif
                <td>
                  <span class="badge badge-primary">
                    <span>Running</span>
                    <span class="dot">...</span>
                  </span>
                </td>
                <td class="start_time">{{$running_job->started}}</td>
                <td></td>
                <td class="Run_time">{{$now - $running_job->started}}</td>
                <td><a class="btn btn-sm btn-danger ml-2" href="/workspace/manageRunning/terminate?uuid={{$running_job->current_uuid}}">STOP</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
