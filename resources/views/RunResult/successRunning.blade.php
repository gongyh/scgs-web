@extends('layouts.app')

@section('content')
<div class="flex-container">

  <!-- middle-area -->
  <!-- left column -->
  <div class="row middle-area">
    <div class="col-md-2"></div>
    <div class="col-md-8">
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
                    Detail
                    <i class="fa-solid fa-circle-info" style="font-size:16px"></i>
                  </a>
                </p>
                <p class="ml-2">
                  <a class="btn btn-light border" type="a" data-toggle="collapse" data-target="#collapse_command" aria-expanded="false" aria-controls="collapseExample">
                    Command
                    <i class="fa-solid fa-terminal"></i>
                  </a>
                </p>

                @if(isset($sample_id))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?sampleID={{$sample_id}}" title="Download compress result">
                  <i class="fa-sharp fa-solid fa-download" style="font-size:28px"></i>
                </a>
                @elseif(isset($project_user))
                <a class="ml-2 mt-1" href="/successRunning/resultDownload?projectID={{$project_id}}" title="Download compress result">
                  <i class="fa-sharp fa-solid fa-download" style="font-size:28px"></i>
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
                    <b>No. of contigs</b>: The total number of contigs in the assembly.<br />
                    <b>Largest contig</b>: The length of the largest contig in the assembly.<br />
                    <b>Total length</b>: The total number of bases in the assembly.<br />
                    <b>Nx (where 0 ≤ x ≤100)</b>: The largest contig length, L, such that using contigs of length L accounts for at least x% of the bases of the assembly.NGx, Genome Nx: The contig length such that using equal or longer length contigs produces x% of the length of the reference genome, rather than x% of the assembly length.<br />
                    <b>NGx, Genome Nx</b>: The contig length such that using equal or longer length contigs produces x% of the length of the reference genome, ather than x% of the assembly length.<br />
                    <b>Genome fraction(%)</b>: The total number of aligned bases in the reference, divided by the genome size. A base in the reference genome is counted as aligned if at least one contig has at least one alignment to
                    this base. Contigs from repeat regions may map to multiple places, and thus may be counted multiple times in this quantity(This result is not available if there is no reference genome).
                  </div>
                  <div class="mt-3">
                    <span>paper link: </span>
                    <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3624806/" target="blank">QUAST: quality assessment tool for genome assemblies</a>
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
              <div class="card card-body shadow p-3 mb-3 bg-white rounded">
                <div>
                  BlobTools assigns a single NCBI taxonomy for each sequence in the assembly, based on the highest scoring NCBI TaxID at the following taxonomic ranks: species, genus, family, order, phylum, and superkingdom. Score calculation can be controlled by the user through a minimal score threshold (--min_score) and a minimal difference in scores (--min_diff) between the best and second-best scoring taxonomy. In addition, three non-canonical taxonomic annotations are possible: 'no-hit', the suffix '-undef' and 'unresolved'. Sequences not assigned to any taxonomic group, or not passing the --min_score threshold, are labelled 'no-hit'. If a NCBI TaxID has no explicit parent at a taxonomic rank, the suffix '-undef' is appended to the next upper taxonomic rank for which one does exist. In cases where the score difference between the best and second-best hits is smaller than --min_diff, sequences are labelled as 'unresolved'.
                </div>
                <div class="mt-3">
                  <span>paper link: </span>
                  <a href="https://doi.org/10.12688/f1000research.12232.1" target="blank">BlobTools: Interrogation of genome assemblies [version 1; peer review: 2 approved with reservations]</a>
                </div>
              </div>
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
                <div>
                  In BlobTools, sequences are depicted as circles in BlobPlots (as opposed to dots in the blobology pipeline), with diameters proportional to sequence length. The scatter-plot is decorated with coverage and GC histograms for each taxonomic group, which are weighted by the total span (cumulative length) of sequences occupying each bin. A legend reflects the taxonomic affiliation of sequences and lists count, total span and N50 by taxonomic group. Taxonomic groups can be plotted at any taxonomic rank and colours are selected dynamically from a colour map. The number of taxonomic groups to be plotted can be controlled and remaining groups are binned into the category 'others'.
                </div>
                <div class="mt-3">
                  <span>paper link: </span>
                  <a href="https://doi.org/10.12688/f1000research.12232.1" target="blank">BlobTools: Interrogation of genome assemblies [version 1; peer review: 2 approved with reservations]</a>
                </div>
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

        <!-- Kraken -->
        <div class="tab-pane fade" id="v-pills-krona" role="tabpanel" aria-labelledby="v-pills-krona-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <div>
              Kraken is a taxonomic sequence classifier that assigns taxonomic labels to short DNA reads. It does this by examining the k-mers within a read and querying a database with those k-mers. This database contains a mapping of every k-mer in Kraken's genomic library to the lowest common ancestor (LCA) in a taxonomic tree of all genomes that contain that k-mer. The set of LCA taxa that correspond to the k-mers in a read are then analyzed to create a single taxonomic label for the read; this label can be any of the nodes in the taxonomic tree.
            </div>
            <div class="mt-3">
              <span>paper link: </span>
              <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4053813/" target="blank">Kraken: ultrafast metagenomic sequence classification using exact alignments</a>
            </div>
          </div>
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
          <div id="kraken_report" class="kraken_report embed-responsive embed-responsive-4by3 shadow p-3 bg-white rounded border">
          </div>
        </div>

        <!-- BlobTools -->
        <div class="tab-pane fade" id="v-pills-blob" role="tabpanel" aria-labelledby="v-pills-blob-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <div>
              In BlobTools, sequences are depicted as circles in BlobPlots (as opposed to dots in the blobology pipeline), with diameters proportional to sequence length. The scatter-plot is decorated with coverage and GC histograms for each taxonomic group, which are weighted by the total span (cumulative length) of sequences occupying each bin. A legend reflects the taxonomic affiliation of sequences and lists count, total span and N50 by taxonomic group. Taxonomic groups can be plotted at any taxonomic rank and colours are selected dynamically from a colour map. The number of taxonomic groups to be plotted can be controlled and remaining groups are binned into the category 'others'.
            </div>
            <div class="mt-3">
              <span>paper link: </span>
              <a href="https://doi.org/10.12688/f1000research.12232.1" target="blank">BlobTools: Interrogation of genome assemblies [version 1; peer review: 2 approved with reservations]</a>
            </div>
          </div>
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
          <div class="shadow p-3 bg-white rounded border">
            @if(isset($sample_id))
            <img id="blob_image_sample" src='/blob?sample_uuid={{$sample_uuid}}&sample_name={{$file_prefix}}' width="100%" height="100%">
            @elseif(isset($project_user))
            <img id="blob_image" src="" width="100%" height="100%">
            @endif
          </div>
        </div>

        <!-- Preseq -->
        <div class="tab-pane fade" id="v-pills-preseq" role="tabpanel" aria-labelledby="v-pills-preseq-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <div>
              <b>c</b>: _c(params c_curve) is used to compute the expected complexity curve of a mapped read file with a hypergeometric formula. Output is a text file with two columns. The first gives the total number of reads and the second
              the corresponding number of distinct reads. The diagram is drawn based on this txt file.
            </div>
            <div>
              <b>lc</b>: _lc(params lc_extrap) is used to generate the expected yield for theoretical larger experiments and bounds on the number of distinct reads in the library and the associated confidence intervals, which is computed by bootstrapping the observed duplicate counts histogram. Output is a text file with four columns. The first is the total number of reads, second gives the corresponding average expected number of distinct reads, and the third and fourth give the lower and upper limits of the confidence interval. The diagram is drawn based on this txt file.
            </div>
            <div>
              <b>gc</b>: _gc(params gc_extrap) is used to extrapolate the expected number of bases covered at least once for theoretical larger experiments. Input format is required to be in mapped read format and we have provided the tool to-mr to convert bam format files to mr. Output is a text file with four columns. The first is the total number of sequenced and mapped bases, second gives the corresponding expected number of distinct bases covered, and the third and fourth give the lower and upper limits of the confidence interval.The diagram is drawn based on this txt file.
            </div>
          </div>
          @if(isset($project_user))
          <select id="preseq_proj_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($preseq_array as $preseq)
            <option value={{$preseq}}>{{$preseq}}</option>
            @endforeach
          </select>
          @endif
          @if(isset($sample_id))
          <select id="preseq_tabs" class="selectpicker show-tick mb-2" data-live-search="true" data-style="btn-info">
            @foreach($preseq_array as $preseq)
            <option value={{$preseq}}>{{$preseq}}</option>
            @endforeach
          </select>
          @endif
          <div id="iframe_browser" class="preseq_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">Preseq Reports</div>
            </div>
          </div>
          <div id="preseq_report" class="w-100 overflow-hidden shadow p-3 bg-white rounded border">
          </div>
        </div>

        <!-- ARG -->
        <div class="tab-pane fade" id="v-pills-arg" role="tabpanel" aria-labelledby="v-pills-arg-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <div>
              ResFinder is a web-based method that uses BLAST for identification of acquired antimicrobial resistance genes in whole-genome data. It provides a method to detect the presence of 1862 different resistance genes from 12 different antimicrobial classes in WGS data.ResFinder can also be used to ignore known acquired resistance genes in a search for new resistance genes.
            </div>
            <div class="mt-3">
              <span>paper link: </span>
              <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3468078/" target="blank">Identification of acquired antimicrobial resistance genes</a>
            </div>
          </div>
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
          <div class="table-responsive arg_table mb-3 p-3 shadow bg-white rounded border">
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

        <!-- Bowtie2 -->
        <div class="tab-pane fade" id="v-pills-bowtie" role="tabpanel" aria-labelledby="v-pills-bowtie-tab">
          <div class="card card-body shadow p-3 mb-3 bg-white rounded">
            <p>
              Bowtie 2 is an ultrafast and memory-efficient tool for aligning sequencing reads to long reference sequences. It is particularly good at aligning reads of about 50 up to 100s of characters to relatively long (e.g. mammalian) genomes.<br />
              <b>1st fragments</b>: total number of first fragments<br />
              <b>average first fragment length</b>: average first fragment length<br />
              <b>average last fragment length</b>: average last fragment length<br />
              <b>average length</b>: average sequence length<br />
              <b>average quality</b>: average sequence quality<br />
              <b>bases duplicated</b>: number of duplicated bases<br />
              <b>bases inside the target</b>: bases inside the target<br />
              <b>bases mapped</b>: number of mapped bases<br />
              <b>bases trimmed</b>: number of trimmed bases
            </p>
            <div>
              <span>paper link: </span>
              <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3322381/" target="blank">Fast gapped-read alignment with Bowtie 2</a>
            </div>
          </div>
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

        <!-- CheckM -->
        <div class="tab-pane fade" id="v-pills-checkM" role="tabpanel" aria-labelledby="v-pills-checkM-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <p>
              <b>bin id</b>: unique identifier of genome bin (derived from input fasta file)<br />
              <b># genomes</b>: number of reference genomes used to infer the lineage-specific marker set.<br />
              <b># markers</b>: number of marker genes within the inferred lineage-specific marker set.<br />
              <b># marker sets</b>: number of co-located marker sets within the inferred lineage-specific marker set.</br>
              <b>marker lineage</b>: indicates the taxonomic rank of the lineage-specific marker set used to estimated genome completeness, contamination, and strain heterogeneity. More detailed information about the placement of a genome within the reference genome tree can be obtained with the tree_qa command. The UID indicates the branch within the reference tree used to infer the marker set applied to estimate the bins quality.<br />
              <b>0-5+</b>: number of times each marker gene is identified.<br />
              <b>completeness</b>: estimated completeness of genome as determined from the presence/absence of marker genes and the expected collocalization of these genes.<br />
              <b>contamination</b>: estimated contamination of genome as determined by the presence of multi-copy marker genes and the expected collocalization of these genes.<br />
              <b>strain heterogeneity</b>: estimated strain heterogeneity as determined from the number of multi-copy marker pairs which exceed a specified amino acid identity threshold (default = 90%). High strain heterogeneity suggests the majority of reported contamination is from one or more closely related organisms (i.e. potentially the same species), while low strain heterogeneity suggests the majority of contamination is from more phylogenetically diverse sources.<br />
            </p>
            <div>
              <span>paper link: </span>
              <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4484387/" target="blank">CheckM: assessing the quality of microbial genomes recovered from isolates, single cells, and metagenomes</a>
            </div>
          </div>
          <div id="iframe_browser" class="checkM_report overflow-auto">
            <div id="iframe_browser_header">
              <div id="iframe_browser_title">CheckM Reports</div>
            </div>
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

        <!-- EukCC -->
        <div class="tab-pane fade" id="v-pills-EukCC" role="tabpanel" aria-labelledby="v-pills-EukCC-tab">
          <div class="card card-body shadow mb-3 p-3 bg-white rounded">
            <div>
              EukCC is a tool for estimating the quality of eukaryotic genomes based on the automated dynamic selection of single copy marker gene sets. We demonstrate that our method outperforms current genome quality estimators, particularly for estimating contamination, and have applied EukCC to datasets derived from two different environments to enable the identification of novel eukaryote genomes, including one from the human skin.
            </div>
            <div class="mt-3">
              <span>paper link: </span>
              <a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC7488429/" target="blank">Estimating the quality of eukaryotic genomes recovered from metagenomic analysis with EukCC</a>
            </div>
          </div>
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
    <div class="col-md-2">
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

  @section('script')
  <script src="{!! mix('js/plotting.js')!!}"></script>
  @endsection
