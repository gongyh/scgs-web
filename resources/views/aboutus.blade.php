@extends('layouts.app')

@section('content')
<div class="container">
<div class="row middle-area">
  <div class="col-md-2 left-column">
    <div class="list-group">
      <a href="#about_cite" class="list-group-item">How to cite</a>
      <a href="#about_contact" class="list-group-item">Contact us</a>
      <a href="#about_credit" class="list-group-item">Credits</a>
    </div>
  </div>
  <div class="col-md-10 middle-column">
    <div class="card" style="width:100%;">
      <div class="card-body">
        <div class="border-bottom" id="about_cite">
          <h2>Citation</h2>
          <p class="card-text">Github repository (pipeline): <a href="https://github.com/gongyh/nf-core-scgs">https://github.com/gongyh/nf-core-scgs</a></p>
          <p class="card-text">Github repository (RamanD2O): <a href="https://github.com/gongyh/RamanD2O">https://github.com/gongyh/RamanD2O</a></p>
          <p class="card-text">Github repository (website): <a href="https://github.com/gongyh/scgs-web">https://github.com/gongyh/scgs-web</a></p>
          <p class="card-text card-last-child">Contact: gongyh@qibebt.ac.cn or zhousq@qibebt.ac.cn</p>
        </div>
        <div class="border-bottom card-pattern" id="about_contact">
          <h2>Contact</h2>
          <p class="card-text">Phone/Fax: 86-532-80662653/2654</p>
          <p class="card-text">Email: singlecell@qibebt.ac.cn</p>
          <p class="card-text">Postal Code: 266101</p>
          <p class="card-text card-last-child">Address: No.189 Songling Road, Laoshan District, Qingdao, Shandong, 266101, China</p>
        </div>
        <div id="about_credit">
          <h2 class="card-pattern"><span class="badge badge-warning">Disclaimer</span></h2>
          <p class="card-text">The data processed by nf-core/scgs pipeline can not be guaranted and assured to be accurate for all and we can not be held responsiblity for any error derived there from.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- right-column -->
  <div class="right-column">
    <div class="other-info">
    </div>
  </div>
</div>
</div>
@endsection
