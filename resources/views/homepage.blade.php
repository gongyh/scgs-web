<!-- @extends('layouts.app') -->

@section('content')
<div class="container">

  <!-- Jumbotron Header -->
  <header class="jumbotron mb-4">
    <h1 class="">Single-Cell pHenOme-genOme Landscape</h1>
    <p class="lead">Integrate and simplify single cell genome assembly, annotation, and evolutionary analysis.</p>
    <div>
      <a href="/docs" class="btn btn-primary btn-lg"><i class="fa fa-book" aria-hidden="true"></i> Quick Start</a>
      <a href="/projects" class="btn btn-primary btn-lg"><i class="fa fa-search" aria-hidden="true"></i> Explore</a>
    </div>
  </header>

  <!-- Page Features -->
  <div class="row text-center home-slider">
    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">SCGS Pipeline</h4>
          <p class="card-text">SAG analysis</p>
        </div>
        <div class="card-footer">
          <a href="/docs/scgs" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">RamanD2O</h4>
          <p class="card-text">Ramanome analysis</p>
        </div>
        <div class="card-footer">
          <a href="/docs/ramand2o" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">RamanID</h4>
          <p class="card-text">Pathogens identification</p>
        </div>
        <div class="card-footer">
          <a href="/docs/ramanid" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">Pathways</h4>
          <p class="card-text">Metabolic pathways</p>
        </div>
        <div class="card-footer">
          <a href="/docs/pathway" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">Drug Resistance</h4>
          <p class="card-text">AST testing</p>
        </div>
        <div class="card-footer">
          <a href="/docs/ast" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="card h-100">
        <img class="card-img-top" src="https://via.placeholder.com/500x325" alt="">
        <div class="card-body">
          <h4 class="card-title">GWAS</h4>
          <p class="card-text">Mutants screening</p>
        </div>
        <div class="card-footer">
          <a href="/docs/gwas" class="btn btn-primary stretched-link">Find Out More!</a>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

@endsection
