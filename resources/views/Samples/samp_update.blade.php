@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <form class="layui-form" id="sampleUpdateForm">
        @csrf
        <div class="d-flex flex-around">
          <div class="w-50 pr-5">
            <div class="layui-form-item" title="BioSample name">
              <label for="sampleLabel">Sample Label</label><span class="text-danger"> *</span>
              <input type="text" class="layui-input" name="sampleLabel" id="sampleLabel" lay-verify="required" lay-filter="sampleL" value={{$sample->sampleLabel}}>
            </div>
            <div class="layui-form-item">
              <label for="library_id">Library ID</label><span class="text-danger"> *</span>
              <input type="text" class="layui-input" name="library_id" id="library_id" lay-filter="libraryID" lay-verify="required" value={{$sample->library_id}}>
            </div>
            <div class="layui-form-item">
              <label for="library_strategy">Library Strategy</label><span class="text-danger"> *</span>
              <select name="library_strategy" id="library_strategy" lay-filter="libraryS">
                @foreach($library_strategies as $library_strategy)
                <option value={{$library_strategy}} @if($sample->library_strategy == $library_strategy)selected @endif>{{$library_strategy}}</option>
                @endforeach
              </select>
            </div>
            <div class="layui-form-item">
              <label for="library_source">Library Source</label><span class="text-danger"> *</span>
              <select name="library_source" id="library_source" lay-filter="librarySo">
                @foreach($library_sources as $library_source)
                <option value={{$library_source}} @if($sample->library_source == $library_source)selected @endif>{{$library_source}}</option>
                @endforeach
              </select>
            </div>
            <div class="layui-form-item">
              <label for="library_selection">Library Selection</label><span class="text-danger"> *</span>
              <select class="custom-select" name="library_selection" id="library_selection" lay-filter="librarySe">
                @foreach($library_selections as $library_selection)
                <option value={{$library_selection}} @if($sample->library_selection == $library_selection)selected @endif>{{$library_selection}}</option>
                @endforeach
              </select>
            </div>
            <div class="layui-form-item">
              <label for="platform">Platform</label><span class="text-danger"> *</span>
              <select name="platform" id="platform" lay-filter="platform">
                <option selected value={{$sample->platform}}>{{$sample->platform}}</option>
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
            <div class="layui-form-item">
              <label for="instrument_model">Instrument_model</label><span class="text-danger"> *</span>
              <select name="instrument_model" id="instrument_model" lay-filter="instrumentM">
                <option selected value={{$sample->instrument_model}}>{{$sample->instrument_model}}</option>
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
          </div>
          <div class="w-50">
            <div class="layui-form-item layui-form-text" title="Free-form description of the methods used to create the sequencing library">
              <label for="design_description">Design Description</label><span class="text-danger"> *</span>
              <textarea class="form-control" name="design_description" id="design_description" lay-verify="required" lay-filter="designD">{{$sample->design_description}}</textarea>
            </div>
            <div class="layui-form-item">
              <label for="filetype">Filetype</label><span class="text-danger"> *</span>
              <input type="text" class="layui-input bg-light" name="filetype" id="filetype" disabled value="fastq">
            </div>
            <div class="layui-form-item">
              <label for="select_application">Choose a Application</label><span class="text-danger"> *</span>
              <select name="select_application" id="select_application" lay-filter="application">
                @foreach($applications as $application)
                <option value={{$application->id}} @if($sample->applications_id == $application->id)selected @endif>{{$application->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="layui-form-item">
              <label for="select_species">Choose a Species</label>
              <select name="select_species" id="select_species" lay-filter="species">
                <option selected></option>
                @foreach($all_species as $species)
                <option value={{$species->id}} @if($sample->species_id == $species->id)selected @endif>{{$species->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active mt-3" id="file" role="tabpanel" aria-labelledby="file-tab">
                <div class="layui-form-item" title="Paired-end or Single">
                  <label>Paired-end</label><span class="text-danger"> *</span>
                  <div>
                    <input disabled type="radio" id="customRadio1" name="isPairends" class="singleEnds" value="Single" title="single" lay-filter="single" @if($sample->pairends == 0)checked @endif>
                    <input diasbled type="radio" id="customRadio2" name="isPairends" class="pairEnds" value="Paired-end" title="paired" lay-filter="paired" @if($sample->pairends == 1)checked @endif>
                  </div>
                </div>
                <div class="layui-form-item">
                  <label for="new_fileOne">File 1(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
                  <input disabled type="text" class="form-control mt-2" name="fileOne" id="fileOne" value={{$sample->filename1}}>
                </div>
                <div class="layui-form-item file_two">
                  <label for="new_fileTwo">File 2(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger"> *</span>
                  <input disabled type="text" class="form-control mt-2" name="fileTwo" id="fileTwo" value={{$sample->filename2}}>
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
          </div>
        </div>
        <div class="layui-form-item">
          <div class="mt-2 d-flex flex-row-reverse">
            <button type="button" lay-submit class="layui-btn" lay-filter="demo-submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="{!! mix('js/form.js')!!}"></script>
@endsection
