@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action=" " method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Institutions-edit</h5>
                        <div class="input-group">
                            <input name="new-stiname" type="text" class="form-control" aria-describedby="sizing-addon2">
                        </div>
                        <button type="commit" class="btn btn-primary" style="margin-top:10px">Commit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
