@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
    @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="species_name" class="input_title">Species Name</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="name" id="species_name" value="{{$species->name}}">
        </div>
        <p class="tips"><strong>Tips:</strong> 1.<strong>* </strong>means required field;<br />2.default root dictionary is '<strong>{{$base_path}}</strong>', you can input the absolute path or relative path based on the root dictionary, you can also change the root dictionary by going to the "<strong>.env</strong>" file to change "<strong>BASE_PATH</strong>" if needed.</p>
        <div class="form-group">
          <label for="fasta" class="input_title">Path of Reference Genome(.fasta/.fa)</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="fasta" id="fasta" value="{{$species->fasta}}">
        </div>

        <div class="form-group">
          <label for="gff" class="input_title">Path of Genome Annotation(.gff)</label><span class="text-danger">*</span>
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
