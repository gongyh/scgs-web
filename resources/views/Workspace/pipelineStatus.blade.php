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
          @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
          <div class="rem15 font-italic">Sample Label<div class="badge">
              <span class="badge badge-primary">
                <span>Running</span>
                <span class="dot">...</span>
              </span>
            </div>
          </div>
          <a class="rem1 text-primary" href="/execute/start?sampleID={{$user_job->sample_id}}">
            {{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}
          </a>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
          <div class="rem15 font-italic">Sample Label<div class="badge">
              <span class="badge badge-danger">failed</span>
            </div>
          </div>
          <a class="rem1 text-danger" href="/failedRunning?uuid={{$user_job->uuid}}">
            {{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}
          </a>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
          <div class="rem15 font-italic">Sample Label<div class="badge">
              <span class="badge badge-success">success</span>
            </div>
          </div>
          <a class="rem1 text-success" href="/successRunning?sampleID={{$user_job->sample_id}}">
            {{$samples->where('id',$user_job->sample_id)->value('sampleLabel')}}
          </a>
          @endif

          &nbsp;
          <img src="{{asset('images/finger_to_left.jpg')}}" class="finger_to_left"> point to see current progress status

          <div class="rem15 font-italic">Start</div>
          @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
          <div class="rem1 text-primary started_time">
            <div class="start_time">{{$user_job->started}}</div>
          </div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
          <div class="rem1 text-danger started_time">
            <div class="start_time">{{$user_job->started}}</div>
          </div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
          <div class="rem1 text-success started_time">
            <div class="start_time">{{$user_job->started}}</div>
          </div>
          @endif

          <div class="rem15 font-italic">finish</div>
          @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
          <div class="rem1 text-primary"> - - : - - </div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
          <div class="rem1 text-danger finish_time">{{$user_job->finished}}</div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
          <div class="rem1 text-success finish_time">{{$user_job->finished}}</div>
          @endif

          <div class=" rem15 font-italic">Time Period</div>
          @if(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 1)
          <div class="rem1 text-primary Run_time">{{$now - $user_job->started}}</div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 2)
          <div class="rem1 text-danger Run_time">{{$user_job->finished - $user_job->started}}</div>
          @elseif(DB::table('jobs')->where('uuid',$user_job->uuid)->value('status') == 3)
          <div class="rem1 text-success Run_time">{{$user_job->finished - $user_job->started}}</div>
          @endif
        </div>
        @endforeach

      </div>
    </div>
  </div>
</div>
@endsection
