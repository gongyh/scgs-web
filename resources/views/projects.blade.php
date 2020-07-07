<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Scgs-Web</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/common.css') }}" rel="stylesheet">
        <style>
            .project-item{
                height:80px;
                padding:20px;
                font-size:18px;
                line-height: 18px;
                font-family:Consolas;
                display:flex;
            }
            .sample{
                margin-bottom:30px;
                font-weight: bolder;
            }
            .desc{
                font-weight: bolder;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <!-- header -->
            <div class="row">
                <div class="header col-sm-8">scgs-web</div>
                <div class="header col-sm-4"><a href="#" class="login">Login</a></div>
            </div>
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
                            <div class="projects">
                                <div class="project-item">
                                    <div class="desc">project name:</div>
                                    <div>WGA of SARS-CoV-2 amplified using ARTIC V3 protocol</div>
                                </div>
                                <div class="project-item">
                                    <div class="desc">Description:</div>
                                    <div>FISABIO - Public Health</div>
                                </div>
                                <div class="project-item">
                                    <div class="sample">Samples:</div>
                                        <table class="table table-bordered">
                                            <tr><td>sampleId</td><td>sampleLabel</td></tr>
                                            <tr><td>...</td><td>...</td></tr>
                                        </table>
                                </div>
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
            <!-- footer -->
            <div class="row footer">
                <div class="col-sm-8">
                    <ol class="breadcrumb">
                        <li><a href="#">NGS database</a></li>
                        <li class="active">Samples</li>
                    </ol>
                </div>
                <div class="col-sm-4 microfooternav">
                    <ul class="nav nav-pills">
                        <li><a href="#">Bioinformatics Platform</a></li>
                        <li><a href="#">scgs - website</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
