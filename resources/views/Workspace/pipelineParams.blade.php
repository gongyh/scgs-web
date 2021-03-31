@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <div class="form-group">
        <label for="resfinder_db_path" class="font-normal">resfinder database path</label>
        <input type="text" class="form-control" id="resfinder_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->resfinder_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="nt_db_path" class="font-normal">nt database path</label>
        <input type="text" class="form-control" id="nt_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->nt_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="kraken_db_path" class="font-normal">karken database path</label>
        <input type="text" class="form-control" id="kraken_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->kraken_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="eggnog_db_path" class="font-normal">eggnog database path</label>
        <input type="text" class="form-control" id="eggnog_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->eggnog_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="kofam_profile_path" class="font-normal">kofam profile path</label>
        <input type="text" class="form-control" id="kofam_profile_path" readonly value={{isset($pipelineParams)?$pipelineParams->kofam_profile_path:''}}>
      </div>
      <div class="form-group">
        <label for="kofam_kolist_path" class="font-normal">kofam kolist path</label>
        <input type="text" class="form-control" id="kofam_kolist_path" readonly value={{isset($pipelineParams)?$pipelineParams->kofam_kolist_path:''}}>
      </div>
      <div class="form-group">
        <label for="eukcc_db_path" class="font-normal">eukcc database path</label>
        <input type="text" class="form-control" id="eukcc_db_path" readonly value={{isset($pipelineParams)?$pipelineParams->eukcc_db_path:''}}>
      </div>
      <div class="form-group">
        <label for="nextflow_path" class="font-normal">nextflow path</label>
        <input type="text" class="form-control" id="nextflow_path" readonly value={{isset($pipelineParams)?$pipelineParams->nextflow_path:''}}>
      </div>
      <div class="form-group">
        <label for="nf_core_scgs_path" class="font-normal">nf-core-scgs path</label>
        <input type="text" class="form-control" id="nf_core_scgs_path" readonly value={{isset($pipelineParams)?$pipelineParams->nf_core_scgs_path:''}}>
      </div>
      <div class="font-normal">nextflow profile</div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="nextflow_profile" id="inlineRadio1" value="Local" disabled {{$pipelineParams->nextflow_profile == 'Local'?'checked':''}}>
        <label class="form-check-label font-normal" for="inlineRadio1">Local</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="nextflow_profile" id="inlineRadio2" value="Kubernetes" disabled {{$pipelineParams->nextflow_profile == 'Kubernetes'?'checked':''}}>
        <label class="form-check-label font-normal" for="inlineRadio2">Kubernetes</label>
      </div>
      <!-- error message -->
      <div class="mt-4 d-flex justify-content-around">
        <a href="/workspace/pipelineParams/update" class="btn btn-success">Edit</a>
        <button class="btn btn-secondary" disabled>Save</button>
      </div>
    </div>
  </div>
</div>
@endsection
