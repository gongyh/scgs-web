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
      <div class="bg-white rounded shadow-sm p-3 exec-params">
        <div class="exec-title">execute params setting</div>
        <div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="ass" name="ass" value="ass" {{$ass?'checked':''}}>
            <label class="custom-control-label" for="ass">ass</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="cnv" name="cnv" value="cnv" {{$cnv?'checked':''}}>
            <label class="custom-control-label" for="cnv">cnv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="snv" name="snv" value="snv" {{$snv?'checked':''}}>
            <label class="custom-control-label" for="snv">snv</label>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="genus" name="genus" value="genus" {{$genus?'checked':''}}>
              <label class="custom-control-label" for="genus">genus</label>
            </div>
            <input type="text" name="genus_name" class="form-control genus_name {{$genus_name == null?'no-display':''}}" value={{$genus_name == null?'':$genus_name}}>
          </div>

          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" value="resfinder_db" {{$resfinder_db?'checked':''}}>
              <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 resfinder_db_path {{$resfinder_db?'no-display':''}}">{{$pipelineParams->resfinder_db_path}}</div>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" value="nt_db" {{$nt_db?'checked':''}}>
              <label class="custom-control-label" for="nt_db">nt_db</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 nt_db_path {{$nt_db? 'no-display':''}}">{{$pipelineParams->nt_db_path}}</div>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" value="kraken_db" {{$kraken_db?'checked':''}}>
              <label class="custom-control-label" for="kraken_db">kraken_db</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 kraken_db_path {{$kraken_db ?'no-display':''}}">{{$pipelineParams->kraken_db_path}}</div>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" value="eggnog_db" {{$eggnog?'checked':''}}>
              <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 eggnog_db_path {{$eggnog ?'no-display':''}}">{{$pipelineParams->eggnog_db_path}}</div>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" value="kofam_profile" {{$kofam_profile?'checked':''}}>
              <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 kofam_profile_path {{$kofam_profile?'no-display':''}}">{{$pipelineParams->kofam_profile_path}}</div>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" value="kofam_kolist" {{$kofam_kolist?'checked':''}}>
              <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
            </div>
            <div class="border db_path rounded shadow-sm p-2 kofam_kolist_path {{$kofam_kolist?'no-display':''}}">{{$pipelineParams->kofam_kolist_path}}</div>
          </div>
        </div>
        <div class="mt-5 d-flex justify-content-around">
          <button class="btn btn-secondary" disabled>execute</button>
          <button class="btn btn-success">stop</button>
        </div>
        </form>
      </div>

    </div>
    <!-- right-column -->
    <div class="col-md-3 right-column">

      <div class="bg-white">
        console
      </div>
    </div>
  </div>

</div>
@endsection