@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <!-- <div class="middle-info">

      </div> -->
      <div class="bg-white rounded shadow-sm p-3 exec-params">
        <div class="exec-title">execute params setting</div>
        <div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="ass">
            <label class="custom-control-label" for="ass">ass</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="cnv">
            <label class="custom-control-label" for="cnv">cnv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="snv">
            <label class="custom-control-label" for="snv">snv</label>
          </div>
          <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="saturation">
            <label class="custom-control-label" for="saturation">saturation</label>
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="resfinder_db" value="resfinder_db">
              <label class="custom-control-label" for="resfinder_db">resfinder_db</label>
            </div>
            <input type="text" class="form-control resfinder_db_path" placeholder="/mnt/data7/gongyh/ARG/resfinder_db">
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="nt_db">
              <label class="custom-control-label" for="nt_db">nt_db</label>
            </div>
            <input type="text" class="form-control nt_db_path" placeholder="/mnt/data7/gongyh/ncbi_databases">
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kraken_db">
              <label class="custom-control-label" for="kraken_db">kraken_db</label>
            </div>
            <input type="text" class="form-control kraken_db_path" placeholder="/mnt/data7/gongyh/ncbi_databases">
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="eggnog_db">
              <label class="custom-control-label" for="eggnog_db">eggnog_db</label>
            </div>
            <input type="text" class="form-control eggnog_db_path" placeholder="/mnt/data7/gongyh/databases/eggnogv5">
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_profile">
              <label class="custom-control-label" for="kofam_profile">kofam_profile</label>
            </div>
            <input type="text" class="form-control kofam_profile_path" placeholder="/mnt/scc8t/gongyh/jingxy/KofamKOALA/db/profiles">
          </div>
          <div class="d-flex mt-2">
            <div class="custom-control custom-checkbox mr-4">
              <input type="checkbox" class="custom-control-input" id="kofam_kolist">
              <label class="custom-control-label" for="kofam_kolist">kofam_kolist</label>
            </div>
            <input type="text" class="form-control kofam_kolist_path" placeholder="/mnt/scc8t/gongyh/jingxy/KofamKOALA/db/ko_list">
          </div>
        </div>
        <div class="mt-5 d-flex justify-content-around">
          <button type="button" class="btn btn-success">execute</button>
          <button type="button" class="btn bg-secondary" disabled>stop</button>
        </div>
      </div>

    </div>
    <!-- right-column -->
    <div class="col-md-3 right-column">

      <div class="other-info">

      </div>
    </div>
  </div>

</div>
@endsection
