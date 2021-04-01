@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item active" aria-current="page">Manage Waiting Samples/Projects</li>
          </li>
        </ol>
      </nav>
      <div class="bg-white p-3 rounded shadow-sm">
        <div class="rem15 font-weight-bold text-success border-bottom pb-2">Waiting List</div>
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
              @foreach ($waiting_jobs as $waiting_job)
              <tr class="table-warning">
                @if($waiting_job->sample_id !== null)
                <td></td>
                <th>{{$samples->where('id',$waiting_job->sample_id)->value('library_id')}}</th>
                @else
                <th>{{$projects->where('id',$waiting_job->project_id)->value('doi')}}</th>
                <td></td>
                @endif
                <td>
                  <span class="badge badge-primary">
                    <span>Waiting</span>
                    <span class="dot">...</span>
                  </span>
                </td>
                <td> -- </td>
                <td></td>
                <td> -- </td>
                <td>
                @if($waiting_job->sample_id !== null)
                <a class="btn btn-sm btn-danger ml-2" href="/workspace/manageWaiting/terminate?sampleID={{$waiting_job->sample_id}}">STOP</a>
                @else
                <a class="btn btn-sm btn-danger ml-2" href="/workspace/manageWaiting/terminate?projectID={{$waiting_job->project_id}}">STOP</a>
                @endif
                </td>
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
