@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <form method="post" action="" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="new_species_name" class="font-normal">Species Name</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_species_name" id="new_species_name" value={{old('new_species_name')?old('new_species_name'):''}}>
        </div>
        <div class="form-group">
          <label for="new_reference_genome" class="font-normal">Reference Genome(.fasta/.fa)</label><span class="text-danger">*</span>
          <input type="file" class="form-control-file new_reference_genome" name="new_reference_genome" id="new_reference_genome">
          <div class="btn btn-default border" style="margin-top:-29px">Browse...</div>
        </div>
        <div class="form-group">
          <label for="new_genome_annotation" class="font-normal">Genome Annotation(.gff)</label><span class="text-danger">*</span>
          <input type="file" class="form-control-file new_genome_annotation" name="new_genome_annotation" id="new_genome_annotation">
          <div class="btn btn-default border" style="margin-top:-29px">Browse...</div>
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
      </form>
    </div>
  </div>
</div>
@endsection
