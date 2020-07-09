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
                <a class="list-group-item" href="/">Samples</a>
                <a class="list-group-item" href="/">Species</a>
                <a class="list-group-item" href="/">Status</a>
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
                    <div class="institutions">
                        <table class="table table-condense">
                            <tr><td class="table-header">institutions</td></tr>
                           @foreach ($institutions as $institution)
                            <tr><td class="table-item">{{$institution->name}}</td></tr>
                           @endforeach
                        </table>
                        {{$institutions->links()}}
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

@section('style')
.table-header {
    text-align:center;
    font:bold 22px "Times New Roman"
}
.table-item {
    text-align:center;
}
.pagination{
    margin-left:25%;
}
@endsection

