@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <form method="post" action="">
        @csrf
        <div class="d-flex flex-around">
          <div class="w-50 pr-5">
            <div class="form-group">
              <label for="new_sample_label">SampleLabel</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="new_sample_label" id="new_sample_label">
            </div>

            <div class="form-group">
              <label for="new_library_id">LibraryID</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="new_library_id" id="new_library_id">
            </div>

            <div class="form-group">
              <label for="library_strategy">Library Strategy</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_strategy" id="library_strategy">
                <option selected></option>
                @foreach($library_strategies as $library_strategy)
                <option value={{$library_strategy}}>{{$library_strategy}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="library_source">Library Source</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_source" id="library_source">
                <option selected></option>
                @foreach($library_sources as $library_source)
                <option value={{$library_source}}>{{$library_source}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="library_selection">Library Selection</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_selection" id="library_selection">
                <option selected></option>
                @foreach($library_selections as $library_selection)
                <option value={{$library_selection}}>{{$library_selection}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="platform">Platform</label><span class="text-danger"> *</span>
              <select class="custom-select" name="platform" id="platform">
                <option selected></option>
                <option value="_LS454">_LSE454</option>
                <option value="ABI_SOLID">ABI_SOLID</option>
                <option value="BGISEQ">BGISEQ</option>
                <option value="CAPILLARY">CAPILLARY</option>
                <option value="COMPLETE_GENOMICS">COMPLETE_GENOMICS</option>
                <option value="HELICOS">HELICOS</option>
                <option value="ILLUMINA">ILLUMINA</option>
                <option value="ION_TORRENT">ION_TORRENT</option>
                <option value="OXFORD_NANOPORE">OXFORD_NANOPORE</option>
                <option value="PACBIO_SMRT">PACBIO_SMRT</option>
              </select>
            </div>

            <div class="form-group">
              <label for="instrument_model">Instrument_model</label><span class="text-danger"> *</span>
              <select class="custom-select" name="instrument_model" id="instrument_model">
                <option selected></option>
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
                <option value="Illumina HiSeq 2000" class="Illumina">Illumina HiSeq 2000</option>
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
              </select>
            </div>

            <div class="form-group">
              <label for="design_description">Design Description</label><span class="text-danger"> *</span>
              <textarea class="form-control" name="design_description" id="design_description"></textarea>
            </div>

            <div class="form-group">
              <label for="filetype">Filetype</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="filetype" id="filetype" disabled value="fastq">
            </div>
          </div>

          <div class="w-50">
            <div class="form-group">
              <label for="select_application">Choose a application</label><span class="text-danger"> *</span>
              <select class="custom-select" name="select_application" id="select_application">
                <option selected></option>
                @foreach($applications as $application)
                <option value="{{$application->id}}">{{$application->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="select_species">Choose a species</label>
              <select class="custom-select" name="select_species" id="select_species">
                <option selected></option>
                @foreach($all_species as $species)
                <option value="{{$species->id}}">{{$species->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>PairEnds</label><span class="text-danger"> *</span>
              <div class="custom-control custom-radio">
                <input type="radio" id="customRadio1" name="isPairends" class="custom-control-input singleEnds" value="singleEnds">
                <label class="custom-control-label" for="customRadio1">SingleEnds</label>
              </div>
              <div class="custom-control custom-radio">
                <input type="radio" id="customRadio2" name="isPairends" class="custom-control-input pairEnds" value="pairEnds" checked>
                <label class="custom-control-label" for="customRadio2">PairEnds</label>
              </div>
            </div>
            <p class="tips"><strong>Tips:</strong>1.default root dictionary is '<strong>{{$base_path}}</strong>', you can input the absolute path or relative path based on the root dictionary, you can also change the root dictionary by going to the "<strong>.env</strong>" file to change "<strong>BASE_PATH</strong>" if needed.</p>
            <div class="form-group">
              <label for="new_fileOne">File 1(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" name="new_fileOne" id="new_fileOne">
            </div>

            <div class="form-group file_two">
              <label for="new_fileTwo">File 2(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
              <input type="text" class="form-control fileTwo" name="new_fileTwo" id="new_fileTwo">
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
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
