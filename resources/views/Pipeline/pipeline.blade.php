@extends('layouts.app')

@section('content')
<div class="fluit-container">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
          <li class="breadcrumb-item"><a href="/samples?projectID={{$project_id}}">Samples</a></li>
          <li class="breadcrumb-item active" aria-current="page">Execute</li>
          </li>
        </ol>
      </nav>
      <!-- <div class="middle-info">

      </div> -->
      <div class="d-flex">
        <div class="col-md-6 bg-white rounded shadow-sm p-3 exec-params overflow-auto">
          <form action="" method="POST">
            @csrf
            <div class="text-primary rem15">Configuration</div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --ass </span>
              </div>
              <div class="form-control overflow-auto">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ass_1" name="ass" class="custom-control-input" value="ass" {{$ass?'checked':''}}>
                  <label class="custom-control-label" for="ass_1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ass_2" name="ass" class="custom-control-input" value="" {{$ass?'':'checked'}}>
                  <label class="custom-control-label" for="ass_2">False</label>
                </div>
              </div>
            </div>
            @isset($default_reference)
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01"> --reference_genome </label>
              </div>
              <select class="custom-select" id="inputGroupSelect01" name="reference_genome">
                <option class="font-italic" value="denovo">de novo</option>
                @foreach($species_list as $species)
                <option value={{$species->id}} {{$species->name == $default_reference?'selected':''}}>{{$species->name}}</option>
                @endforeach
              </select>
            </div>
            @endisset
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --euk </span>
              </div>
              <div class="form-control overflow-auto">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="euk_1" name="euk" class="custom-control-input euk" value="euk" {{$euk?'checked':''}}>
                  <label class="custom-control-label" for="euk_1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="euk_2" name="euk" class="custom-control-input euk" value="" {{$euk?'':'checked'}}>
                  <label class="custom-control-label" for="euk_2">False</label>
                </div>
              </div>
            </div>
            <div class="euk_true ml-4">
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"> --augustus_species </span>
                </div>
                <div class="form-control overflow-auto">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="augustus_species_1" name="augustus_species" class="custom-control-input augustus_species" value="augustus_species" {{$augustus_species?'checked':''}}>
                    <label class="custom-control-label" for="augustus_species_1">True</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="augustus_species_2" name="augustus_species" class="custom-control-input augustus_species" value="" {{$augustus_species?'':'checked'}}>
                    <label class="custom-control-label" for="augustus_species_2">False</label>
                  </div>
                </div>
                <select class="custom-select w-40 augustus_species_name {{$augustus_species_name == null?'no-display':''}}" name=" augustus_species_name" id="augustus_species_name">
                  @foreach($augustus_species_lists as $augustus_species_list)
                  <option value={{$augustus_species_list}}>{{$augustus_species_list}}</option>
                  @endforeach
                </select>
              </div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"> --fungus </span>
                </div>
                <div class="form-control overflow-auto">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="fungus_1" name="fungus" class="custom-control-input" value="fungus" {{$fungus?'checked':''}}>
                    <label class="custom-control-label" for="fungus_1">True</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="fungus_2" name="fungus" class="custom-control-input" value="" {{$fungus?'':'checked'}}>
                    <label class="custom-control-label" for="fungus_2">False</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="euk_false ml-4">
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"> --genus </span>
                </div>
                <div class="form-control overflow-auto">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="genus_1" name="genus" class="custom-control-input genus" value="genus" {{$genus?'checked':''}}>
                    <label class="custom-control-label" for="genus_1">True</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="genus_2" name="genus" class="custom-control-input genus" value="" {{$genus?'':'checked'}}>
                    <label class="custom-control-label" for="genus_2">False</label>
                  </div>
                </div>
                <select class="custom-select w-40 genus_name {{$genus_name == null?'no-display':''}}" name="genus_name" id="genus_name">
                  @foreach($genus_list as $genus)
                  <option value={{$genus}} {{strcmp($genus, $genus_name) == 0 ? 'selected' : ''}}>{{$genus}}</option>
                  @endforeach
                </select>
              </div>

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

            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --nanopore </span>
              </div>
              <div class="form-control overflow-auto">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="nanopore_1" name="nanopore" class="custom-control-input nanopore" value="nanopore" {{$nanopore?'checked':''}}>
                  <label class="custom-control-label" for="nanopore_1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="nanopore_2" name="nanopore" class="custom-control-input nanopore" value="" {{$nanopore?'':'checked'}}>
                  <label class="custom-control-label" for="nanopore_2">False</label>
                </div>
              </div>
            </div>
            <div class="accordion mt-2" id="advanced_select">
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#advanced" aria-expanded="true" aria-controls="collapseTwo">
                      Advanced
                    </button>
                  </h2>
                </div>
                <div id="advanced" class="collapse" aria-labelledby="headingTwo" data-parent="#advanced_select">
                  <div class="card-body">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --cnv </span>
                      </div>
                      <div class="form-control overflow-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="cnv_1" name="cnv" class="custom-control-input" value="cnv" {{$cnv?'checked':''}}>
                          <label class="custom-control-label" for="cnv_1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="cnv_2" name="cnv" class="custom-control-input" value="" {{$cnv?'':'checked'}}>
                          <label class="custom-control-label" for="cnv_2">False</label>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mt-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --snv </span>
                      </div>
                      <div class="form-control overflow-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="snv_1" name="snv" class="custom-control-input" value="snv" {{$snv?'checked':''}}>
                          <label class="custom-control-label" for="snv_1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="snv_2" name="snv" class="custom-control-input" value="" {{$snv?'':'checked'}}>
                          <label class="custom-control-label" for="snv_2">False</label>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mt-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --bulk </span>
                      </div>
                      <div class="form-control overflow-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="bulk_1" name="bulk" class="custom-control-input" value="bulk" {{$bulk?'checked':''}}>
                          <label class="custom-control-label" for="bulk_1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="bulk_2" name="bulk" class="custom-control-input" value="" {{$bulk?'':'checked'}}>
                          <label class="custom-control-label" for="bulk_2">False</label>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mt-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --saturation </span>
                      </div>
                      <div class="form-control overflow-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="saturation_1" name="saturation" class="custom-control-input" value="saturation" {{$saturation?'checked':''}}>
                          <label class="custom-control-label" for="saturation_1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="saturation_2" name="saturation" class="custom-control-input" value="" {{$saturation?'':'checked'}}>
                          <label class="custom-control-label" for="saturation_2">False</label>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mt-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --acquired </span>
                      </div>
                      <div class="form-control overflow-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="acquired_1" name="acquired" class="custom-control-input" value="acquired" {{$acquired?'checked':''}}>
                          <label class="custom-control-label" for="acquired_1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="acquired_2" name="acquired" class="custom-control-input" value="" {{$acquired?'':'checked'}}>
                          <label class="custom-control-label" for="acquired_2">False</label>
                        </div>
                      </div>
                      <div class="input-group mt-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --saveTrimmed </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveTrimmed_1" name="saveTrimmed" class="custom-control-input" value="saveTrimmed" {{$saveTrimmed?'checked':''}}>
                            <label class="custom-control-label" for="saveTrimmed_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveTrimmed_2" name="saveTrimmed" class="custom-control-input" value="" {{$saveTrimmed?'':'checked'}}>
                            <label class="custom-control-label" for="saveTrimmed_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="input-group mt-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --saveAlignedIntermediates </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveAlignedIntermediates_1" name="saveAlignedIntermediates" class="custom-control-input" value="saveAlignedIntermediates" {{$saveAlignedIntermediates?'checked':''}}>
                            <label class="custom-control-label" for="saveAlignedIntermediates_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveAlignedIntermediates_2" name="saveAlignedIntermediates" class="custom-control-input" value="" {{$saveAlignedIntermediates?'':'checked'}}>
                            <label class="custom-control-label" for="saveAlignedIntermediates_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="input-group mt-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --no-normalize </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="no_normalize_1" name="no_normalize" class="custom-control-input" value="no_normalize" {{$no_normalize?'checked':''}}>
                            <label class="custom-control-label" for="no_normalize_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="no_normalize_2" name="no_normalize" class="custom-control-input" value="" {{$no_normalize?'':'checked'}}>
                            <label class="custom-control-label" for="no_normalize_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="w-100">
                        <div class="input-group mt-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text"> --resume </span>
                          </div>
                          <div class="form-control overflow-auto">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="resume_1" name="resume" class="custom-control-input" value="resume" {{$resume?'checked':''}}>
                              <label class="custom-control-label" for="resume_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="resume_2" name="resume" class="custom-control-input" value="" {{$resume?'':'checked'}}>
                              <label class="custom-control-label" for="resume_2">False</label>
                            </div>
                          </div>
                          <div class="input-group-append">
                            <span id="explain_resume" class="input-group-text" data-toggle="collapse" data-target="#resume_explain" aria-expanded="false" aria-controls="resume_explain">
                              <i class="fa fa-lg fa-question-circle"></i>
                            </span>
                          </div>
                        </div>
                        <div class="collapse" id="resume_explain">
                          <div class="card card-body text-danger">
                            Pipeline will restart where teminated last time!
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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
                <div id="database" class="collapse" aria-labelledby="headingOne" data-parent="#database_select">
                  <div class="card-body">
                    <div class="mt-2">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --resfinder_db </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="resfinder_db_1" name="resfinder_db" class="custom-control-input" value="resfinder_db" {{$resfinder_db?'checked':''}}>
                            <label class="custom-control-label" for="resfinder_db_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="resfinder_db_2" name="resfinder_db" class="custom-control-input" value="" {{$resfinder_db?'':'checked'}}>
                            <label class="custom-control-label" for="resfinder_db_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 resfinder_db_path overflow-auto {{$resfinder_db?'no-display':''}}">
                        {{isset($PipelineParams->resfinder_db_path)?$PipelineParams->resfinder_db_path:'The resfinder db path has not been set! Please contact the website administor.'}}
                      </div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --nt_db </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nt_db_1" name="nt_db" class="custom-control-input" value="nt_db" {{$nt_db?'checked':''}}>
                            <label class="custom-control-label" for="nt_db_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nt_db_2" name="nt_db" class="custom-control-input" value="" {{$nt_db?'':'checked'}}>
                            <label class="custom-control-label" for="nt_db_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 nt_db_path overflow-auto {{$nt_db? 'no-display':''}}">{{isset($PipelineParams->nt_db_path)?$PipelineParams->nt_db_path:'The nt db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --kraken_db </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kraken_db_1" name="kraken_db" class="custom-control-input" value="kraken_db" {{$kraken_db?'checked':''}}>
                            <label class="custom-control-label" for="kraken_db_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kraken_db_2" name="kraken_db" class="custom-control-input" value="" {{$kraken_db?'':'checked'}}>
                            <label class="custom-control-label" for="kraken_db_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kraken_db_path overflow-auto {{$kraken_db ?'no-display':''}}">{{isset($PipelineParams->kraken_db_path)?$PipelineParams->kraken_db_path:'The kraken db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --eggnog </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="eggnog_1" name="eggnog_db" class="custom-control-input" value="eggnog_db" {{$eggnog?'checked':''}}>
                            <label class="custom-control-label" for="eggnog_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="eggnog_2" name="eggnog_db" class="custom-control-input" value="" {{$eggnog?'':'checked'}}>
                            <label class="custom-control-label" for="eggnog_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 eggnog_db_path overflow-auto {{$eggnog ?'no-display':''}}">{{isset($PipelineParams->eggnog_db_path)?$PipelineParams->eggnog_db_path:'The eggnog db path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --kofam_profile </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kofam_profile_1" name="kofam_profile" class="custom-control-input" value="kofam_profile" {{$kofam_profile?'checked':''}}>
                            <label class="custom-control-label" for="kofam_profile_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kofam_profile_2" name="kofam_profile" class="custom-control-input" value="" {{$kofam_profile?'':'checked'}}>
                            <label class="custom-control-label" for="kofam_profile_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kofam_profile_path overflow-auto {{$kofam_profile?'no-display':''}}">{{isset($PipelineParams->kofam_profile_path)?$PipelineParams->kofam_profile_path:'The kofam profile path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --kofam_kolist </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kofam_kolist_1" name="kofam_kolist" class="custom-control-input" value="kofam_kolist" {{$kofam_kolist?'checked':''}}>
                            <label class="custom-control-label" for="kofam_kolist_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="kofam_kolist_2" name="kofam_kolist" class="custom-control-input" value="" {{$kofam_kolist?'':'checked'}}>
                            <label class="custom-control-label" for="kofam_kolist_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 kofam_kolist_path overflow-auto {{$kofam_kolist?'no-display':''}}">{{isset($PipelineParams->kofam_kolist_path)?$PipelineParams->kofam_kolist_path:'The kofam kolist path has not been set! Please contact the website administor.'}}</div>
                    </div>
                    <div class="mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> --eukcc_db </span>
                        </div>
                        <div class="form-control">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="eukcc_db_1" name="eukcc_db" class="custom-control-input" value="eukcc_db" {{$eukcc_db?'checked':''}}>
                            <label class="custom-control-label" for="eukcc_db_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="eukcc_db_2" name="eukcc_db" class="custom-control-input" value="" {{$eukcc_db?'':'checked'}}>
                            <label class="custom-control-label" for="eukcc_db_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 eukcc_db_path overflow-auto {{$eukcc_db?'no-display':''}}">{{isset($PipelineParams->eukcc_db_path)?$PipelineParams->eukcc_db_path:'The eukcc database path has not been set! Please contact the website administor.'}}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="mt-3 ml-3 w-50 btn btn-success d-inline-block" onclick="if(confirm('Notice: the parameters can\'t be modified during execution of this pipeline. Are you sure to run now?')==false) return false;" {{$can_exec?'':'disabled'}}>Execute</button>
          </form>
        </div>
        <div class="col-md-6">
          <img src={{"images/pipeline.jpg"}} alt="scgs_pipeline" class="scgs_pipeline">
        </div>
      </div>
    </div>
    <!-- right-column -->
  </div>
</div>
@endsection
