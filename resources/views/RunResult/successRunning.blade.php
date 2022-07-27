@extends('layouts.app')

@section('content')
<div class="container">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-11">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="/workspace">Workspace</a></li>
          <li class="breadcrumb-item"><a href="/workspace/myProject">My Projects</a></li>
          <li class="breadcrumb-item"><a href="/workspace/samples?projectID={{$project_id}}">My Samples</a></li>
          <li class="breadcrumb-item active" aria-current="page">Success</li>
        </ol>
      </nav>
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <div class="bg-white p-3 rounded shadow">
            <div class="font-weight-bold text-dark border-bottom pb-2">
              <div class="rem15">Job Information</div>
              <div class="d-flex mt-2">
                <p>
                  <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapse_detail" aria-expanded="false" aria-controls="collapseExample">
                    Show Detail >>
                  </a>
                </p>
                <p class="ml-2">
                  <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapse_command" aria-expanded="false" aria-controls="collapseExample">
                    Show Command >>
                  </a>
                </p>

                @if(isset($sample_id))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?sampleID={{$sample_id}}" title="Download compress result">
                  <img src="{{asset('/images/zip.jpg')}}" class="download-image" alt="Download compress result">
                </a>
                @elseif(isset($project_user))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?projectID={{$project_id}}" title="Download compress result">
                  <img src="{{asset('/images/zip.jpg')}}" class="download-image" alt="Download compress result">
                </a>
                @endif
              </div>
            </div>
            <div class="collapse" id="collapse_command">
              <div class="card card-body">
                {{$command}}
              </div>
            </div>
            <div class="collapse" id="collapse_detail">
              <div class="card card-body">
                @if(isset($sample_user))
                <div class="d-flex text-dark rem15 mt-2">
                  <div class="mr-3">User : </div>
                  <div class="text-success iframe_sample_user">{{$sample_user}}</div>
                </div>
                @elseif(isset($project_user))
                <div class="d-flex text-dark rem15 mt-2">
                  <div class="mr-3">User : </div>
                  <div class="text-success iframe_project_user">{{$project_user}}</div>
                </div>
                @endif
                @if(isset($sample_uuid))
                <div class="d-none">
                  <div class="mr-3">uuid : </div>
                  <div class="text-success iframe_sample_uuid">{{$sample_uuid}}</div>
                </div>
                @elseif(isset($project_uuid))
                <div class="d-none">
                  <div class="mr-3">uuid : </div>
                  <div class="text-success iframe_project_uuid">{{$project_uuid}}</div>
                </div>
                @endif
                <div class="d-none">
                  <div class="mr-3">uuid : </div>
                  <div class="text-success iframe_project_accession">{{$project_accession}}</div>
                </div>
                @if(isset($file_prefix))
                <div class="d-flex text-dark rem15 mt-2">
                  <div class="mr-3">Sample : </div>
                  <div class="text-success iframe_sample_name">{{$file_prefix}}</div>
                </div>
                @endif
                <div class="d-flex text-dark rem15 mt-2">
                  <div class="mr-3">Started : </div>
                  <div class="text-success start_time">{{$started}}</div>
                </div>
                <div class="d-flex text-dark rem15 mt-2">
                  <div class="mr-3">Finished : </div>
                  <div class="text-success finish_time">{{$finished}}</div>
                </div>
                <div class="d-flex rem15 mt-2 pb-2 border-bottom">
                  <div class="mr-3">Time Period : </div>
                  <div class="text-success Run_time">{{$period}}</div>
                </div>
              </div>
            </div>
            <!-- quast -->
            <div class="mt-3 border-bottom">
              <div class="font-large">Quast</div>
              <div id="quast_explaination">
                <div class="card card-body shadow p-3 mb-3 bg-white rounded">
                  <div>
                    QUAST is a quality assessment tool for genome assemblies.QUAST can evaluate assembly quality even without a reference genome, so that researchers can assess the quality of assemblies of new species that do not yet have a finished reference genome.<br />
                    <b>Duplication ratio</b>: The total number of aligned bases in the assembly (i.e. total length minus unaligned contigs length), divided by the total number of aligned bases in the reference.<br />
                    <b>GC(%)</b>: The total number of G and C nucleotides in the assembly, divided by the total length of the assembly. This metric can be computed without a reference genome.<br />
                    <span style="color:#ff3030"><b>Genome fraction(%)</b>: The total number of aligned bases in the reference, divided by the genome size. A base in the reference genome is counted as aligned if at least one contig has at least one alignment to
                      this base. Contigs from repeat regions may map to multiple places, and thus may be counted multiple times in this quantity.<br /></span>
                    <b>No. of contigs</b>: The total number of contigs in the assembly.<br />
                    <b>Largest contig</b>: The length of the largest contig in the assembly.<br />
                    <b>Total length</b>: The total number of bases in the assembly.<br />
                    <b>Nx (where 0 ≤ x ≤100)</b>: The largest contig length, L, such that using contigs of length L accounts for at least x% of the bases of the assembly.NGx, Genome Nx: The contig length such that using equal or longer length contigs produces x% of the length of the reference genome, rather than x% of the assembly length.<br />
                    <b>NGx, Genome Nx</b>: The contig length such that using equal or longer length contigs produces x% of the length of the reference genome, ather than x% of the assembly length.
                  </div>
                </div>
              </div>
              <img class="fading_circles_quast" src="images/Fading_circles.gif" alt="">
              @if(isset($project_user))
              <div class="table-responsive mb-3 shadow p-3 bg-white rounded border">
                <table id="quast_dataTable" class="display">
                  <thead>
                    <tr></tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              @elseif(isset($sample_uuid))
              <div class="table-responsive mb-3 shadow p-3 bg-white rounded border">
                <table id="quast_dataTable" class="display">
                  <thead>
                    <tr></tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              @endif
            </div>

            <div class="mt-3 border-bottom">
              <div class="font-large">BlobTools</div>
              @if(isset($project_user))
              <select id="blob_txt_tabs" class="selectpicker show-tick mt-2" data-live-search="true" data-style="btn-info">
                @foreach($filename_array as $filename)
                <option value={{$filename}}>{{$filename}}</option>
                @endforeach
              </select>
              @endif
              <!-- blob table -->
              <div class="table-responsive mt-2 mb-3 shadow p-3 bg-white rounded border">
                <table id="blob_dataTable" class="display">
                  <thead>
                    <tr></tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="mt-3">
              <div class="font-large">BlobPlots</div>
              <div class="card card-body shadow p-3 mb-3 bg-white rounded">
                In BlobTools, sequences are depicted as circles in BlobPlots (as opposed to dots in the blobology pipeline), with diameters proportional to sequence length. The scatter-plot is decorated with coverage and GC histograms for each taxonomic group, which are weighted by the total span (cumulative length) of sequences occupying each bin. A legend reflects the taxonomic affiliation of sequences and lists count, total span and N50 by taxonomic group. Taxonomic groups can be plotted at any taxonomic rank and colours are selected dynamically from a colour map. The number of taxonomic groups to be plotted can be controlled and remaining groups are binned into the category 'others'.
              </div>
              <select id="blob_classify" class="selectpicker show-tick mt-2 ml-2" data-live-search="true" data-style="btn-info">
                <option value="superkingdom">superkingdom</option>
                <option value="phylum" selected>phylum</option>
                <option value="order">order</option>
                <option value="family">family</option>
                <option value="genus">genus</option>
                <option value="species">species</option>
              </select>
              <button id="draw_blob_pic" class="btn btn-info btn-default mt-2 ml-2">Plot</button>
              <div id="blob_pic" class="w-100 overflow-hidden"></div>
            </div>
          </div>
        </div>

        <!-- MultiQC -->
        <div class="tab-pane fade" id="v-pills-multiqc" role="tabpanel" aria-labelledby="v-pills-multiqc-tab">
          <div id="iframe_browser">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">MultiQC Reports</div>
            </div>
            <div id="multiqc_report" class="multiqc_report embed-responsive embed-responsive-4by3">
            </div>
          </div>
        </div>

        <!-- kraken -->
        <div class="tab-pane fade" id="v-pills-krona" role="tabpanel" aria-labelledby="v-pills-krona-tab">
          @if(isset($project_user))
          <select id="kraken_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser">
            <div id="iframe_browser_header" class="overflow-auto">
              <div id="iframe_browser_title">Kraken Reports</div>
            </div>
          </div>
          <div class="card card-body shadow p-3 bg-white rounded">
            Kraken is a taxonomic sequence classifier that assigns taxonomic labels to short DNA reads. It does this by examining the k-mers within a read and querying a database with those k-mers. This database contains a mapping of every k-mer in Kraken's genomic library to the lowest common ancestor (LCA) in a taxonomic tree of all genomes that contain that k-mer. The set of LCA taxa that correspond to the k-mers in a read are then analyzed to create a single taxonomic label for the read; this label can be any of the nodes in the taxonomic tree.
          </div>
          <div id="kraken_report" class="kraken_report embed-responsive embed-responsive-4by3">
          </div>
        </div>

        <!-- blob -->
        <div class="tab-pane fade" id="v-pills-blob" role="tabpanel" aria-labelledby="v-pills-blob-tab">
          @if(isset($project_user))
          <select id="blob_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="blob_browser overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">BlobPlots</div>
            </div>
          </div>
          <div class="card card-body shadow p-3 bg-white rounded">
            In BlobTools, sequences are depicted as circles in BlobPlots (as opposed to dots in the blobology pipeline), with diameters proportional to sequence length. The scatter-plot is decorated with coverage and GC histograms for each taxonomic group, which are weighted by the total span (cumulative length) of sequences occupying each bin. A legend reflects the taxonomic affiliation of sequences and lists count, total span and N50 by taxonomic group. Taxonomic groups can be plotted at any taxonomic rank and colours are selected dynamically from a colour map. The number of taxonomic groups to be plotted can be controlled and remaining groups are binned into the category 'others'.
          </div>
          @if(isset($sample_id))
          <img id="blob_image_sample" src='/blob?sample_uuid={{$sample_uuid}}&sample_name={{$file_prefix}}' width="100%" height="100%">
          @elseif(isset($project_user))
          <img id="blob_image" src="" width="100%" height="100%">
          @endif
        </div>

        <!-- preseq -->
        <div class="tab-pane fade" id="v-pills-preseq" role="tabpanel" aria-labelledby="v-pills-preseq-tab">
          @if(isset($project_user))
          <select id="preseq_proj_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($preseq_array as $preseq)
            <option value={{$preseq}}>{{$preseq}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="preseq_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Preseq Reports</div>
              @if(isset($sample_id))
              <ul id="preseq_tabs" class="d-flex">
                @foreach($preseq_array as $preseq)
                <li><a href="#" class="text-truncate">{{$preseq}}</a></li>
                @endforeach
              </ul>
              @endif
            </div>
          </div>
          <div id="preseq_report" class="w-100 overflow-hidden">
          </div>
        </div>

        <!-- arg -->
        <div class="tab-pane fade" id="v-pills-arg" role="tabpanel" aria-labelledby="v-pills-arg-tab">
          @if(isset($project_user))
          <select id="arg_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="arg_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">ARG Reports</div>
            </div>
          </div>
          <div class="table-responsive arg_table mb-3 shadow p-3 bg-white rounded border">
            <table id="arg_dataTable" class="table display">
              <thead>
                <tr>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <!-- bowtie -->
        <div class="tab-pane fade" id="v-pills-bowtie" role="tabpanel" aria-labelledby="v-pills-bowtie-tab">
          @if(isset($project_user))
          <select id="bowtie_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="bowtie_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Bowtie2 Reports</div>
            </div>
          </div>
          <div class="table-responsive bowtie_table mb-3 shadow p-3 bg-white rounded border">
            <table id="bowtie_dataTable" class="display">
              <thead>
                <tr></tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <!-- checkM -->
        <div class="tab-pane fade" id="v-pills-checkM" role="tabpanel" aria-labelledby="v-pills-checkM-tab">
          <div id="iframe_browser" class="checkM_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">CheckM Reports</div>
            </div>
          </div>
          <div class="card card-body shadow p-3 bg-white rounded">
            <p>
              <b>bin id</b>: unique identifier of genome bin (derived from input fasta file)<br />
              <b># genomes</b>: number of reference genomes used to infer the lineage-specific marker set.<br />
              <b># markers</b>: number of marker genes within the inferred lineage-specific marker set.<br />
              <b># marker sets</b>: number of co-located marker sets within the inferred lineage-specific marker set.</br>
              <b>marker lineage</b>: indicates the taxonomic rank of the lineage-specific marker set used to estimated genome completeness, contamination, and strain heterogeneity. More detailed information about the placement of a genome within the reference genome tree can be obtained with the tree_qa command. The UID indicates the branch within the reference tree used to infer the marker set applied to estimate the bins quality.<br />
              <b>0-5+</b>: number of times each marker gene is identified.<br />
              <span style="color:#ff3030"><b>completeness</b>: estimated completeness of genome as determined from the presence/absence of marker genes and the expected collocalization of these genes.<br /></span>
              <b>contamination</b>: estimated contamination of genome as determined by the presence of multi-copy marker genes and the expected collocalization of these genes.<br />
              <b>strain heterogeneity</b>: estimated strain heterogeneity as determined from the number of multi-copy marker pairs which exceed a specified amino acid identity threshold (default = 90%). High strain heterogeneity suggests the majority of reported contamination is from one or more closely related organisms (i.e. potentially the same species), while low strain heterogeneity suggests the majority of contamination is from more phylogenetically diverse sources.<br />
            </p>
          </div>
          <div class="table-responsive checkM_table mb-3 shadow p-3 bg-white rounded border">
            <table id="checkM_dataTable" class="display">
              <thead>
                <tr></tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <!-- eukCC -->
        <div class="tab-pane fade" id="v-pills-EukCC" role="tabpanel" aria-labelledby="v-pills-EukCC-tab">
          @if(isset($project_user))
          <select id="EukCC_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($filename_array as $filename)
            <option value={{$filename}}>{{$filename}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="EukCC_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">EukCC Reports</div>
            </div>
          </div>
          <div class="table-responsive EukCC_table mb-3 shadow p-3 bg-white rounded border">
            <table id="EukCC_dataTable" class="display">
              <thead>
                <tr></tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- right-column -->
    <div class="col-md-1">
      <div class="result-info">
        <div class="w-75 nav flex-column nav-pills list-switch left demo-chooser" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <a class="active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
          <a id="v-pills-multiqc-tab" data-toggle="pill" href="#v-pills-multiqc" role="tab" aria-controls="v-pills-multiqc" aria-selected="false">MultiQC</a>
          <a id="v-pills-krona-tab" data-toggle="pill" href="#v-pills-krona" role="tab" aria-controls="v-pills-krona" aria-selected="false">Krona</a>
          <a id="v-pills-blob-tab" data-toggle="pill" href="#v-pills-blob" role="tab" aria-controls="v-pills-blob" aria-selected="false">BlobPlots</a>
          <a id="v-pills-preseq-tab" data-toggle="pill" href="#v-pills-preseq" role="tab" aria-controls="v-pills-preseq" aria-selected="false">Preseq</a>
          <a id="v-pills-arg-tab" data-toggle="pill" href="#v-pills-arg" role="tab" aria-controls="v-pills-arg" aria-selected="false">ARG</a>
          <a id="v-pills-bowtie-tab" data-toggle="pill" href="#v-pills-bowtie" role="tab" aria-controls="v-pills-bowtie" aria-selected="false">Bowtie2</a>
          @if(strcmp($species, 'bacterium') == 0)
          <a id="v-pills-checkM-tab" data-toggle="pill" href="#v-pills-checkM" role="tab" aria-controls="v-pills-checkM" aria-selected="false">CheckM</a>
          @else
          <a id="v-pills-EukCC-tab" data-toggle="pill" href="#v-pills-EukCC" role="tab" aria-controls="v-pills-EukCC" aria-selected="false">EukCC</a>
          @endif
        </div>
      </div>
    </div>

  </div>

  @endsection

  @push('plotting-js')
  <script src="https://cdn.jsdelivr.net/npm/plotly.js@2.11.1/dist/plotly-basic.min.js"></script>
  @endpush

  @section('script')
  <script src="{!! mix('js/plotting.js')!!}"></script>
  @endsection
