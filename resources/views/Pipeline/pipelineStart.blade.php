@extends('layouts.app')

@section('content')
<div class="container">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class=""></div>
    <div class="col-md-12">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="weblog-tab" data-toggle="tab" href="#weblog" role="tab" aria-controls="weblog" aria-selected="false">Pipeline Status</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="configuration-tab" data-toggle="tab" href="#configuration" role="tab" aria-controls="configuration" aria-selected="true">Configuration</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
          <div class="d-flex">
            <div class="col-md-6 bg-white rounded shadow-sm p-3 rem1 overflow-auto">
              <div class="d-flex">
                <div class="rem15">Configuration</div>
                @if(isset($sample_id))
                <div class="p-2 ml-3"><span class="badge badge-info rem1">{{$samples->where('id',$sample_id)->value('sampleLabel')}}</span></div>
                @endif
              </div>
              <div class="d-flex border-bottom pb-3">
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
              </div>
              <div class="d-flex flex-wrap">
                <div class="custom-control custom-checkbox mt-2">
                  <input type="checkbox" class="custom-control-input" id="saveTrimmed" name="saveTrimmed" value="saveTrimmed" {{$saveTrimmed?'checked':''}} disabled>
                  <label class="custom-control-label" for="saveTrimmed">saveTrimmed</label>
                </div>
                <div class="custom-control custom-checkbox mt-2 ml-3">
                  <input type="checkbox" class="custom-control-input" id="saveAlignedIntermediates" name="saveAlignedIntermediates" value="saveAlignedIntermediates" {{$saveAlignedIntermediates?'checked':''}} disabled>
                  <label class="custom-control-label" for="saveAlignedIntermediates">saveAlignedIntermediates</label>
                </div>
              </div>
              <div class="d-flex flex-wrap">
                <div class="custom-control custom-checkbox mt-2">
                  <input type="checkbox" class="custom-control-input" id="euk" name="euk" value="euk" {{$euk?'checked':''}} disabled>
                  <label class="custom-control-label" for="euk">euk</label>
                </div>
                <div class="custom-control custom-checkbox mt-2 ml-3">
                  <input type="checkbox" class="custom-control-input" id="fungus" name="fungus" value="fungus" {{$fungus?'checked':''}} disabled>
                  <label class="custom-control-label" for="fungus">fungus</label>
                </div>
              </div>
              <div class="d-flex flex-wrap">
                <div class="custom-control custom-checkbox mt-2">
                  <input type="checkbox" class="custom-control-input" id="resume" name="resume" value="resume" {{$resume?'checked':''}} disabled>
                  <label class="custom-control-label" for="resume">
                    <span>resume</span>
                    <span class="text-danger">(pipeline will restart where terminated last time)</span>
                  </label>
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
                @isset($reference_genome)
                <div class="mt-2 d-flex">
                  <div class="ml-1 mt-2 mr-3">reference genome : </div>
                  <select class="form-control w-25" name="reference_genome" disabled>
                    <option value={{$reference_genome}} selected>{{$reference_genome}}</option>
                  </select>
                </div>
                @endisset
                <div class="mt-2 border-bottom pb-3">
                  <div class="custom-control custom-checkbox mr-4">
                    <input type="checkbox" class="custom-control-input" id="augustus_species" name="augustus_species" value="augustus_species" {{$augustus_species?'checked':''}} disabled>
                    <label class="custom-control-label" for="augustus_species">augustus_species</label>
                  </div>
                  <input type="text" name="augustus_species_name" class="w-50 form-control augustus_species_name text-secondary {{$augustus_species_name == null?'no-display':''}}" value={{$augustus_species_name == null?'':$augustus_species_name}}>
                </div>

                <div class="accordion mt-2" id="database_select">
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#database" aria-expanded="true" aria-controls="collapseOne">
                          Database
                        </button>
                      </h2>
                    </div>
                    <div id="database" class="collapse show" aria-labelledby="headingOne" data-parent="#database_select">
                      <div class="card-body">
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" value="resfinder_db" {{$resfinder_db?'checked':''}} disabled>
                            <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 resfinder_db_path overflow-auto {{$resfinder_db?'no-display':''}}">{{$pipelineParams->resfinder_db_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" value="nt_db" {{$nt_db?'checked':''}} disabled>
                            <label class="custom-control-label" for="nt_db">nt_db</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 nt_db_path overflow-auto {{$nt_db? 'no-display':''}}">{{$pipelineParams->nt_db_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" value="kraken_db" {{$kraken_db?'checked':''}} disabled>
                            <label class="custom-control-label" for="kraken_db">kraken_db</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 kraken_db_path overflow-auto {{$kraken_db ?'no-display':''}}">{{$pipelineParams->kraken_db_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" value="eggnog_db" {{$eggnog?'checked':''}} disabled>
                            <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 eggnog_db_path overflow-auto {{$eggnog ?'no-display':''}}">{{$pipelineParams->eggnog_db_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" value="kofam_profile" {{$kofam_profile?'checked':''}} disabled>
                            <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 kofam_profile_path overflow-auto {{$kofam_profile?'no-display':''}}">{{$pipelineParams->kofam_profile_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" value="kofam_kolist" {{$kofam_kolist?'checked':''}} disabled>
                            <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 kofam_kolist_path overflow-auto {{$kofam_kolist?'no-display':''}}">{{$pipelineParams->kofam_kolist_path}}</div>
                        </div>
                        <div class="mt-2">
                          <div class="custom-control custom-checkbox mr-4">
                            <input type="checkbox" class="custom-control-input" id="eukcc_db" name="eukcc_db" value="eukcc_db" {{$eukcc_db?'checked':''}} disabled>
                            <label class="custom-control-label" for="eukcc_db">eukcc_db</label>
                          </div>
                          <div class="d-none mt-2 w-50 border text-secondary rounded shadow p-2 eukcc_db_path overflow-auto {{$eukcc_db?'no-display':''}}">{{$pipelineParams->eukcc_db_path}}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 bg-white rounded shadow-sm p-3">Explain...</div>
          </div>
        </div>
        <div class="tab-pane fade show active" id="weblog" role="tabpanel" aria-labelledby="weblog-tab">
          <div class="bg-white rounded shadow-sm p-2">
            <div class="mt-2 rem15 text-primary border-bottom">Pipeline Status</div>
            <div class="command_out text-wrap text-break rounded">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
