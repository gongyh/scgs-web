@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Sample Files</li>
        </ol>
      </nav>
      <div class="rem15">Files In Your Dictionary</div>
      @foreach($fileList as $file)
      <div>
        <span class="badge badge-pill badge-success">Prepared</span>
        <span class="rem1">{{$file}}</span>
      </div>
      @endforeach

      <label for="addSampleFiles" class="control-label"></label>
      <input type="file" name="addSampleFiles" id="addSampleFiles" multiple>

      <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="custom-file w-50 mt-3">
          <input type="file" name="sra_id_file" class="custom-file-input" id="sra_id_file">
          <label class="custom-file-label" for="sra_id_file" id="sra_id_label">Choose a sra id txt file</label>
        </div>
        <div class="text-danger font-weight-bolder">Or</div>
        <div class="form-group mt-2 w-50">
          <label for="ncbi_sra_id" class="rem1">NCBI sra-id</label>
          <input type="text" class="form-control" id="ncbi_sra_id" name="ncbi_sra_id">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
      </form>
      @if (count($errors) > 0)
      <div class="alert alert-danger mt-2">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
      @endif

      @foreach($preparing_lists as $preparing_list)
      <div>
        <span class="badge badge-primary">
          <span>Upload</span>
          <span class="dot">...</span>
        </span>
        <span class="rem1">{{$preparing_list->sra_id}}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
