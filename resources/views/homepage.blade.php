<!-- @extends('layouts.app') -->

@section('content')
<div class="container">

  <!-- Jumbotron Header -->
  <header class="jumbotron mb-4">
    <h1 class="">Single-Cell pHenOme-genOme Landscape</h1>
    <p class="lead">Integrate and simplify single cell genome assembly, annotation, and evolutionary analysis.</p>
    <!-- Guides information -->
    <div id="guides">
      <div class="step">
        <p><a class="btn btn-primary" href="/login">Signup & Login</a></p>
      </div>
      <span class="arrow">&#8594;</span>
      <div class="step">
        <p><a class="btn btn-primary" href="/projects/create">Create projects</a></p>
      </div>
      <span class="arrow">&#8594;</span>
      <div class="step">
        <p><a class="btn btn-primary" href="/workspace/addSampleFiles">Upload sequences</a></p>
      </div>
      <span class="arrow">&#8594;</span>
      <div class="step">
        <p><a class="btn btn-primary" href="/workspace/myProject">Analyze projects</a></p>
      </div>
      <span class="arrow">&#8594;</span>
      <div class="step">
        <p><a class="btn btn-primary" href="/workspace/myProject">Inspect results</a></p>
      </div>
    </div>
    <div style="display: none;">
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
          <h4 class="card-title">BGCs</h4>
          <p class="card-text">Biosynthetic gene clusters</p>
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
          <h4 class="card-title">Resistome</h4>
          <p class="card-text">Drug resistance</p>
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
