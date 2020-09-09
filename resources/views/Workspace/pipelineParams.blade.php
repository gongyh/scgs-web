@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="resfinder_db_path">resfinder database path</label>
        <input type="text" class="form-control" id="resfinder_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->resfinder_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="nt_db_path">nt database path</label>
        <input type="text" class="form-control" id="nt_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->nt_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="kraken_db_path">karken database path</label>
        <input type="text" class="form-control" id="kraken_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->kraken_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="eggnog_db_path">eggnog database path</label>
        <input type="text" class="form-control" id="eggnog_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->eggnog_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="kofam_profile_path">kofam profile path</label>
        <input type="text" class="form-control" id="kofam_profile_path" readonly value={{isset($pipelineParams)?$pipelineParams->kofam_profile_path:''}}>
      </div>
      <div class="form-group">
        <label for="kofam_kolist_path">kofam kolist path</label>
        <input type="text" class="form-control" id="kofam_kolist_path" readonly value={{isset($pipelineParams)?$pipelineParams->kofam_kolist_path:''}}>
      </div>
      <!-- error message -->
      <div class="mt-5 d-flex justify-content-around">
        <a href="/workspace/pipelineParams/update" class="btn btn-success">Edit</a>
        <button class="btn btn-secondary" disabled>Save</button>
      </div>
    </div>
  </div>
</div>
@endsection
