@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="species_name" class="input_title">Species Name</label>
          <input type="text" class="form-control" name="name" id="species_name" value="{{$species->name}}">
        </div>

        <div class="form-group">
          <label for="fasta" class="input_title">Path of Reference Genome(.fasta)</label>
          <input type="text" class="form-control" name="fasta" id="fasta" value="{{$species->fasta}}">
        </div>

        <div class="form-group">
          <label for="gff" class="input_title">Path of Genome Annotation(.gff)</label>
          <input type="text" class="form-control" name="gff" id="gff" value="{{$species->gff}}">
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
