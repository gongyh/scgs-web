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
                                    <td class="table-header">Samples</td>
                                    <td class="table-header">Species</td>
                                </tr>
                                @foreach ($samples as $sample)
                                <tr>
                                    <td class="table-item">{{$sample->id}}</td>
                                    <td class="table-item"><a href='#'>{{$sample->name}}</a></td>
                                    <td class="table-item">{{$sample->species}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        {{$samples->links()}}
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
