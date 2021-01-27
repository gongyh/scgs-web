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
                  <div class="product-img mt-1">
                    <i class="fa fa-unlock-alt fa-2x ext-icon"></i>
                  </div>
                  <div class="product-info">
                    <a href="/password/reset" class="product-title">
                      password reset
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
