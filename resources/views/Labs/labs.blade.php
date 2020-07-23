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
      </div>
    </div>
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome to SCGS-Web!
      </div>
      <div class="result">
        <div class="result-info">
          Lab
        </div>
        <div class="result-detail">
          <div class="labs">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead>
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Labs</th>
                    <th scope="col" class="table-header"><a href="labs/create"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        </svg></a></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($labs as $lab)
                  <tr>
                    <th scope="row" class="table-item">{{$lab->id}}</td>
                    <td class="table-item"><a href='/labPasswd?labID={{$lab->id}}'>{{$lab->name}}</a></td>
                    <td class="table-item">
                      <a href="{{$isPI ? url('labs/delete',['id'=>$lab->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '' : 'disabled'}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                      <a href="{{$isPI ? url('labs/update',['id'=>$lab->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '':'disabled'}} ">edit</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{$labs->links()}}
          </div>
        </div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-sm-3 right-column">
      <div class="others">
        Notice
      </div>
      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
