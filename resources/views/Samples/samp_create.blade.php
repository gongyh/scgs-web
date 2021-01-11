@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="d-flex flex-around">
          <div class="w-50 pr-5">
            <div class="form-group" title="BioSample name">
              <label for="new_sample_label">Sample Label</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="new_sample_label" id="new_sample_label" value={{old('new_sample_label')?old('new_sample_label'):''}}>
            </div>

            <div class="form-group">
              <label for="new_library_id">Accession</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="new_library_id" id="new_library_id" value={{old('new_library_id')?old('new_library_id'):''}}>
            </div>

            <div class="form-group">
              <label for="library_strategy">Library Strategy</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_strategy" id="library_strategy">
                @if(old('library_strategy'))
                <option value={{old('library_strategy')?old('library_strategy'):''}} selected>{{old('library_strategy')?old('library_strategy'):''}}</option>
                @else
                <option value="WGS" selected>WGS</option>
                @endif
                @foreach($library_strategies as $library_strategy)
                <option value={{$library_strategy}}>{{$library_strategy}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="library_source">Library Source</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_source" id="library_source">
                @if(old('library_source'))
                <option value={{old('library_source')?old('library_source'):''}} selected>{{old('library_source')?old('library_source'):''}}</option>
                @else
                <option value="GENOMIC" selected>GENOMIC</option>
                @endif
                @foreach($library_sources as $library_source)
                <option value={{$library_source}}>{{$library_source}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="library_selection">Library Selection</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_selection" id="library_selection">
                @if(old('library_selection'))
                <option value={{old('library_selection')?old('library_selection'):''}} selected>{{old('library_selection')?old('library_selection'):''}}</option>
                @else
                <option value="MDA" selected>MDA</option>
                @endif
                @foreach($library_selections as $library_selection)
                <option value={{$library_selection}}>{{$library_selection}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="platform">Platform</label><span class="text-danger"> *</span>
              <select class="custom-select" name="platform" id="platform">
                @if(old('platform'))
                <option value={{old('platform')?old('platform'):''}} selected>{{old('platform')?old('platform'):''}}</option>
                @else
                <option value="_LS454">_LS454</option>
                <option value="ABI_SOLID">ABI_SOLID</option>
                <option value="BGISEQ">BGISEQ</option>
                <option value="CAPILLARY">CAPILLARY</option>
                <option value="COMPLETE_GENOMICS">COMPLETE_GENOMICS</option>
                <option value="HELICOS">HELICOS</option>
                <option value="ILLUMINA" selected>ILLUMINA</option>
                <option value="ION_TORRENT">ION_TORRENT</option>
                <option value="OXFORD_NANOPORE">OXFORD_NANOPORE</option>
                <option value="PACBIO_SMRT">PACBIO_SMRT</option>
                @endif
              </select>
            </div>

            <div class="form-group">
              <label for="instrument_model">Instrument Model</label><span class="text-danger"> *</span>
              <select class="custom-select" name="instrument_model" id="instrument_model">
                @if(old('instrument_model'))
                <option value={{old('instrument_model')?old('instrument_model'):''}} selected>{{old('instrument_model')?old('instrument_model'):''}}</option>
                @else
                <option value="454 GS" class="_LS454">454 GS</option>
                <option value="454 GS 20" class="_LS454">454 GS 20</option>
                <option value="454 GS FLX" class="_LS454">454 GS FLX</option>
                <option value="454 GS FLX+" class="_LS454">454 GS FLX+</option>
                <option value="454 GS FLX Titanium" class="_LS454">454 GS FLX Titanium</option>
                <option value="454 GS Junior" class="_LS454">454 GS Junior</option>
                <option value="HiSeq X Five" class="Illumina">HiSeq X Five</option>
                <option value="Hiseq X Ten" class="Illumina">Hiseq X Ten</option>
                <option value="Illumina Genome Analyzer" class="Illumina">Illumina Genome Analyzer</option>
                <option value="Illumina Genome Analyzer II" class="Illumina">Illumina Genome Analyzer II</option>
                <option value="Illumina Genome Analyzer IIx" class="Illumina">Illumina Genome Analyzer IIx</option>
                <option value="Illumina HiScanSQ" class="Illumina">Illumina HiScanSQ</option>
                <option value="Illumina HiSeq 1000" class="Illumina">Illumina HiSeq 1000</option>
                <option value="Illumina HiSeq 1500" class="Illumina">Illumina HiSeq 1500</option>
                <option value="Illumina HiSeq 2000" class="Illumina" selected>Illumina HiSeq 2000</option>
                <option value="Illumina HiSeq 2500" class="Illumina">Illumina HiSeq 2500</option>
                <option value="Illumina HiSeq 3000" class="Illumina">Illumina HiSeq 3000</option>
                <option value="Illumina HiSeq 4000" class="Illumina">Illumina HiSeq 4000</option>
                <option value="Illumina iSeq 100" class="Illumina">Illumina iSeq 100</option>
                <option value="Illumina NovaSeq 6000" class="Illumina">Illumina NovaSeq 6000</option>
                <option value="Illumina MiniSeq" class="Illumina">Illumina MiniSeq</option>
                <option value="Illumina MiSeq" class="Illumina">Illumina MiSeq</option>
                <option value="NextSeq 500" class="Illumina">NextSeq 500</option>
                <option value="NextSeq 550" class="Illumina">NextSeq 550</option>
                <option value="Helicos HeliScope" class="Helicos">Helicos HeliScope</option>
                <option value="AB 5500 Genetic Analyzer" class="ABI_SOLID">AB 5500 Genetic Analyzer</option>
                <option value="AB 5500xl Genetic Analyzer" class="ABI_SOLID">AB 5500xl Genetic Analyzer</option>
                <option value="AB 5500x-Wl Genetic Analyzer" class="ABI_SOLID">AB 5500x-Wl Genetic Analyzer</option>
                <option value="AB SOLiD 3 Plus System" class="ABI_SOLID">AB SOLiD 3 Plus System</option>
                <option value="AB SOLiD 4 System" class="ABI_SOLID">AB SOLiD 4 System</option>
                <option value="AB SOLiD 4hq System" class="ABI_SOLID">AB SOLiD 4hq System</option>
                <option value="AB SOLiD PI System" class="ABI_SOLID">AB SOLiD PI System</option>
                <option value="AB SOLiD System" class="ABI_SOLID">AB SOLiD System</option>
                <option value="AB SOLiD System 2.0" class="ABI_SOLID">AB SOLiD System 2.0</option>
                <option value="AB SOLiD System 3.0" class="ABI_SOLID">AB SOLiD System 3.0</option>
                <option value="Complete Genomics" class="Complete_genomics">Complete Genomics</option>
                <option value="PacBio RS" class="Pacbio_smrt">PacBio RS</option>
                <option value="PacBio RS II" class="Pacbio_smrt">PacBio RS II</option>
                <option value="PacBio Sequel" class="Pacbio_smrt">PacBio Sequel</option>
                <option value="PacBio Sequel II" class="Pacbio_smrt">PacBio Sequel II</option>
                <option value="Ion Torrent PGM" class="Ion_torrent">Ion Torrent PGM</option>
                <option value="Ion Torrent Proton" class="Ion_torrent">Ion Torrent Proton</option>
                <option value="Ion Torrent S5 XL" class="Ion_torrent">Ion Torrent S5 XL</option>
                <option value="Ion Torrent S5" class="Ion_torrent">Ion Torrent S5</option>
                <option value="AB 310 Genetic Analyzer" class="Capillary">AB 310 Genetic Analyzer</option>
                <option value="AB 3130 Genetic Analyzer" class="Capillary">AB 3130 Genetic Analyzer</option>
                <option value="AB 3130xL Genetic Analyzer" class="Capillary">AB 3130xL Genetic Analyzer</option>
                <option value="AB 3500 Genetic Analyzer" class="Capillary">AB 3500 Genetic Analyzer</option>
                <option value="AB 3500xL Genetic Analyzer" class="Capillary">AB 3500xL Genetic Analyzer</option>
                <option value="AB 3730 Genetic Analyzer" class="Capillary">AB 3730 Genetic Analyzer</option>
                <option value="AB 3730xL Genetic Analyzer" class="Capillary">AB 3730xL Genetic Analyzer</option>
                <option value="GridION" class="Oxford_nanopore">GridION</option>
                <option value="MinION" class="Oxford_nanopore">MinION</option>
                <option value="PromethION" class="Oxford_nanopore">PromethION</option>
                <option value="BGISEQ-500" class="BgiSeq">BGISEQ-500</option>
                @endif
              </select>
            </div>

            <div class="form-group" title="Free-form description of the methods used to create the sequencing library">
              <label for="design_description">Design Description</label><span class="text-danger"> *</span>
              <textarea class="form-control" name="design_description" id="design_description">{{old('design_description')?old('design_description'):''}}</textarea>
            </div>

            <div class="form-group">
              <label for="filetype">Filetype</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="filetype" id="filetype" disabled value="fastq">
            </div>
          </div>

          <div class="w-50">
            <div class="form-group">
              <label for="select_application">Choose a Application</label><span class="text-danger"> *</span>
              <select class="custom-select" name="select_application" id="select_application">
                @if(old('select_application'))
                <option value={{old('select_application')?old('select_application'):''}} selected>{{old('select_application')?old('select_application'):''}}</option>
                @endif
                @foreach($applications as $application)
                <option value="{{$application->id}}">{{$application->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="select_species">Choose a Species</label>
              <select class="custom-select" name="select_species" id="select_species">
                @if(old('select_species'))
                <option value={{old('select_species')?old('select_species'):''}} selected>{{old('select_species')?old('select_species'):''}}</option>
                @endif
                <option selected></option>
                @foreach($all_species as $species)
                <option value="{{$species->id}}">{{$species->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active mt-3" id="file" role="tabpanel" aria-labelledby="file-tab">
                <div class="form-group" title="Paired-end or Single">
                  <label>Paired-end</label><span class="text-danger"> *</span>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio1" name="isPairends" class="custom-control-input singleEnds" value="Single">
                    <label class="custom-control-label" for="customRadio1">Single</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio2" name="isPairends" class="custom-control-input pairEnds" value="Paired-end" checked>
                    <label class="custom-control-label" for="customRadio2">Paired-end</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="new_fileOne">File 1(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
                  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addFileModal">select files</button>
                  <input type="text" class="form-control mt-2" name="new_fileOne" id="new_fileOne" value={{old('new_fileOne')?old('new_fileOne'):''}}>
                </div>
                <div class="form-group file_two">
                  <label for="new_fileTwo">File 2(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
                  <input type="text" class="form-control fileTwo" name="new_fileTwo" id="new_fileTwo" value={{old('new_fileTwo')?old('new_fileTwo'):''}}>
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
                @isset($file_error)
                <div class="alert alert-danger">
                  <ul>
                    <li>{{ $file_error }}</li>
                  </ul>
                </div>
                @endisset
              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select / Add Files</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <nav>
                      <div class="nav nav-tabs d-flex" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active mr-auto" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Files</a>
                        <a class="btn btn-success btn-sm pt-2" href="/workspace/addSampleFiles">Add Files</a>
                      </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active my-2 rem1" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">Sample Files (Storaged in your dictionary) :
                      </div>
                      @foreach($files as $file)
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input chose_samples" id={{$file}} value={{$file}}>
                        <label class="custom-control-label" for={{$file}}>{{$file}}</label>
                      </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary sample_files_save">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
@endsection
