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
      <div class="bg-white rounded shadow-sm p-3 rem1">
        <div class="d-flex">
          <div class="rem15">execute params chosen</div>
          <div class="p-2 rem1 ml-3 text-dark">{{$samples->where('id',$sample_id)->value('sampleLabel')}}<span class="badge badge-success badge-pill">Running</span></div>
        </div>
        <div class="d-flex flex-wrap">
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="ass" name="ass" value="ass" {{$ass?'checked':''}} disabled>
            <label class="custom-control-label" for="ass">ass</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 ml-3">
            <input type="checkbox" class="custom-control-input" id="cnv" name="cnv" value="cnv" {{$cnv?'checked':''}} disabled>
            <label class="custom-control-label" for="cnv">cnv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 ml-3">
            <input type="checkbox" class="custom-control-input" id="snv" name="snv" value="snv" {{$snv?'checked':''}} disabled>
            <label class="custom-control-label" for="snv">snv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 ml-3">
            <input type="checkbox" class="custom-control-input" id="bulk" name="bulk" value="bulk" {{$bulk?'checked':''}} disabled>
            <label class="custom-control-label" for="bulk">bulk</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 ml-3">
            <input type="checkbox" class="custom-control-input" id="saturation" name="saturation" value="saturation" {{$saturation?'checked':''}} disabled>
            <label class="custom-control-label" for="saturation">saturation</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 ml-3">
            <input type="checkbox" class="custom-control-input" id="acquired" name="acquired" value="acquired" {{$acquired?'checked':''}} disabled>
            <label class="custom-control-label" for="acquired ml-3">acquired</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="saveTrimmed" name="saveTrimmed" value="saveTrimmed" {{$saveTrimmed?'checked':''}} disabled>
            <label class="custom-control-label" for="saveTrimmed"><span>saveTrimmed</span><span class="text-danger">(Save the trimmed Fastq files in the the Results directory.)</span></label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="saveAlignedIntermediates" name="saveAlignedIntermediates" value="saveAlignedIntermediates" {{$saveAlignedIntermediates?'checked':''}} disabled>
            <label class="custom-control-label" for="saveAlignedIntermediates"><span>saveAlignedIntermediates</span><span class="text-danger">(Save the intermediate BAM files from the Alignment step - not done by default)</span></label>
          </div>
        </div>
        <div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="genus" name="genus" value="genus" {{$genus?'checked':''}} disabled>
              <label class="custom-control-label" for="genus">genus</label>
            </div>
            <input type="text" name="genus_name" class="w-50 form-control genus_name text-secondary {{$genus_name == null?'no-display':''}}" value={{$genus_name == null?'':$genus_name}}>
          </div>

          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" value="resfinder_db" {{$resfinder_db?'checked':''}} disabled>
              <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 resfinder_db_path {{$resfinder_db?'no-display':''}}">{{$pipelineParams->resfinder_db_path}}</div>
          </div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" value="nt_db" {{$nt_db?'checked':''}} disabled>
              <label class="custom-control-label" for="nt_db">nt_db</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 nt_db_path {{$nt_db? 'no-display':''}}">{{$pipelineParams->nt_db_path}}</div>
          </div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" value="kraken_db" {{$kraken_db?'checked':''}} disabled>
              <label class="custom-control-label" for="kraken_db">kraken_db</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 kraken_db_path {{$kraken_db ?'no-display':''}}">{{$pipelineParams->kraken_db_path}}</div>
          </div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" value="eggnog_db" {{$eggnog?'checked':''}} disabled>
              <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 eggnog_db_path {{$eggnog ?'no-display':''}}">{{$pipelineParams->eggnog_db_path}}</div>
          </div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" value="kofam_profile" {{$kofam_profile?'checked':''}} disabled>
              <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 kofam_profile_path {{$kofam_profile?'no-display':''}}">{{$pipelineParams->kofam_profile_path}}</div>
          </div>
          <div class="mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" value="kofam_kolist" {{$kofam_kolist?'checked':''}} disabled>
              <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
            </div>
            <div class="mt-2 w-50 border text-secondary rounded shadow p-2 kofam_kolist_path {{$kofam_kolist?'no-display':''}}">{{$pipelineParams->kofam_kolist_path}}</div>
          </div>
        </div>
        <div class="mt-5 d-flex justify-content-around">
          <button class="btn btn-secondary w-25" disabled>execute</button>
          <button class="btn btn-success w-25 detail">Check Progress</button>
          <button class="btn btn-success w-25">stop</button>
        </div>
      </div>

    </div>
    <!-- right-column -->
    <div class="col-md-3 right-column">
      <div class="bg-white rounded shadow-sm p-2">
        <div class="console-header text-success">
          <span>Pipeline is running</span><span class="dot">...</span>
        </div>
        <div class="mt-2 progress">
          <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="mt-2 rem15 text-primary border-bottom">Pipeline Status</div>
        <div class="command_out d-none text-nowrap overflow-auto">
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
