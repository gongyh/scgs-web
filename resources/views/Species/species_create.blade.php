@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_species_name" class="input_title">Species Name</label>
          <input type="text" class="form-control" name="new_species_name" id="new_species_name">
        </div>

        <div class="form-group">
          <label for="new_fasta" class="input_title">Path of Reference Genome(.fasta)</label>
          <input type="text" class="form-control" name="new_fasta" id="new_fasta">
        </div>

        <div class="form-group">
          <label for="new_gff" class="input_title">Path of Genome Annotation(.gff)</label>
          <input type="text" class="form-control" name="new_gff" id="new_gff">
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