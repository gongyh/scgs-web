@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row middle-area">
    <div class="col-md-3">
      @include('components.workspace_nav')
    </div>
    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Workspace</li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-4">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Options</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <i class="fa fa-gears fa-2x ext-icon"></i>
                  </div>
                  <div class="product-info">
                    <a href="#" target="_blank" class="product-title">
                      password change
                    </a>
                    <span class="pull-right installed"><i class="fa fa-check"></i></span>
                  </div>
                </li>
                <li class="item">
                  <div class="product-img">
                    <i class="fa fa-flask fa-2x ext-icon"></i>
                  </div>
                  <div class="product-info">
                    <a href="#" target="_blank" class="product-title">
                      laravel-admin-ext/redis-manager
                    </a>
                  </div>
                </li>

                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
