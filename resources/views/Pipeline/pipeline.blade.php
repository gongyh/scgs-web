@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-6">
      <!-- <div class="middle-info">

      </div> -->
      <div class="bg-white rounded shadow-sm p-3 exec-params overflow-auto">
        <form action="" method="POST">
          @csrf
          <div class="text-primary rem15">execute params setting</div>
          <div class="d-flex">
            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" class="custom-control-input" id="ass" name="ass" value="ass" {{$ass?'checked':''}}>
              <label class="custom-control-label" for="ass">ass</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="cnv" name="cnv" value="cnv" {{$cnv?'checked':''}}>
              <label class="custom-control-label" for="cnv">cnv</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="snv" name="snv" value="snv" {{$snv?'checked':''}}>
              <label class="custom-control-label" for="snv">snv</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="bulk" name="bulk" value="bulk" {{$bulk?'checked':''}}>
              <label class="custom-control-label" for="bulk">bulk</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="saturation" name="saturation" value="saturation" {{$saturation?'checked':''}}>
              <label class="custom-control-label" for="saturation">saturation</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="acquired" name="acquired" value="acquired" {{$acquired?'checked':''}}>
              <label class="custom-control-label" for="acquired">acquired</label>
            </div>
          </div>
          <div class="d-flex flex-wrap">
            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" class="custom-control-input" id="saveTrimmed" name="saveTrimmed" value="saveTrimmed" {{$saveTrimmed?'checked':''}}>
              <label class="custom-control-label" for="saveTrimmed">saveTrimmed</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="saveAlignedIntermediates" name="saveAlignedIntermediates" value="saveAlignedIntermediates" {{$saveAlignedIntermediates?'checked':''}}>
              <label class="custom-control-label" for="saveAlignedIntermediates">saveAlignedIntermediates</label>
            </div>
          </div>
          <div class="d-flex flex-wrap">
            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" class="custom-control-input" id="euk" name="euk" value="euk" {{$euk?'checked':''}}>
              <label class="custom-control-label" for="euk">euk</label>
            </div>
            <div class="custom-control custom-checkbox mt-2 ml-3">
              <input type="checkbox" class="custom-control-input" id="fungus" name="fungus" value="fungus" {{$fungus?'checked':''}}>
              <label class="custom-control-label" for="fungus">fungus</label>
            </div>
          </div>
          <div class="d-flex flex-wrap">
            <div class="custom-control custom-checkbox mt-2">
              <input type="checkbox" class="custom-control-input" id="resume" name="resume" value="resume" {{$resume?'checked':''}}>
              <label class="custom-control-label" for="resume">
                <span>resume</span>
                <span class="text-danger">(pipeline will restart where terminated last time)</span>
              </label>
            </div>
          </div>
          <div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="genus" name="genus" value="genus" {{$genus?'checked':''}}>
                <label class="custom-control-label" for="genus">genus</label>
              </div>
              <input type="text" name="genus_name" class="mt-2 w-50 form-control genus_name {{$genus_name == null?'no-display':''}}" value={{$genus_name == null?'':$genus_name}}>
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
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" value="resfinder_db" {{$resfinder_db?'checked':''}}>
                <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 resfinder_db_path overflow-auto {{$resfinder_db?'no-display':''}}">{{isset($pipelineParams->resfinder_db_path)?$pipelineParams->resfinder_db_path:'resfinder db path has not set!please call administor'}}</div>
            </div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" value="nt_db" {{$nt_db?'checked':''}}>
                <label class="custom-control-label" for="nt_db">nt_db</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 nt_db_path overflow-auto {{$nt_db? 'no-display':''}}">{{isset($pipelineParams->nt_db_path)?$pipelineParams->nt_db_path:'nt db path has not set!please call administor'}}</div>
            </div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" value="kraken_db" {{$kraken_db?'checked':''}}>
                <label class="custom-control-label" for="kraken_db">kraken_db</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 kraken_db_path overflow-auto {{$kraken_db ?'no-display':''}}">{{isset($pipelineParams->kraken_db_path)?$pipelineParams->kraken_db_path:'kraken db path has not set!please call administor'}}</div>
            </div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" value="eggnog_db" {{$eggnog?'checked':''}}>
                <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 eggnog_db_path overflow-auto {{$eggnog ?'no-display':''}}">{{isset($pipelineParams->eggnog_db_path)?$pipelineParams->eggnog_db_path:'eggnog db path has not set!please call administor'}}</div>
            </div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" value="kofam_profile" {{$kofam_profile?'checked':''}}>
                <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 kofam_profile_path overflow-auto {{$kofam_profile?'no-display':''}}">{{isset($pipelineParams->kofam_profile_path)?$pipelineParams->kofam_profile_path:'kofam profile path has not set!please call administor'}}</div>
            </div>
            <div class="mt-2">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" value="kofam_kolist" {{$kofam_kolist?'checked':''}}>
                <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
              </div>
              <div class="mt-2 w-50 border border-info rounded shadow p-2 kofam_kolist_path overflow-auto {{$kofam_kolist?'no-display':''}}">{{isset($pipelineParams->kofam_kolist_path)?$pipelineParams->kofam_kolist_path:'kofam kolist path has not set!please call administor'}}</div>
            </div>
          </div>
          <button type="submit" class="mt-5 ml-3 w-50 btn btn-success d-inline-block" onclick="if(confirm('when pipeline start,params can\' be modified until pipeline finished,Are you sure to execute?')==false) return false;" {{$can_exec?'':'disabled'}}>execute</button>
        </form>
      </div>
    </div>
    <!-- right-column -->
    <div class=" col-md-3 right-column">
      <div class="other-info">
      </div>
    </div>
  </div>
</div>
@endsection
