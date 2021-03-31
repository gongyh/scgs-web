@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <form action="" method="POST">
        @csrf
        <div class="form-group">
          <label for="resfinder_db_path" class="font-normal">resfinder database path</label>
          <input type="text" class="form-control" id="resfinder_db_path" name="resfinder_db_path" value={{isset($pipelineParams)?$pipelineParams->resfinder_db_path:''}}>
        </div>
        <div class="form-group">
          <label for="nt_db_path" class="font-normal">nt database path</label>
          <input type="text" class="form-control" id="nt_db_path" name="nt_db_path" value={{isset($pipelineParams)?$pipelineParams->nt_db_path:''}}>
        </div>
        <div class="form-group">
          <label for="kraken_db_path" class="font-normal">kraken database path</label>
          <input type="text" class="form-control" id="kraken_db_path" name="kraken_db_path" value={{isset($pipelineParams)?$pipelineParams->kraken_db_path:''}}>
        </div>
        <div class="form-group">
          <label for="eggnog_db_path" class="font-normal">eggnog database path</label>
          <input type="text" class="form-control" id="eggnog_db_path" name="eggnog_db_path" value={{isset($pipelineParams)?$pipelineParams->eggnog_db_path:''}}>
        </div>
        <div class="form-group">
          <label for="kofam_profile_path" class="font-normal">kofam profile path</label>
          <input type="text" class="form-control" id="kofam_profile_path" name="kofam_profile_path" value={{isset($pipelineParams)?$pipelineParams->kofam_profile_path:''}}>
        </div>
        <div class="form-group">
          <label for="kofam_kolist_path" class="font-normal">kofam kolist path</label>
          <input type="text" class="form-control" id="kofam_kolist_path" name="kofam_kolist_path" value={{isset($pipelineParams)?$pipelineParams->kofam_kolist_path:''}}>
        </div>
        <div class="form-group">
          <label for="eukcc_db_path" class="font-normal">eukcc database path</label>
          <input type="text" class="form-control" id="eukcc_db_path" name="eukcc_db_path" value={{isset($pipelineParams)?$pipelineParams->eukcc_db_path:''}}>
        </div>
        <div class="form-group">
          <label for="nextflow_path" class="font-normal">nextflow path</label>
          <input type="text" class="form-control" id="nextflow_path" name="nextflow_path" value={{isset($pipelineParams)?$pipelineParams->nextflow_path:''}}>
        </div>
        <div class="form-group">
          <label for="nf_core_scgs_path" class="font-normal">nf-core-scgs path</label>
          <input type="text" class="form-control" id="nf_core_scgs_path" name="nf_core_scgs_path" value={{isset($pipelineParams)?$pipelineParams->nf_core_scgs_path:''}}>
        </div>
        <div class="font-normal">nextflow profile</div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="nextflow_profile" id="inlineRadio1" value="Local" {{(empty($pipelineParams)|| $pipelineParams->nextflow_profile == 'Local') ? 'checked' : ''}}>
          <label class="form-check-label font-normal" for="inlineRadio1">Local</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="nextflow_profile" id="inlineRadio2" value="Kubernetes" {{$pipelineParams->nextflow_profile == 'Kubernetes' ? 'checked' : ''}}>
          <label class="form-check-label font-normal" for="inlineRadio2">Kubernetes</label>
        </div>
        <!-- error message -->
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
              @endforeach
          </ul>
        </div>
        @endif
        @if (isset($db_path_errors))
        <div class="alert alert-danger">
          <ul>
            @foreach ($db_path_errors as $db_path_error)
            <li>{{ $db_path_error }}</li>
              @endforeach
          </ul>
        </div>
        @endif
        <div class="mt-5 d-flex justify-content-around">
          <button class="btn btn-secondary" disabled>Edit</button>
          <button class="btn btn-success" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
