@extends('layouts.app')

@section('content')
<div class="row middle-area">
  <div class="col-md-3 left-column">
    <div class="list-group list">

    </div>
  </div>
  <div class="col-md-6 middle-column">
    <div class="card" style="width:100%;">
      <img src="{{asset('image/Contact_us.jpg')}}" class="card-img-top" alt="Qibebt">
      <div class="card-body">
        <div class="border-bottom">
          <h2>How to contact us?</h2>
          <p class="card-text">phone/fax : 86-532-80662653/2654</p>
          <p class="card-text">Email : singlecell@qibebt.ac.cn</p>
          <p class="card-text">Postal Code : 266101</p>
          <p class="card-text card-last-child">Address : No.189 Songling Road, Laoshan District, Qingdao, Shandong, 266101, China</p>
        </div>
        <div class="border-bottom card-pattern">
          <h2>How to cite this program?</h2>
          <p class="card-text">We have uploaded this project to the github.</p>
          <p class="card-text card-last-child">Github Address : <a href="https://github.com/gongyh/scgs-web">https://github.com/gongyh/scgs-web</a></p>
        </div>
        <div>
          <h2 class="card-pattern"><span class="badge badge-warning">Disclaimer</span></h2>
          <p class="card-text">The data processed by nf-scgs pipeline can not be guaranted and assured to be accurate for all and we can not be held responsiblity for any error derived there from.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- right-column -->
  <div class="col-md-3 right-column">

    <div class="other-info">

    </div>
  </div>
</div>
@endsection
