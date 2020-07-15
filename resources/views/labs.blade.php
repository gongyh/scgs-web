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
                    RNA-Seq
                </div>
                <div class="result-detail">
                    <div class="labs">
                        <div class="table-responsive">
                            <table class="table table-condense">
                                <tr>
                                    <td class="table-header">ID</td>
                                    <td class="table-header">Labs</td>
                                </tr>
                                @foreach ($labs as $lab)
                                <tr>
                                    <td class="table-item">{{$lab->id}}</td>
                                    <td class="table-item"><a href='#'>{{$lab->name}}</a></td>
                                    <td>
                                        <a href="{{url('labs/delete',['id'=>$lab->id])}}" onclick="if(confirm('Are you sure to delete?')==false) return false;" class="btn btn-primary btn-sm">delete</a>
                                        <a href="{{url('labs/update',['id'=>$lab->id])}}" class="btn btn-primary btn-sm">edit</a>
                                    </td>
                                </tr>

                                @endforeach
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
                Structure
            </div>
            <div class="other-info">

            </div>
        </div>
    </div>

</div>
@endsection
