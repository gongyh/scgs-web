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
          <img src="images/logo.png" alt="nf-core-scgs_logo" class="logo">
          <form action="" method="POST">
            @csrf
            <div class="input-group mt-2">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <span>
                    <span class="layui-font-red font-weight-bold">&nbsp Version</span>
                  </span>
                </span>
              </div>
              <div class="form-control overflow-auto">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ver_1" name="ver" class="custom-control-input" value="v1" {{isset($eParams->ver) ? ($eParams->ver == 'v1' ? 'checked' : '') : ''}} disabled>
                  <label class="custom-control-label font-weight-bold" for="ver_1">Version 1</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="ver_2" name="ver" class="custom-control-input" value="v2" {{isset($eParams->ver) ? ($eParams->ver == 'v2' ? 'checked' : '') : 'checked'}}>
                  <label class="custom-control-label font-weight-bold" for="ver_2">Version 2</label>
                </div>
              </div>
            </div>
            <div class="accordion mt-2" id="basic_select">
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#basic" aria-expanded="true" aria-controls="collapseTwo">
                      <span class="font-normal">Basic</span>
                    </button>
                  </h2>
                </div>
                <div id="basic" class="collapse" aria-labelledby="headingTwo" data-parent="#basic_select">
                  <div class="card-body">
                    <div class="bgcolor_grey">
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-barcode"></i>
                              <span class="text-success font-weight-bold">&nbsp--ass</span>
                            </span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ass_1" name="ass" class="custom-control-input" value="ass" {{$eParams->ass?'checked':''}}>
                            <label class="custom-control-label" for="ass_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ass_2" name="ass" class="custom-control-input" value="" {{$eParams->ass?'':'checked'}}>
                            <label class="custom-control-label" for="ass_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div>
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable genome assembly.</span>
                      </div>
                    </div>
                    @isset($default_reference)
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-file-code"></i>
                            </span>
                            <span class="text-success mr-2 font-weight-bold">&nbsp--reference genome</span>
                          </span>
                        </div>
                        <select class="custom-select" id="inputGroupSelect01" name="reference_genome">
                          <option class="font-italic" value="denovo">de novo</option>
                          @foreach($species_list as $species)
                          <option value={{$species->id}} {{$species->name == $default_reference?'selected':''}}>{{$species->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Choose the reference genome. Choose "de novo" if the reference genome is unknown.</span>
                      </div>
                    </div>
                    @endisset
                    <div class="euk-group p-2">
                      <div>
                        <div class="input-group mt-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-dna"></i>
                              </span>
                              <span class="text-success mr-2 font-weight-bold">&nbsp--euk</span>
                            </span>
                          </div>
                          <div class="form-control overflow-auto">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="euk_1" name="euk" class="custom-control-input euk" value="euk" {{$eParams->euk?'checked':''}}>
                              <label class="custom-control-label" for="euk_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="euk_2" name="euk" class="custom-control-input euk" value="" {{$eParams->euk?'':'checked'}}>
                              <label class="custom-control-label" for="euk_2">False</label>
                            </div>
                          </div>
                        </div>
                        <div class="bgcolor_grey">
                          <i class="fa-solid fa-circle-info text-muted"></i>
                          <span class="text-muted">Whether it is eukaryotic?</span>
                        </div>
                      </div>
                      <div class="euk_true ml-4">
                        <div>
                          <div class="input-group mt-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <span class="text-success">
                                  <i class="fa-solid fa-bacteria"></i>
                                </span>
                                <span class="text-success mr-2 font-weight-bold">&nbsp--augustus_species</span>
                              </span>
                            </div>
                            <div class="form-control overflow-auto">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="augustus_species_1" name="augustus_species" class="custom-control-input augustus_species" value="augustus_species" {{$eParams->augustus_species?'checked':''}}>
                                <label class="custom-control-label" for="augustus_species_1">True</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="augustus_species_2" name="augustus_species" class="custom-control-input augustus_species" value="" {{$eParams->augustus_species?'':'checked'}}>
                                <label class="custom-control-label" for="augustus_species_2">False</label>
                              </div>
                            </div>
                            <select class="custom-select w-40 augustus_species_name {{isset($eParams->split_euk) ? ($eParams->split_euk === true ? 'no-display' : '') : 'no-display'}}" name=" augustus_species_name" id="augustus_species_name">
                              @foreach($augustus_species_lists as $augustus_species_list)
                              <option value={{$augustus_species_list}}>{{$augustus_species_list}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="bgcolor_grey">
                            <i class="fa-solid fa-circle-info text-muted"></i>
                            <span class="text-muted">Choose the belonging "species" which will be used in Augustus to predict gene models.</span>
                          </div>
                        </div>
                        <div>
                          <div class="input-group mt-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <span class="text-success">
                                  <i class="fas fa-bacteria"></i>
                                </span>
                                <span class="text-success mr-2 font-weight-bold">&nbsp--fungus</span>
                              </span>
                            </div>
                            <div class="form-control overflow-auto">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="fungus_1" name="fungus" class="custom-control-input" value="fungus" {{$eParams->fungus?'checked':''}}>
                                <label class="custom-control-label" for="fungus_1">True</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="fungus_2" name="fungus" class="custom-control-input" value="" {{$eParams->fungus?'':'checked'}}>
                                <label class="custom-control-label" for="fungus_2">False</label>
                              </div>
                            </div>
                          </div>
                          <div class="bgcolor_grey">
                            <i class="fa-solid fa-circle-info text-muted"></i>
                            <span class="text-muted">Whether it is a fungal genome?</span>
                          </div>
                        </div>
                      </div>
                      <div class="euk_false ml-4">
                        <div class="input-group mt-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-bacterium"></i>
                              </span>
                              <span class="text-success mr-2 font-weight-bold">&nbsp--genus</span>
                            </span>
                          </div>
                          <div class="form-control overflow-auto">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="genus_1" name="genus" class="custom-control-input genus" value="genus" {{$eParams->genus?'checked':''}}>
                              <label class="custom-control-label" for="genus_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="genus_2" name="genus" class="custom-control-input genus" value="" {{$eParams->genus?'':'checked'}}>
                              <label class="custom-control-label" for="genus_2">False</label>
                            </div>
                          </div>
                          <select class="custom-select w-40 genus_name {{isset($eParams->genus_name) ? ($eParams->genus_name === true ? 'no-display' : '') : 'no-display'}}" name="genus_name" id="genus_name">
                            @foreach($genus_list as $genus)
                            <option value={{$genus}} {{strcmp($genus, $eParams->genus_name) == 0 ? 'selected' : ''}}>{{$genus}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="bgcolor_grey">
                          <i class="fa-solid fa-circle-info text-muted"></i>
                          <span class="text-muted">For prokaryotic genome, choose the belonging "Genus" which will be used in CheckM for the estimation of genome completeness and contamination.</span>
                        </div>
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

                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="far fa-file"></i>
                            </span>
                            <span class="text-success mr-2 font-weight-bold">&nbsp--nanopore</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nanopore_1" name="nanopore" class="custom-control-input nanopore" value="nanopore" {{$eParams->nanopore?'checked':''}}>
                            <label class="custom-control-label" for="nanopore_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="nanopore_2" name="nanopore" class="custom-control-input nanopore" value="" {{$eParams->nanopore?'':'checked'}}>
                            <label class="custom-control-label" for="nanopore_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether your sequencing data are from Nanopore sequencer?</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-braille"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--bulk</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="bulk_1" name="bulk" class="custom-control-input" value="bulk" {{$eParams->bulk?'checked':''}}>
                            <label class="custom-control-label" for="bulk_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="bulk_2" name="bulk" class="custom-control-input" value="" {{$eParams->bulk?'':'checked'}}>
                            <label class="custom-control-label" for="bulk_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whole genome sequencing of bulk sample.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion mt-2" id="advanced_select">
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#advanced" aria-expanded="true" aria-controls="collapseTwo">
                      <span class="font-normal">Advanced</span>
                    </button>
                  </h2>
                </div>
                <div id="advanced" class="collapse" aria-labelledby="headingTwo" data-parent="#advanced_select">
                  <div class="card-body">
                    <div>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-map-signs"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--cnv</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="cnv_1" name="cnv" class="custom-control-input" value="cnv" {{$eParams->cnv?'checked':''}}>
                            <label class="custom-control-label" for="cnv_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="cnv_2" name="cnv" class="custom-control-input" value="" {{$eParams->cnv?'':'checked'}}>
                            <label class="custom-control-label" for="cnv_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable detection of copy number variation.</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-map-signs"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--snv</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="snv_1" name="snv" class="custom-control-input" value="snv" {{$eParams->snv?'checked':''}}>
                            <label class="custom-control-label" for="snv_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="snv_2" name="snv" class="custom-control-input" value="" {{$eParams->snv?'':'checked'}}>
                            <label class="custom-control-label" for="snv_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable detection of single nucleotide variation.</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-caret-square-right"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--saturation</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saturation_1" name="saturation" class="custom-control-input" value="saturation" {{$eParams->saturation?'checked':''}}>
                            <label class="custom-control-label" for="saturation_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saturation_2" name="saturation" class="custom-control-input" value="" {{$eParams->saturation?'':'checked'}}>
                            <label class="custom-control-label" for="saturation_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable sequencing saturation analysis.</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-caret-square-right"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--acquired</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="acquired_1" name="acquired" class="custom-control-input" value="acquired" {{$eParams->acquired?'checked':''}}>
                            <label class="custom-control-label" for="acquired_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="acquired_2" name="acquired" class="custom-control-input" value="" {{$eParams->acquired?'':'checked'}}>
                            <label class="custom-control-label" for="acquired_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable antimicrobial resistance genes (ARG) analysis.</span>
                      </div>
                    </div>
                    <div class="split_group p-2">
                      <div>
                        <div class="input-group mt-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fab fa-buffer"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--split</span>
                            </span>
                          </div>
                          <div class="form-control overflow-auto">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="split_1" name="split" class="custom-control-input split" value="split" {{$eParams->split?'checked':''}}>
                              <label class="custom-control-label" for="split_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="split_2" name="split" class="custom-control-input split" value="" {{$eParams->split?'':'checked'}}>
                              <label class="custom-control-label" for="split_2">False</label>
                            </div>
                          </div>
                        </div>
                        <div class="bgcolor_grey">
                          <i class="fa-solid fa-circle-info text-muted"></i>
                          <span class="text-muted">Split the genome assemblies by taxa assignments of contigs. Gene models and functional annotations will also be splitted.</span>
                        </div>
                      </div>
                      <div class="split_true ml-4">
                        <div>
                          <div class="input-group mt-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <span class="text-success">
                                  <i class="fa-solid fa-bacteria"></i>
                                </span>
                                <span class="text-success mr-2 font-weight-bold">&nbsp--split_euk</span>
                              </span>
                            </div>
                            <div class="form-control overflow-auto">
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="split_euk_1" name="split_euk" class="custom-control-input split_euk" value="split_euk" {{isset($eParams->split_euk) ? ($eParams->split_euk === true ? 'checked' : '') : ''}}>
                                <label class="custom-control-label" for="split_euk_1">True</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="split_euk_2" name="split_euk" class="custom-control-input split_euk" value="" {{isset($eParams->split_euk) ? ($eParams->split_euk === false ? 'checked' : '') : 'checked'}}>
                                <label class="custom-control-label" for="split_euk_2">False</label>
                              </div>
                            </div>
                          </div>
                          <div class="bgcolor_grey">
                            <i class="fa-solid fa-circle-info text-muted"></i>
                            <span class="text-muted">Enable eukaryotic annotation for split contigs?</span>
                          </div>
                        </div>
                        <div>
                          <div class="input-group mt-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <span class="text-success">
                                  <i class="fa-solid fa-bacteria"></i>
                                </span>
                                <span class="text-success mr-2 font-weight-bold">&nbsp--split_bac_level</span>
                              </span>
                            </div>
                            <select class="custom-select w-40" name="split_bac_level">
                              @foreach($ranks as $rank)
                              <option value={{$rank}} {{isset($eParams->split_bac_level) ? (strcmp($rank, $eParams->split_bac_level) == 0 ? 'selected' : '') : (strcmp($rank, 'genus') == 0 ? 'selected' : '')}}>{{$rank}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="bgcolor_grey">
                            <i class="fa-solid fa-circle-info text-muted"></i>
                            <span class="text-muted">Rank for bacteria genome contigs to split</span>
                          </div>
                          <div class="input-group mt-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <span class="text-success">
                                  <i class="fa-solid fa-bacteria"></i>
                                </span>
                                <span class="text-success mr-2 font-weight-bold">&nbsp--split_euk_level</span>
                              </span>
                            </div>
                            <select class="custom-select w-40" name="split_euk_level">
                              @foreach($ranks as $rank)
                              <option value={{$rank}} {{isset($eParams->split_euk_level) ? (strcmp($rank, $eParams->split_bac_level) == 0 ? 'selected' : '') : ($rank == 'genus' ? 'selected' : '')}}>{{$rank}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="bgcolor_grey">
                            <i class="fa-solid fa-circle-info text-muted"></i>
                            <span class="text-muted">Rank for eukaryotic genome contigs to split?</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--saveTrimmed</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveTrimmed_1" name="saveTrimmed" class="custom-control-input" value="saveTrimmed" {{$eParams->saveTrimmed?'checked':''}}>
                            <label class="custom-control-label" for="saveTrimmed_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveTrimmed_2" name="saveTrimmed" class="custom-control-input" value="" {{$eParams->saveTrimmed?'':'checked'}}>
                            <label class="custom-control-label" for="saveTrimmed_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Save the trimmed Fastq files into the Results directory.</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--saveAlignedIntermediates</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveAlignedIntermediates_1" name="saveAlignedIntermediates" class="custom-control-input" value="saveAlignedIntermediates" {{$eParams->saveAlignedIntermediates?'checked':''}}>
                            <label class="custom-control-label" for="saveAlignedIntermediates_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="saveAlignedIntermediates_2" name="saveAlignedIntermediates" class="custom-control-input" value="" {{$eParams->saveAlignedIntermediates?'':'checked'}}>
                            <label class="custom-control-label" for="saveAlignedIntermediates_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Save the intermediate BAM files from the Alignment step. (disable by default)</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-align-justify"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--no_normalize</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="no_normalize_1" name="no_normalize" class="custom-control-input" value="no_normalize" {{$eParams->no_normalize?'checked':''}}>
                            <label class="custom-control-label" for="no_normalize_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="no_normalize_2" name="no_normalize" class="custom-control-input" value="" {{$eParams->no_normalize?'':'checked'}}>
                            <label class="custom-control-label" for="no_normalize_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Skip read normalization?</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-align-justify"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--acdc</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="acdc_1" name="acdc" class="custom-control-input" value="acdc" {{isset($eParams->acdc) ? ($eParams->acdc === true ? 'checked' : '') : ''}}>
                            <label class="custom-control-label" for="acdc_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="acdc_2" name="acdc" class="custom-control-input" value="" {{isset($eParams->acdc) ? ($eParams->acdc === false ? 'checked' : '') : 'checked'}}>
                            <label class="custom-control-label" for="acdc_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable acdc?</span>
                      </div>
                    </div>
                    <div>
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fas fa-align-justify"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--graphbin</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="graphbin_1" name="graphbin" class="custom-control-input" value="graphbin" {{isset($eParams->graphbin) ? ($eParams->graphbin === true ? 'checked' : '') : ''}}>
                            <label class="custom-control-label" for="graphbin_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="graphbin_2" name="graphbin" class="custom-control-input" value="" {{isset($eParams->graphbin) ? ($eParams->graphbin === false ? 'checked' : '') : 'checked'}}>
                            <label class="custom-control-label" for="graphbin_2">False</label>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable graphbin binning?</span>
                      </div>
                    </div>
                    <div class="w-100">
                      <div class="input-group mt-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <span class="text-success">
                              <i class="fa-solid fa-rotate-left"></i>
                            </span>
                            <span class="text-success font-weight-bold mr-2">&nbsp--resume</span>
                          </span>
                        </div>
                        <div class="form-control overflow-auto">
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="resume_1" name="resume" class="custom-control-input" value="resume" {{$eParams->resume?'checked':''}}>
                            <label class="custom-control-label" for="eParams->resume_1">True</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="resume_2" name="resume" class="custom-control-input" value="" {{$eParams->resume?'':'checked'}}>
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
                          When using the -resume flag, successfully completed tasks are skipped and the previously cached results are used in downstream tasks.
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
                      <span class="font-normal">Database</span>
                    </button>
                  </h2>
                </div>
                <div id="database" class="collapse" aria-labelledby="headingOne" data-parent="#database_select">
                  <div class="card-body">
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--kraken2</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="kraken_1" name="kraken" class="custom-control-input" value="kraken" {{isset($eParams->kraken) ? ($eParams->kraken === true ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="kraken_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="kraken_2" name="kraken" class="custom-control-input" value="" {{isset($eParams->kraken) ? ($eParams->kraken === false ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="kraken_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to use Kraken for taxonomic classification of reads.</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--blob</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="blob_1" name="blob" class="custom-control-input" value="blob" {{isset($eParams->blob) ? ($eParams->blob === true ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="blob_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="blob_2" name="blob" class="custom-control-input" value="" {{isset($eParams->blob) ? ($eParams->blob === false ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="blob_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to use Kraken for taxonomic classification of reads.</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fa-solid fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--point</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="point_1" name="point" class="custom-control-input" value="point" {{isset($eParams->point) ? ($eParams->point === true ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="point_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="point_2" name="point" class="custom-control-input" value="point" {{isset($eParams->point) ? ($eParams->point === false ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="point_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to identify antimicrobial resistance genes from assembled genomes?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--nt</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="nt_1" name="nt" class="custom-control-input" value="nt" {{isset($eParams->nt) ? ($eParams->nt === true ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="nt_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="nt_2" name="nt" class="custom-control-input" value="" {{isset($eParams->nt) ? ($eParams->nt === false ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="nt_2">False</label>
                            </div>
                          </div>
                        </div>
                        <div class="d-none mt-2 w-50 border border-info rounded shadow p-2 nt_db overflow-auto">{{isset($PipelineParams->nt_db_path)?$PipelineParams->nt_db_path:'The nt database path has not been set! Please contact the website administor.'}}</div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to use NCBI/Nt database for contig annotation?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--eggnog</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="eggnog_1" name="eggnog" class="custom-control-input" value="eggnog" {{isset($eParams->eggnog) ? ($eParams->eggnog === true ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="eggnog_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="eggnog_2" name="eggnog" class="custom-control-input" value="" {{isset($eParams->eggnog) ? ($eParams->eggnog === false ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="eggnog_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable EggNOG annotation?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--kofam</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="kofam_profile_1" name="kofam_profile" class="custom-control-input" value="kofam_profile" {{$eParams->kofam_profile?'checked':''}}>
                              <label class="custom-control-label" for="kofam_profile_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="kofam_profile_2" name="kofam_profile" class="custom-control-input" value="" {{$eParams->kofam_profile?'':'checked'}}>
                              <label class="custom-control-label" for="kofam_profile_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable KO annotation using KOfam?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--checkm2</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="checkm2_1" name="checkm2" class="custom-control-input" value="checkm2" {{isset($eParams->checkm2) ? ($eParams->checkm2 === true ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="checkm2_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="checkm2_2" name="checkm2" class="custom-control-input" value="" {{isset($eParams->checkm2) ? ($eParams->checkm2 === false ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="checkm2_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to enable checkm2 to assessing the quality of genomes?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--eukcc</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="eukcc_db_1" name="eukcc_db" class="custom-control-input" value="eukcc_db" {{$eParams->eukcc_db?'checked':''}}>
                              <label class="custom-control-label" for="eukcc_db_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="eukcc_db_2" name="eukcc_db" class="custom-control-input" value="" {{$eParams->eukcc_db?'':'checked'}}>
                              <label class="custom-control-label" for="eukcc_db_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Whether to use EukCC for completeness estimation of eukaryotic genome assemblies?</span>
                      </div>
                    </div>
                    <div>
                      <div class="mt-2">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">
                              <span class="text-success">
                                <i class="fas fa-database"></i>
                              </span>
                              <span class="text-success font-weight-bold mr-2">&nbsp--gtdbtk</span>
                            </span>
                          </div>
                          <div class="form-control">
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="gtdb_1" name="gtdb" class="custom-control-input" value="gtdb" {{isset($eParams->gtdb) ? ($eParams->gtdb === true ? 'checked' : '') : ''}}>
                              <label class="custom-control-label" for="gtdb_1">True</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="gtdb_2" name="gtdb" class="custom-control-input" value="" {{isset($eParams->gtdb) ? ($eParams->gtdb === false ? 'checked' : '') : 'checked'}}>
                              <label class="custom-control-label" for="gtdb_2">False</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="bgcolor_grey">
                        <i class="fa-solid fa-circle-info text-muted"></i>
                        <span class="text-muted">Enable EggNOG annotation?</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="mt-2 ml-3 w-50 btn btn-success d-inline-block" onclick="if(confirm('Notice: the parameters can\'t be modified during execution of this pipeline. Are you sure to run now?')==false) return false;" {{$can_exec?'':'disabled'}}>Execute</button>
          </form>
        </div>
        <div class="col-md-6">
          <div class="card mb-3">
            <div class="card-body">
              <span style="font-size:16px">
                The pipeline is used for single cell genome sequencing data analysis and built using Nextflow, a workflow tool to run tasks across multiple compute infrastructures in a very portable manner. It comes with docker / singularity containers making installation trivial and results highly reproducible.
              </span>
            </div>
          </div>
          <img src={{"images/pipeline.jpg"}} alt="scgs_pipeline" class="scgs_pipeline">
        </div>
      </div>
      <hr>
    </div>
    <!-- right-column -->
  </div>
</div>
@endsection
