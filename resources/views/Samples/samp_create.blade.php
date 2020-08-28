@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @if(strpos(url()->full(),'from'))
      @include('components.workspace_nav')
      @endif
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-4">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="new_sample_label">SampleLabel</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_sample_label" id="new_sample_label">
        </div>

        <div class="form-group">
          <label for="select_application">Choose a application</label><span class="text-danger">*</span>
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
          <label>PairEnds?</label><span class="text-danger">*</span>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio1" name="isPairends" class="custom-control-input singleEnds" value="singleEnds">
            <label class="custom-control-label" for="customRadio1">SingleEnds</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio2" name="isPairends" class="custom-control-input pairEnds" value="pairEnds" checked>
            <label class="custom-control-label" for="customRadio2">PairEnds</label>
          </div>
        </div>
        <p class="tips"><strong>Tips:</strong> 1.<strong>* </strong>means required field;<br />2.default root dictionary is '<strong>{{$base_path}}</strong>', you can input the absolute path or relative path based on the root dictionary, you can also change the root dictionary by going to the "<strong>.env</strong>" file to change "<strong>BASE_PATH</strong>" if needed.</p>
        <div class="form-group">
          <label for="new_fileOne">File 1(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger">*</span>
          <input type="text" class="form-control" name="new_fileOne" id="new_fileOne">
        </div>

        <div class="form-group file_two">
          <label for="new_fileTwo">File 2(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label><span class="text-danger">*</span>
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
      </form>
    </div>
  </div>
</div>
@endsection
