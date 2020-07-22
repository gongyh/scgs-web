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
                    <th scope="col" class="table-header">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @if($selectLabs !== null)
                  @foreach ($selectLabs as $selectLab)
                  <tr>
                    <th scope="row" class="table-item">{{$selectLab->id}}</td>
                    <td class="table-item">
                      <a href="/labPasswd?labID={{$selectLab->id}}">{{$selectLab->name}}</a>
                    </td>
                    <td class="table-item">
                      <a href="{{$isPI ? url('labs/delete',['id'=>$selectLab->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '' : 'disabled'}}" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                      <a href="{{$isPI ? url('labs/update',['id'=>$selectLab->id]):'javascript:void(0)'}}" class="btn btn-primary btn-sm {{$isPI ? '':'disabled'}} ">edit</a>
                    </td>
                  </tr>
                  @endforeach
                  @endif

                </tbody>
              </table>
            </div>
            @if($selectLabs !== null)
            {{$selectLabs->links()}}
            @endif
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
