@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Sample Files</li>
        </ol>
      </nav>

      <div>
        <p class="rem1 text-danger upload-tip">You can upload <b>5 (.fastq,.fasta,.fq,.fa,.fastq.gz,.fasta.gz)</b> files at most at the same time. Each file no more than <b>2000M</b> .</p>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label for="addSampleFiles" class="control-label"></label>
          <input type="file" name="addSampleFiles" id="addSampleFiles" multiple>
        </div>
        <div class="mt-3 col-md-6 overflow-auto files_in_dict">
          <div class="rem15">Files In Your Dictionary</div>
          @foreach($fileList as $file)
          <div>
            <span class="badge badge-pill badge-success">Prepared</span>
            <span class="rem1">{{$file}}</span>
          </div>
          @endforeach
        </div>
      </div>

      <hr>
      <div class="row">
        <div class="col-md-6">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="ncbi-tab" data-toggle="tab" href="#ncbi" role="tab" aria-controls="ncbi" aria-selected="true">NCBI</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="ebi-tab" data-toggle="tab" href="#ebi" role="tab" aria-controls="ebi" aria-selected="false">EBI</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="era-tab" data-toggle="tab" href="#era" role="tab" aria-controls="era" aria-selected="false">ERA</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ncbi" role="tabpanel" aria-labelledby="ncbi-tab">
              <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="custom-file mt-3">
                  <input type="file" name="sra_id_file" class="custom-file-input" id="sra_id_file">
                  <label class="custom-file-label" for="sra_id_file" id="sra_id_label">Choose a sra id txt file</label>
                </div>
                <div class="text-danger font-weight-bolder">Or</div>
                <div class="form-group mt-2">
                  <label for="ncbi_sra_id" class="rem1">NCBI sra-id</label>
                  <input type="text" class="form-control" id="ncbi_sra_id" name="ncbi_sra_id">
                </div>
                <button id="ncbi_submit" type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
            <div class="tab-pane fade" id="ebi" role="tabpanel" aria-labelledby="ebi-tab">...</div>
            <div class="tab-pane fade" id="era" role="tabpanel" aria-labelledby="era-tab">...</div>
          </div>

        </div>
        <div class="col-md-6" id="preparing_list">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
