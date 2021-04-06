@extends('layouts.app')

@section('content')
<div class="container">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class=""></div>
    <div class="col-md-8">
      <!-- <div class="middle-info">

      </div> -->
      <div class="bg-white rounded shadow-sm p-3 exec-params overflow-auto">
        <form action="" method="POST">
          @csrf
          <div class="text-primary rem15">Configuration</div>
          <div class="d-flex border-bottom pb-3">
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
            @isset($default_reference)
            <div class="mt-2 d-flex">
              <div class="ml-1 mt-2 mr-3">reference genome : </div>
              <select class="form-control w-25" name="reference_genome">
                <option class="font-italic" value="denovo">de novo</option>
                @foreach($species_list as $species)
                <option value={{$species->id}} {{$species->name == $default_reference?'selected':''}}>{{$species->name}}</option>
                @endforeach
              </select>
            </div>
            @endisset
            <div class="mt-2 border-bottom pb-3">
              <div class="custom-control custom-checkbox mr-4">
                <input type="checkbox" class="custom-control-input" id="augustus_species" name="augustus_species" value="augustus_species" {{$augustus_species?'checked':''}}>
                <label class="custom-control-label" for="augustus_species">augustus species</label>
              </div>
              <select class="custom-select mt-2 w-50 augustus_species_name {{$augustus_species_name == null?'no-display':''}}" name=" augustus_species_name" id="augustus_species_name">
                <option selected value="saccharomyces">saccharomyces</option>
                @foreach($augustus_species_lists as $augustus_species_list)
                <option value={{$augustus_species_list}}>{{$augustus_species_list}}</option>
                @endforeach
              </select>
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
                        <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" value="resfinder_db" {{$resfinder_db?'checked':''}}>
                        <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 resfinder_db_path overflow-auto {{$resfinder_db?'no-display':''}}">{{isset($PipelineParams->resfinder_db_path)?$PipelineParams->resfinder_db_path:'The resfinder db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" value="nt_db" {{$nt_db?'checked':''}}>
                        <label class="custom-control-label" for="nt_db">nt_db</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 nt_db_path overflow-auto {{$nt_db? 'no-display':''}}">{{isset($PipelineParams->nt_db_path)?$PipelineParams->nt_db_path:'The nt db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" value="kraken_db" {{$kraken_db?'checked':''}}>
                        <label class="custom-control-label" for="kraken_db">kraken_db</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kraken_db_path overflow-auto {{$kraken_db ?'no-display':''}}">{{isset($PipelineParams->kraken_db_path)?$PipelineParams->kraken_db_path:'The kraken db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" value="eggnog_db" {{$eggnog?'checked':''}}>
                        <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 eggnog_db_path overflow-auto {{$eggnog ?'no-display':''}}">{{isset($PipelineParams->eggnog_db_path)?$PipelineParams->eggnog_db_path:'The eggnog db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" value="kofam_profile" {{$kofam_profile?'checked':''}}>
                        <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kofam_profile_path overflow-auto {{$kofam_profile?'no-display':''}}">{{isset($PipelineParams->kofam_profile_path)?$PipelineParams->kofam_profile_path:'The kofam profile path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" value="kofam_kolist" {{$kofam_kolist?'checked':''}}>
                        <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kofam_kolist_path overflow-auto {{$kofam_kolist?'no-display':''}}">{{isset($PipelineParams->kofam_kolist_path)?$PipelineParams->kofam_kolist_path:'The kofam kolist path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-2">
                      <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="eukcc_db" name="eukcc_db" value="eukcc_db" {{$eukcc_db?'checked':''}}>
                        <label class="custom-control-label" for="eukcc_db">eukcc_db</label>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 eukcc_db_path overflow-auto {{$eukcc_db?'no-display':''}}">{{isset($PipelineParams->eukcc_db_path)?$PipelineParams->eukcc_db_path:'The eukcc database path has not been set! Please contact the website administor.'}}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="mt-3 ml-3 w-50 btn btn-success d-inline-block" onclick="if(confirm('Notice: the parameters can\'t be modified during execution of this pipeline. Are you sure to run now?')==false) return false;" {{$can_exec?'':'disabled'}}>Execute</button>
        </form>
      </div>
    </div>
    <!-- right-column -->
    <div class=" col-md-4 right-column">
      <div class="other-info">
      </div>
    </div>
  </div>
</div>
@endsection
