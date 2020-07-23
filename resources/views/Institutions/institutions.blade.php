@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-sm-3 left-column">
      <div class="list-group list">
        <a class="list-group-item" href="/institutions">Institutions</a>
      </div>
    </div>

    <!-- middle column -->
    <div class="col-sm-6 middle-column">
      <div class="middle-info">
        Welcome to SCGS-Web!
      </div>
      <div class="result">
        <div class="result-info">
          Institution
        </div>
        <div class="result-detail">
          <div class="institutions">
            <div class="table-responsive">
              <table class="table table-condense">
                <thead>
                  <tr>
                    <th scope="col" class="table-header">ID</th>
                    <th scope="col" class="table-header">Institutions</th>
                    @if($isAdmin)
                    <th scope="col" class="table-header"><a href="institutions/create"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z" />
                          <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                        </svg></a></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($institutions as $institution)
                  <tr>
                    <th scope="row" class="table-item" name="insti-id">{{$institution->id}}</td>
                    <td class="table-item" name="insti-name">
                      <a href="/institutions/labs?instiID={{$institution->id}}">{{$institution->name}}</a></td>
                    @if($isAdmin)
                    <td class="table-item">
                      <a href="{{url('institutions/delete',['id'=>$institution->id])}}" class="btn btn-primary btn-sm" onclick="if(confirm('Are you sure to delete?')==false) return false;">delete</a>
                      <a href="{{url('institutions/update',['id'=>$institution->id])}}" class="btn btn-primary btn-sm">edit</a>
                    </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{$institutions->links()}}
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
