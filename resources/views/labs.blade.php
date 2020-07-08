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
                    <div class="labs">
                        <table class="table table-bordered">
                            <tr>
                                <td><b>ID</b></td>
                                <td><b>Name</b></td>
                            </tr>
                            <tr>
                                <td><b>10001</b></td>
                                <td><b>lab_name</b></td>
                            </tr>
                        </table>
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
