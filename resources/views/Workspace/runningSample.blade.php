@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-5">
      <div class="bg-white p-3 rounded">
        <div class="rem15 font-weight-bold font-italic text-success border-bottom pb-2">Job List</div>
        @foreach ($user_jobs as $user_job)
        <div class="border-bottom pb-2">
          @if(DB::table('jobs')->where('sample_id',$user_job->sample_id)->value('status') == 1)
          <div class="rem15 font-italic">Sample Label<div class="badge"><span class="badge badge-primary"><span>Running</span><span class="dot">...</span></span></div>
          </div>
          <a class="rem1 text-primary" href="/execute/start?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}</a>
          @elseif(DB::table('jobs')->where('sample_id',$user_job->sample_id)->value('status') == 2)
          <div class="rem15 font-italic">Sample Label<div class="badge"><span class="badge badge-danger">failed</span></div>
          </div>
          <a class="rem1 text-danger" href="/execute/start?sampleID={{$user_job->sample_id}}">{{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}</a>
          @endif
          &nbsp;
          <img src="{{asset('image/finger_to_left.jpg')}}" class="finger_to_left"> point to see current progress status
          <div class="rem15 font-italic">Start</div>
          @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
          <div class="rem1 text-primary started_time">
            <div class="start_time">{{$user_job->started}}</div>
          </div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
          <div class="rem1 text-danger started_time">
            <div class="start_time">{{$user_job->started}}</div>
          </div>
          @endif
          <div class=" rem15 font-italic">Time Period</div>
          <div class="rem1 text-primary run_period">
            <div class="run_time invisible">{{$now - $user_job->started}}</div>
          </div>
        </div>
        @endforeach
        <!-- <div class="rem15 run_params">Pipeline params</div>
        <div class="d-flex flex-wrap justify-content-start mt-1">
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="ass" name="ass" {{$execparams->where('samples_id',$user_job->sample_id)->value('ass') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="ass">ass</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="cnv" name="cnv" {{$execparams->where('samples_id',$user_job->sample_id)->value('cnv') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="cnv">cnv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="snv" name="snv" {{$execparams->where('samples_id',$user_job->sample_id)->value('snv') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="snv">snv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="genus" name="genus" {{$execparams->where('samples_id',$user_job->sample_id)->value('genus') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="genus">genus</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="resfinder_db" name="resfinder_db" {{$execparams->where('samples_id',$user_job->sample_id)->value('resfinder_db') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="nt_db" name="nt_db" {{$execparams->where('samples_id',$user_job->sample_id)->value('nt_db') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="nt_db">nt_db</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="kraken_db" name="kraken_db" {{$execparams->where('samples_id',$user_job->sample_id)->value('kraken_db') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="kraken_db">kraken_db</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="eggnog_db" name="eggnog_db" {{$execparams->where('samples_id',$user_job->sample_id)->value('eggnog') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="kofam_profile" name="kofam_profile" {{$execparams->where('samples_id',$user_job->sample_id)->value('kofam_profile') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
          </div>
          <div class="custom-control custom-checkbox mt-2 mr-3">
            <input type="checkbox" class="custom-control-input" id="kofam_kolist" name="kofam_kolist" {{$execparams->where('samples_id',$user_job->sample_id)->value('kofam_kolist') ? 'checked':''}} disabled>
            <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>
@endsection
