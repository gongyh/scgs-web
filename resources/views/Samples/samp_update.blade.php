@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form method="post" action="">
        @csrf
        <div class="form-group">
          <label for="sample_label">SampleLabel</label>
          <input type="text" class="form-control" name="sample_label" id="sample_label" value="{{$sample->sampleLabel}}">
        </div>

        <div class="form-group">
          <label for="select_application">Choose a application</label>
          <select class="custom-select" name="select_application" id="select_application">
            <option selected value="{{$app->id}}">{{$app->name}}</option>
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
          <label>PairEnds?</label>
          @if($sample->pairends == 0)
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio1" name="isPairends" class="custom-control-input singleEnds" value="singleEnds" checked="checked">
            <label class="custom-control-label" for="customRadio1">SingleEnds</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio2" name="isPairends" class="custom-control-input pairEnds" value="pairEnds">
            <label class="custom-control-label" for="customRadio2">PairEnds</label>
          </div>

          @else
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio1" name="isPairends" class="custom-control-input singleEnds" value="singleEnds">
            <label class="custom-control-label" for="customRadio1">SingleEnds</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="customRadio2" name="isPairends" class="custom-control-input pairEnds" value="pairEnds" checked="checked">
            <label class="custom-control-label" for="customRadio2">PairEnds</label>
          </div>
        </div>
        @endif

        <div class="form-group">
          <label for="fileOne">File 1(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label>
          <input type="text" class="form-control" name="fileOne" id="fileOne" value="{{$sample->filename1}}">
        </div>

        <div class="form-group file_two">
          <label for="fileTwo">File 2(.fasta.gz/.fastq.gz/.fasta/.fastq/.fa)</label>
          <input type="text" class="form-control fileTwo" name="fileTwo" id="fileTwo" value="{{$sample->filename2}}">
        </div>
        <p class="text-black-50"><strong>Tips:</strong> The default root dictionary is 'D:\', you can input the absolute path or relative path based on the root dictionary, you can also go to the "config - filesystem.php - disk - local - root" to change the root dictionary if needed.</p>
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
