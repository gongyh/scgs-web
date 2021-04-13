@extends('layouts.app')

@section('content')
<div class="container">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class=""></div>
    <div class="col-md-8">
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
      <div class="bg-white rounded shadow-sm p-3 exec-params overflow-auto">
        <form action="" method="POST">
          @csrf
          <div class="text-primary rem15">Configuration</div>
          <div class="border-bottom pb-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"> --ass </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ass1" name="ass" class="custom-control-input" value="ass" {{$ass?'checked':''}}>
                  <label class="custom-control-label" for="ass1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ass2" name="ass" class="custom-control-input" value="" {{$ass==false?'checked':''}}>
                  <label class="custom-control-label" for="ass2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --cnv </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="cnv1" name="cnv" class="custom-control-input" value="cnv" {{$cnv?'checked':''}}>
                  <label class="custom-control-label" for="cnv1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="cnv2" name="cnv" class="custom-control-input" value="" {{$cnv==false?'checked':''}}>
                  <label class="custom-control-label" for="cnv2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --snv </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="snv1" name="snv" class="custom-control-input" value="snv" {{$snv?'checked':''}}>
                  <label class="custom-control-label" for="snv1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="snv2" name="snv" class="custom-control-input" value="" {{$snv==false?'checked':''}}>
                  <label class="custom-control-label" for="snv2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --bulk </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="bulk1" name="bulk" class="custom-control-input" value="bulk" {{$bulk?'checked':''}}>
                  <label class="custom-control-label" for="bulk1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="bulk2" name="bulk" class="custom-control-input" value="" {{$bulk==false?'checked':''}}>
                  <label class="custom-control-label" for="bulk2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --saturation </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saturation1" name="saturation" class="custom-control-input" value="saturation" {{$saturation?'checked':''}}>
                  <label class="custom-control-label" for="saturation1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saturation2" name="saturation" class="custom-control-input" value="" {{$saturation==false?'checked':''}}>
                  <label class="custom-control-label" for="saturation2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --acquired </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="acquired1" name="acquired" class="custom-control-input" value="acquired" {{$acquired?'checked':''}}>
                  <label class="custom-control-label" for="acquired1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="acquired2" name="acquired" class="custom-control-input" value="" {{$acquired==false?'checked':''}}>
                  <label class="custom-control-label" for="acquired2">False</label>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --saveTrimmed </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saveTrimmed1" name="saveTrimmed" class="custom-control-input" value="saveTrimmed" {{$saveTrimmed?'checked':''}}>
                  <label class="custom-control-label" for="saveTrimmed1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saveTrimmed2" name="saveTrimmed" class="custom-control-input" value="" {{$saveTrimmed==false?'checked':''}}>
                  <label class="custom-control-label" for="saveTrimmed2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --saveAlignedIntermediates </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saveAlignedIntermediates1" name="saveAlignedIntermediates" class="custom-control-input" value="saveAlignedIntermediates" {{$saveAlignedIntermediates?'checked':''}}>
                  <label class="custom-control-label" for="saveAlignedIntermediates1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="saveAlignedIntermediates2" name="saveAlignedIntermediates" class="custom-control-input" value="" {{$saveAlignedIntermediates==false?'checked':''}}>
                  <label class="custom-control-label" for="saveAlignedIntermediates2">False</label>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --euk </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="euk1" name="euk" class="custom-control-input" value="euk" {{$euk?'checked':''}}>
                  <label class="custom-control-label" for="euk1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="euk2" name="euk" class="custom-control-input" value="" {{$euk==false?'checked':''}}>
                  <label class="custom-control-label" for="euk2">False</label>
                </div>
              </div>
            </div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --fungus </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="fungus1" name="fungus" class="custom-control-input" value="fungus" {{$fungus?'checked':''}}>
                  <label class="custom-control-label" for="fungus1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="fungus2" name="fungus" class="custom-control-input" value="" {{$fungus==false?'checked':''}}>
                  <label class="custom-control-label" for="fungus2">False</label>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --resume </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="resume1" name="resume" class="custom-control-input" value="resume" {{$resume?'checked':''}}>
                  <label class="custom-control-label" for="resume1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="resume2" name="resume" class="custom-control-input" value="" {{$resume==false?'checked':''}}>
                  <label class="custom-control-label" for="resume2">False</label>
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
          <div>
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --genus </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="genus1" name="genus" class="custom-control-input genus" value="genus" {{$genus?'checked':''}}>
                  <label class="custom-control-label" for="genus1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="genus2" name="genus" class="custom-control-input genus" value="" {{$genus==false?'checked':''}}>
                  <label class="custom-control-label" for="genus2">False</label>
                </div>
              </div>
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
          <div class="mt-2 border-bottom pb-3">
            <div class="input-group mt-3">
              <div class="input-group-prepend">
                <span class="input-group-text"> --augustus_species </span>
              </div>
              <div class="form-control">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="augustus_species1" name="augustus_species" class="custom-control-input augustus_species" value="augustus_species" {{$augustus_species?'checked':''}}>
                  <label class="custom-control-label" for="augustus_species1">True</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="augustus_species2" name="augustus_species" class="custom-control-input augustus_species" value="" {{$augustus_species==false?'checked':''}}>
                  <label class="custom-control-label" for="augustus_species2">False</label>
                </div>
              </div>
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
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> --resfinder_db </span>
                      </div>
                      <div class="form-control">
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="resfinder_db1" name="resfinder_db" class="custom-control-input" value="resfinder_db" {{$resfinder_db?'checked':''}}>
                          <label class="custom-control-label" for="resfinder_db1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="resfinder_db2" name="resfinder_db" class="custom-control-input" value="" {{$resfinder_db==false?'checked':''}}>
                          <label class="custom-control-label" for="resfinder_db2">False</label>
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
                          <input type="radio" id="nt_db1" name="nt_db" class="custom-control-input" value="nt_db" {{$nt_db?'checked':''}}>
                          <label class="custom-control-label" for="nt_db1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="nt_db2" name="nt_db" class="custom-control-input" value="" {{$nt_db==false?'checked':''}}>
                          <label class="custom-control-label" for="nt_db2">False</label>
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
                          <input type="radio" id="kraken_db1" name="kraken_db" class="custom-control-input" value="kraken_db" {{$kraken_db?'checked':''}}>
                          <label class="custom-control-label" for="kraken_db1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="kraken_db2" name="kraken_db" class="custom-control-input" value="" {{$kraken_db==false?'checked':''}}>
                          <label class="custom-control-label" for="kraken_db2">False</label>
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
                          <input type="radio" id="eggnog1" name="eggnog_db" class="custom-control-input" value="eggnog_db" {{$eggnog?'checked':''}}>
                          <label class="custom-control-label" for="eggnog1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="eggnog2" name="eggnog_db" class="custom-control-input" value="" {{$eggnog==false?'checked':''}}>
                          <label class="custom-control-label" for="eggnog2">False</label>
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
                          <input type="radio" id="kofam_profile1" name="kofam_profile" class="custom-control-input" value="kofam_profile" {{$kofam_profile?'checked':''}}>
                          <label class="custom-control-label" for="kofam_profile1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="kofam_profile2" name="kofam_profile" class="custom-control-input" value="" {{$kofam_profile==false?'checked':''}}>
                          <label class="custom-control-label" for="kofam_profile2">False</label>
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
                          <input type="radio" id="kofam_kolist1" name="kofam_kolist" class="custom-control-input" value="kofam_kolist" {{$kofam_kolist?'checked':''}}>
                          <label class="custom-control-label" for="kofam_kolist1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="kofam_kolist2" name="kofam_kolist" class="custom-control-input" value="" {{$kofam_kolist==false?'checked':''}}>
                          <label class="custom-control-label" for="kofam_kolist2">False</label>
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
                          <input type="radio" id="eukcc_db1" name="eukcc_db" class="custom-control-input" value="eukcc_db" {{$eukcc_db?'checked':''}}>
                          <label class="custom-control-label" for="eukcc_db1">True</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="eukcc_db2" name="eukcc_db" class="custom-control-input" value="" {{$eukcc_db==false?'checked':''}}>
                          <label class="custom-control-label" for="eukcc_db2">False</label>
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
    </div>
    <!-- right-column -->
    <div class=" col-md-4 right-column">
      <div class="other-info">
      </div>
    </div>
  </div>
</div>
@endsection
