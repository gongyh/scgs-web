import $ from "jquery";
window.$ = window.jQuery = $;

require("datatables.net");
import Plotly from "plotly.js/dist/plotly";

$(function () {
  var MultiQC = document.getElementById("v-pills-multiqc-tab");
  var krona_tab = document.getElementById("v-pills-krona-tab");
  var blob_tab = document.getElementById("v-pills-blob-tab");
  var preseq_tab = document.getElementById("v-pills-preseq-tab");
  var arg_tab = document.getElementById("v-pills-arg-tab");
  var bowtie_tab = document.getElementById("v-pills-bowtie-tab");
  var checkM_tab = document.getElementById("v-pills-checkM-tab");
  var EukCC_tab = document.getElementById("v-pills-EukCC-tab");
  var image_blob_tabs = $("#blob_tabs");
  var iframe_krona_tabs = $("#kraken_tabs");
  var preseq_proj_tabs = $("#preseq_proj_tabs");
  var preseq_tabs = $("#preseq_tabs");
  var arg_tabs = $("#arg_tabs");
  var bowtie_tabs = $("#bowtie_tabs");
  var eukcc_tabs = $("#EukCC_tabs");
  var blob_txt_tabs = $("#blob_txt_tabs");
  var blob_classify_tabs = $("#blob_classify");
  var krona = document.createElement("iframe");
  var multiqc = document.createElement("iframe");

  if (window.location.href.indexOf("successRunning") != -1) {
    // init
    $("#quast_dataTable thead tr").empty();
    $("#quast_dataTable tbody").empty();
    $("#blob_dataTable thead tr").empty();
    $("#blob_dataTable tbody").empty();
    quast_blob_data();
  }

  if (window.location.href.indexOf("sampleID") != -1) {
    var krona_src =
      "/kraken?sample_uuid=" +
      $(".iframe_sample_uuid").text() +
      "&sample_name=" +
      $(".iframe_sample_name").text();
    var blob_src =
      "/blob?sample_uuid=" +
      $(".iframe_sample_uuid").text() +
      "&sample_name=" +
      $(".iframe_sample_name").text();
    var multiqc_src =
      "/multiqc?sample_uuid=" + $(".iframe_sample_uuid").text();
    krona.setAttribute("src", krona_src);
    krona.setAttribute("class", "embed-responsive-item");
    multiqc.setAttribute("src", multiqc_src);
    multiqc.setAttribute("class", "embed-responsive-item");
    if (MultiQC != null) {
      MultiQC.onclick = function () {
        var multiqc_report =
          document.getElementsByClassName("multiqc_report")[0];
        multiqc_report.appendChild(multiqc);
        setTimeout(() => {
          multiqc_report.contentWindow.location.reload(true);
        }, 1000);
      };
    }
    if (krona_tab != null) {
      krona_tab.onclick = function () {
        var kraken_report =
          document.getElementsByClassName("kraken_report")[0];
        kraken_report.appendChild(krona);
      };
    }
    if (blob_tab != null) {
      blob_tab.onclick = function () {
        $("#blob_image").attr("src", blob_src);
      };
    }
    if (preseq_tab != null) {
      preseq_tab.onclick = function () {
        $("#preseq_tabs li:first").addClass("active");
        if ($("#preseq_report").has("div").length == 0) {
          read_preseq_cdata();
        }
      };
    }
    if (arg_tab != null) {
      arg_tab.onclick = function () {
        if ($("#arg_dataTable thead tr:has(th)").length == 0) {
          read_arg_data();
        }
      };
    }
    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        if ($("#bowtie_dataTable thead tr:has(th)").length == 0) {
          read_bowtie_data();
        }
      };
    }
    if (checkM_tab != null) {
      checkM_tab.onclick = function () {
        if ($("#checkM_dataTable thead tr:has(th)").length == 0) {
          read_checkM_data();
        }
      };
    }
    if (EukCC_tab != null) {
      EukCC_tab.onclick = function () {
        if ($("#EukCC_dataTable thead tr:has(th)").length == 0) {
          read_eukcc_data();
        }
      };
    }
  } else {
    var krona_src =
      "/kraken?project_uuid=" +
      $(".iframe_project_uuid").text() +
      "&sample_name=" +
      iframe_krona_tabs.first().val();
    var blob_src =
      "/blob?project_uuid=" +
      $(".iframe_project_uuid").text() +
      "&sample_name=" +
      image_blob_tabs.first().val();
    var multiqc_src =
      "/multiqc?project_uuid=" + $(".iframe_project_uuid").text();
    krona.setAttribute("src", krona_src);
    krona.setAttribute("class", "embed-responsive-item");
    multiqc.setAttribute("src", multiqc_src);
    multiqc.setAttribute("class", "embed-responsive-item");
    if (MultiQC != null) {
      MultiQC.onclick = function () {
        var multiqc_report =
          document.getElementsByClassName("multiqc_report")[0];
        multiqc_report.appendChild(multiqc);
        setTimeout(() => {
          multiqc_report.contentWindow.location.reload(true);
        }, 1000);
      };
    }
    if (krona_tab != null) {
      krona_tab.onclick = function () {
        var kraken_report =
          document.getElementsByClassName("kraken_report")[0];
        kraken_report.appendChild(krona);
      };
    }
    if (blob_tab != null) {
      blob_tab.onclick = function () {
        $("#blob_image").attr("src", blob_src);
      };
    }
    if (preseq_tab != null) {
      preseq_tab.onclick = function () {
        if ($("#preseq_report").has("div").length == 0) {
          read_preseq_cdata();
        }
      };
    }
    if (arg_tab != null) {
      arg_tab.onclick = function () {
        window.alert = function () { };
        if ($("#arg_dataTable thead tr:has(th)").length == 0) {
          read_arg_data();
        }
      };
    }
    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        window.alert = function () { };
        if ($("#bowtie_dataTable thead tr:has(th)").length == 0) {
          read_bowtie_data();
        }
      };
    }
    if (checkM_tab != null) {
      checkM_tab.onclick = function () {
        if ($("#checkM_dataTable thead tr:has(th)").length == 0) {
          read_checkM_data();
        }
      };
    }
    if (EukCC_tab != null) {
      EukCC_tab.onclick = function () {
        if ($("#EukCC_dataTable thead tr:has(th)").length == 0) {
          read_eukcc_data();
        }
      };
    }
    iframe_krona_tabs.on("change", function (e) {
      e.preventDefault();
      var krona_src =
        "/kraken?project_uuid=" +
        $(".iframe_project_uuid").text() +
        "&sample_name=" +
        $("#kraken_tabs option:selected").val();
      krona.setAttribute("src", krona_src);
      krona.setAttribute("class", "embed-responsive-item");
      $("#kraken_report").empty();
      $("#kraken_report").append(krona);
    });
    image_blob_tabs.on("change", function (e) {
      e.preventDefault();
      var blob_src =
        "/blob?project_uuid=" +
        $(".iframe_project_uuid").text() +
        "&sample_name=" +
        $("#blob_tabs option:selected").val();
      $("#blob_image").attr("src", blob_src);
    });
  }

  preseq_tabs.on("change", function (e) {
    e.preventDefault();
    $("#preseq_report").remove();
    var new_preseq_report = $("<div></div>");
    new_preseq_report.attr("id", "preseq_report");
    new_preseq_report.addClass(
      "w-100 overflow-hidden shadow p-3 bg-white rounded border"
    );
    $(".preseq_report").after(new_preseq_report);
    if ($("#preseq_tabs option:selected").val().indexOf("_c") != -1) {
      read_preseq_cdata();
    }
    read_preseq_lcgcdata();
  });

  preseq_proj_tabs.on("change", function (e) {
    e.preventDefault();
    $("#preseq_report").remove();
    var new_preseq_report = $("<div></div>");
    new_preseq_report.attr("id", "preseq_report");
    new_preseq_report.addClass(
      "w-100 overflow-hidden shadow p-3 bg-white rounded border"
    );
    $(".preseq_report").after(new_preseq_report);
    if ($("#preseq_proj_tabs option:selected").val().indexOf("_c") != -1) {
      read_preseq_cdata();
    }
    read_preseq_lcgcdata();
  });

  arg_tabs.on("change", function (e) {
    e.preventDefault();
    window.alert = function () { };
    var table = $("#arg_dataTable").DataTable({
      paging: false,
    });
    table.destroy();
    $("#arg_dataTable thead tr").empty();
    $("#arg_dataTable tbody").empty();
    read_arg_data();
  });

  blob_txt_tabs.on("change", function (e) {
    e.preventDefault();
    window.alert = function () { };
    var table = $("#blob_dataTable").DataTable({
      paging: false,
    });
    table.destroy();
    $("#blob_dataTable tbody").empty();
    read_blob_txt();
    blob_classify();
  });

  blob_classify_tabs.on("change", function (e) {
    e.preventDefault();
    blob_classify();
  });

  bowtie_tabs.on("change", function (e) {
    e.preventDefault();
    var table = $("#bowtie_dataTable").DataTable({
      paging: false,
    });
    table.destroy();
    $("#bowtie_dataTable thead tr").empty();
    $("#bowtie_dataTable tbody").empty();
    read_bowtie_data();
  });

  eukcc_tabs.on("change", function (e) {
    e.preventDefault();
    window.alert = function () { };
    var table = $("#EukCC_dataTable").DataTable({
      paging: false,
    });
    table.destroy();
    $("#EukCC_dataTable thead tr").empty();
    $("#EukCC_dataTable tbody").empty();
    read_eukcc_data();
  });

  function read_preseq_cdata() {
    if (window.location.href.indexOf("projectID") != -1) {
      var preseq = $("#preseq_proj_tabs option:selected").val();
      var projectID = getVariable("projectID");
      var data = {
        preseq: preseq,
        projectID: projectID,
      };
    } else {
      var preseq = $("#preseq_tabs li.active").children().text();
      var sampleID = getVariable("sampleID");
      var data = {
        preseq: preseq,
        sampleID: sampleID,
      };
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/successRunning/preseq",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        var preseq_report = document.getElementById("preseq_report");
        if (res.code == 200) {
          var a_axios = res.data[0];
          var y_axios = res.data[1];
          Plotly.plot(
            preseq_report,
            [
              {
                x: a_axios,
                y: y_axios,
              },
            ],
            {
              margin: { t: 0 },
              xaxis: {
                title: "total reads",
              },
              yaxis: {
                title: "distinct reads",
              },
            }
          );
        } else {
          $("#preseq_report").empty();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $("#preseq_report").html(no_result_found);
        }
      },
    });
  }

  function read_preseq_lcgcdata() {
    if (window.location.href.indexOf("projectID") != -1) {
      var preseq = $("#preseq_proj_tabs option:selected").val();
      var projectID = getVariable("projectID");
      var data = {
        preseq: preseq,
        projectID: projectID,
      };
    } else {
      var preseq = $("#preseq_tabs option:selected").val();
      console.log(111)
      var sampleID = getVariable("sampleID");
      var data = {
        preseq: preseq,
        sampleID: sampleID,
      };
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/successRunning/preseq",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        var preseq_report = document.getElementById("preseq_report");
        if (res.code == 200) {
          var upper = Math.max(...res.data[3]);
          var lower = Math.min(...res.data[2]);
          var range = [];
          range = range.push(lower, upper);
          var x_axis = [...res.data[0]];
          var x_axios = res.data[0].concat(x_axis.reverse());
          var confidence = res.data[3].concat(res.data[2].reverse());
          var trace1 = {
            fill: "tonexty",
            mode: "none",
            name: "95% Confidence Interval",
            type: "scatter",
            x: x_axios,
            y: confidence,
            fillcolor: "#d8b365",
          };
          var trace2 = {
            mode: "lines",
            name: preseq,
            type: "scatter",
            x: res.data[0],
            y: res.data[1],
            marker: { color: "black" },
          };
          var data = [trace1, trace2];
          var layout = {
            yaxis1: {
              range: range,
              title: preseq,
            },
            yaxis2: {
              side: "right",
              title: "Number of Manual Labels",
              overlaying: "y",
            },
            xaxis: {
              title: "total reads",
            },
          };
          Plotly.plot(preseq_report, {
            data: data,
            layout: layout,
          });
        } else {
          $("#preseq_report").empty();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $("#preseq_report").html(no_result_found);
        }
      },
    });
  }

  function read_arg_data() {
    if (window.location.href.indexOf("projectID") != -1) {
      var arg = $("#arg_tabs option:selected").val();
      var projectID = getVariable("projectID");
      var data = {
        arg: arg,
        projectID: projectID
      }
    } else {
      var arg = $(".iframe_sample_name").text();
      var sampleID = getVariable("sampleID");
      var data = {
        arg: arg,
        sampleID: sampleID,
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/arg",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        if (res.code == 200) {
          var arg_data = res.data;
          var data = [];
          arg_data.forEach((item) => {
            item.forEach((d, i) => {
              var a = (data[i] = data[i] || []);
              a.push(d);
            });
          });
          $("#arg_dataTable thead tr").empty();
          $("#arg_dataTable tbody").empty();
          for (var j = 0; j < data[0].length; j++) {
            var th = $("<th></th>");
            if (j == 0) {
              th.text("Item");
            }
            if (j == 1) {
              th.text("Value");
            }
            $("#arg_dataTable thead tr").append(th);
          }
          for (var i = 0; i < data.length; i++) {
            var tr = $("<tr></tr>");
            for (var j = 0; j < data[i].length; j++) {
              var td = $("<td></td>");
              tr.append(td);
            }
            $("#arg_dataTable tbody").append(tr);
          }
          var arg_table = $("#arg_dataTable").DataTable({
            deferRender: true,
            data: data,
          });
          $("#arg_dataTable tbody").on(
            "click",
            "tr",
            function () {
              if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
              } else {
                table
                  .$("tr.selected")
                  .removeClass("selected");
                $(this).addClass("selected");
              }
            }
          );
        } else {
          $("#arg_dataTable").remove();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $(".arg_table").html(no_result_found);
        }
      },
    });
  }

  function quast_blob_data() {
    if (window.location.href.indexOf("projectID") != -1) {
      var projectID = getVariable("projectID");
      var blob = $("#blob_txt_tabs option:selected").val();
      var data = {
        projectID: projectID,
        blob: blob,
        home: true,
      }
      var blobData = {
        projectID: projectID,
        blob: blob,
      }
    } else {
      var sampleID = getVariable("sampleID");
      var data = {
        sampleID: sampleID,
        home: true,
      }
      var blobData = {
        sampleID: sampleID,
        blob: blob,
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/home",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        if (res.code == 200) {
          let quast_data = res.data.quast;
          let blob_header = res.data.blob_header;
          let blob_pic = res.data.blob_pic;
          // quast
          if (quast_data != null) {
            for (let j = 0; j < quast_data[0].length; j++) {
              let th = $("<th></th>");
              th.html(quast_data[0][j]);
              $("#quast_dataTable thead tr").append(th);
            }
            for (let i = 1; i < quast_data.length; i++) {
              let tr = $("<tr></tr>");
              for (let j = 0; j < quast_data[i].length; j++) {
                let td = $("<td></td>");
                tr.append(td);
              }
              $("#quast_dataTable tbody").append(tr);
            }
            quast_data.shift();
            let quast_table = $("#quast_dataTable").DataTable({
              deferRender: true,
              data: quast_data,
            });
            $(".fading_circles_quast").remove();
            $("#quast_dataTable tbody").on(
              "click",
              "tr",
              function () {
                if ($(this).hasClass("selected")) {
                  $(this).removeClass("selected");
                } else {
                  quast_table
                    .$("tr.selected")
                    .removeClass("selected");
                  $(this).addClass("selected");
                }
              }
            );
          }

          // Blob
          if (blob_header != null) {
            let blob_header_num = blob_header[0].length;
            let blobHeader = [];
            for (let i = 0; i < blob_header_num; i++) {
              blobHeader.push(null);
            }
            for (let j = 0; j < blob_header[0].length; j++) {
              let th = $("<th></th>");
              th.html(blob_header[0][j]);
              $("#blob_dataTable thead tr").append(th);
            }
            let blobTable = $("#blob_dataTable").DataTable({
              destroy: true,
              ajax: {
                url: "/successRunning/blob",
                type: "POST",
                data: blobData,
              },
              deferRender: true,
              processing: true,
              aoColumns: blobHeader,
            });
            $("#blob_dataTable tbody").on(
              "click",
              "tr",
              function () {
                if ($(this).hasClass("selected")) {
                  $(this).removeClass("selected");
                } else {
                  blobTable
                    .$("tr.selected")
                    .removeClass("selected");
                  $(this).addClass("selected");
                }
              }
            );

            // BlobPlots
            let blob_picture =
              document.getElementById("blob_pic");
            let draw_blob_pic = $("#draw_blob_pic");
            let phylum_list = [];
            let phylum_data = [];
            for (let i = 0; i < blob_pic.length; i++) {
              if (phylum_list.indexOf(blob_pic[i][6]) == -1) {
                if (phylum_list.length < 9) {
                  phylum_list.push(blob_pic[i][6]);
                } else {
                  phylum_list.push("others");
                  break;
                }
              }
            }
            for (let j = 0; j < phylum_list.length; j++) {
              if (j < 9) {
                let blob = phylum_list[j];
                let name = phylum_list[j] + "_name";
                let length = phylum_list[j] + "_length";
                let gc = phylum_list[j] + "_gc";
                let cov = phylum_list[j] + "_cov";
                let total_length =
                  phylum_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (let v = 0; v < blob_pic.length; v++) {
                  if (blob_pic[v][6] == phylum_list[j]) {
                    window[name].push(blob_pic[v][0]);
                    window[length].push(blob_pic[v][1]);
                    window[gc].push(blob_pic[v][2]);
                    window[cov].push(blob_pic[v][4]);
                    window[total_length] += parseInt(
                      blob_pic[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: phylum_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                phylum_data.push(window[blob]);
              } else {
                let other_blob = phylum_list[j];
                let other_name = phylum_list[j] + "_name";
                let other_length =
                  phylum_list[j] + "_length";
                let other_gc = phylum_list[j] + "_gc";
                let other_cov = phylum_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (let v = 0; v < blob_pic.length; v++) {
                  if (
                    phylum_list.indexOf(
                      blob_pic[v][6]
                    ) == -1
                  ) {
                    window[other_name].push(
                      blob_pic[v][0]
                    );
                    window[other_length].push(
                      blob_pic[v][1]
                    );
                    window[other_gc].push(
                      blob_pic[v][2]
                    );
                    window[other_cov].push(
                      blob_pic[v][4]
                    );
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: phylum_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                phylum_data.push(window[other_blob]);
              }
            }
            phylum_data.sort(compare("total_length"));
            let layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            draw_blob_pic.on("click", function () {
              if (
                $("#blob_classify option:selected").val() ==
                "phylum" &&
                $("#blob_pic").is(":empty")
              ) {
                console.log(layout);
                Plotly.newPlot(
                  blob_picture,
                  phylum_data,
                  layout
                );
              }
            });
          }
        }
      },
    });
  }

  function read_blob_txt() {
    var projectID = getVariable("projectID");
    var blob = $("#blob_txt_tabs option:selected").val();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/successRunning/get_blob_header",
      type: "POST",
      data: {
        projectID: projectID,
        blob: blob,
      },
      dataType: "json",
      success: function (res) {
        if (res.data != "failed") {
          var blob_header = res.data.blob_header;
          // blob
          var blob_header_num = blob_header[0].length;
          var blobHeader = [];
          for (var i = 0; i < blob_header_num; i++) {
            blobHeader.push(null);
          }
          var blob_table = $("#blob_dataTable").DataTable({
            destroy: true,
            ajax: {
              url: "/successRunning/blob",
              type: "POST",
              data: {
                projectID: projectID,
                blob: blob,
              },
            },
            deferRender: true,
            processing: true,
            aoColumns: blobHeader,
          });
          $("#blob_dataTable tbody").on("click", "tr", function () {
            if ($(this).hasClass("selected")) {
              $(this).removeClass("selected");
            } else {
              blob_table.$("tr.selected").removeClass("selected");
              $(this).addClass("selected");
            }
          });
        }
      },
    });
  }

  function read_bowtie_data() {
    if (window.location.href.indexOf("projectID") != -1) {
      var bowtie = $("#bowtie_tabs option:selected").val();
      var projectID = getVariable("projectID");
      var data = {
        bowtie: bowtie,
        projectID: projectID,
      }
    } else {
      var bowtie = $(".iframe_sample_name").text();
      var sampleID = getVariable("sampleID");
      var data = {
        bowtie: bowtie,
        sampleID: sampleID,
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/bowtie",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        if (res.code == 200) {
          var bowtie_data = res.data;
          var data = [];
          bowtie_data.forEach((item) => {
            item.forEach((d, i) => {
              var a = (data[i] = data[i] || []);
              a.push(d);
            });
          });
          for (var j = 0; j < data[0].length; j++) {
            var th = $("<th></th>");
            if (j == 0) {
              th.text("Item");
            }
            if (j == 1) {
              th.text("Value");
            }
            $("#bowtie_dataTable thead tr").append(th);
          }
          for (var i = 0; i < data.length; i++) {
            var tr = $("<tr></tr>");
            for (var j = 0; j < data[i].length; j++) {
              var td = $("<td></td>");
              tr.append(td);
            }
            $("#bowtie_dataTable tbody").append(tr);
          }
          var bowtie_table = $("#bowtie_dataTable").DataTable({
            deferRender: true,
            data: data,
          });
          $("#bowtie_dataTable tbody").on(
            "click",
            "tr",
            function () {
              if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
              } else {
                bowtie_table
                  .$("tr.selected")
                  .removeClass("selected");
                $(this).addClass("selected");
              }
            }
          );
        } else {
          $("#bowtie_dataTable").remove();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $(".bowtie_table").html(no_result_found);
        }
      },
    });
  }

  //read checkM data
  function read_checkM_data() {
    if (window.location.href.indexOf("projectID") != -1) {
      var projectID = getVariable("projectID");
      var data = {
        checkM: true,
        projectID: projectID,
      }
    } else {
      var sampleID = getVariable("sampleID");
      var data = {
        checkM: true,
        sampleID: sampleID,
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/checkM",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        if (res.code == 200) {
          var data = res.data;
          var checkM_data = [];
          data.forEach((item) => {
            item.forEach((d, i) => {
              var a = (checkM_data[i] = checkM_data[i] || []);
              a.push(d);
            });
          });
          for (var j = 0; j < checkM_data[0].length; j++) {
            var th = $("<th></th>");
            th.html(checkM_data[0][j]);
            $("#checkM_dataTable thead tr").append(th);
          }
          for (var i = 1; i < checkM_data.length; i++) {
            var tr = $("<tr></tr>");
            for (var j = 0; j < checkM_data[i].length; j++) {
              var td = $("<td></td>");
              tr.append(td);
            }
            $("#checkM_dataTable tbody").append(tr);
          }
          checkM_data.shift();
          var checkM_table = $("#checkM_dataTable").DataTable({
            deferRender: true,
            iDisplayLength: 25,
            data: checkM_data,
          });
          $("#checkM_dataTable tbody").on(
            "click",
            "tr",
            function () {
              if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
              } else {
                checkM_table
                  .$("tr.selected")
                  .removeClass("selected");
                $(this).addClass("selected");
              }
            }
          );
        } else {
          $("#checkM_dataTable").remove();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $(".checkM_table").html(no_result_found);
        }
      },
    });
  }

  // EukCC
  function read_eukcc_data() {
    if (window.location.href.indexOf("projectID") != -1) {
      var EukCC = $("#EukCC_tabs option:selected").val();
      var projectID = getVariable("projectID");
      var data = {
        EukCC: EukCC,
        projectID: projectID,
      }
    } else {
      var sampleID = getVariable("sampleID");
      var EukCC = $(".iframe_sample_name").text();
      var data = {
        EukCC: EukCC,
        sampleID: sampleID,
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/eukcc",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        if (res.code == 200) {
          var EukCC_data = res.data.EukCC_body;
          var EukCC_header = res.data.EukCC_header;
          for (var j = 0; j < EukCC_header.length; j++) {
            var th = $("<th></th>");
            th.html(EukCC_header[j]);
            $("#EukCC_dataTable thead tr").append(th);
          }
          for (var i = 0; i < EukCC_data.length; i++) {
            var tr = $("<tr></tr>");
            for (var j = 0; j < EukCC_data[i].length; j++) {
              var td = $("<td></td>");
              tr.append(td);
            }
            $("#EukCC_dataTable tbody").append(tr);
          }
          var EukCC_table = $("#EukCC_dataTable").DataTable({
            deferRender: true,
            data: EukCC_data,
          });
          $("#EukCC_dataTable tbody").on(
            "click",
            "tr",
            function () {
              if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
              } else {
                EukCC_table.$("tr.selected").removeClass(
                  "selected"
                );
                $(this).addClass("selected");
              }
            }
          );
        } else {
          $("#checkM_dataTable").remove();
          var no_result_found = $(
            '<i class="fa fa-search" style="font-size:3rem;color:#ccc"></i><span style="font-size:3rem;color:#ccc">No results found!</span>'
          );
          $(".checkM_table").html(no_result_found);
        }
      },
    });
  }

  // blob classify
  function blob_classify() {
    if (window.location.href.indexOf("projectID") != -1) {
      var projectID = getVariable("projectID");
      var blob = $("#blob_txt_tabs option:selected").val();
      var blob_classify = $("#blob_classify_tabs option:selected").val();
      var data = {
        projectID: projectID,
        blob: blob,
        blob_classify: blob_classify,
      }
    } else {
      var sampleID = getVariable("sampleID");
      var blob = $(".iframe_sample_name").text();
      var data = {
        sampleID: sampleID,
        blob_classify: "superkingdom",
      }
    }
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      url: "/successRunning/blob_classify",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (res) {
        var blob_picture = document.getElementById("blob_pic");
        var data = res.data;
        switch ($("#blob_classify option:selected").val()) {
          case "superkingdom":
            var superkingdom_list = [];
            var superkingdom_data = [];
            for (var i = 0; i < data.length; i++) {
              if (
                superkingdom_list.indexOf(data[i][5]) == -1
              ) {
                if (superkingdom_list.length < 9) {
                  superkingdom_list.push(data[i][5]);
                } else {
                  superkingdom_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < superkingdom_list.length; j++) {
              if (j < 9) {
                var blob = superkingdom_list[j];
                var name = superkingdom_list[j] + "_name";
                var length =
                  superkingdom_list[j] + "_length";
                var gc = superkingdom_list[j] + "_gc";
                var cov = superkingdom_list[j] + "_cov";
                var total_length =
                  superkingdom_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (
                    data[v][5] == superkingdom_list[j]
                  ) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: superkingdom_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                superkingdom_data.push(window[blob]);
              } else {
                var other_blob = superkingdom_list[j];
                var other_name =
                  superkingdom_list[j] + "_name";
                var other_length =
                  superkingdom_list[j] + "_length";
                var other_gc = superkingdom_list[j] + "_gc";
                var other_cov =
                  superkingdom_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (
                    superkingdom_list.indexOf(
                      data[v][5]
                    ) == -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: superkingdom_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                superkingdom_data.push(window[other_blob]);
              }
            }
            superkingdom_data.sort(compare("total_length"));
            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(
              blob_picture,
              superkingdom_data,
              layout
            );
            break;
          case "phylum":
            var phylum_list = [];
            var phylum_data = [];
            for (var i = 0; i < data.length; i++) {
              if (phylum_list.indexOf(data[i][6]) == -1) {
                if (phylum_list.length < 9) {
                  phylum_list.push(data[i][6]);
                } else {
                  phylum_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < phylum_list.length; j++) {
              if (j < 9) {
                var blob = phylum_list[j];
                var name = phylum_list[j] + "_name";
                var length = phylum_list[j] + "_length";
                var gc = phylum_list[j] + "_gc";
                var cov = phylum_list[j] + "_cov";
                var total_length =
                  phylum_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (data[v][6] == phylum_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: phylum_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                phylum_data.push(window[blob]);
              } else {
                var other_blob = phylum_list[j];
                var other_name = phylum_list[j] + "_name";
                var other_length =
                  phylum_list[j] + "_length";
                var other_gc = phylum_list[j] + "_gc";
                var other_cov = phylum_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (
                    phylum_list.indexOf(data[v][6]) ==
                    -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: phylum_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                phylum_data.push(window[other_blob]);
              }
            }
            phylum_data.sort(compare("total_length"));
            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(blob_picture, phylum_data, layout);
            break;
          case "order":
            var order_list = [];
            var order_data = [];
            for (var i = 0; i < data.length; i++) {
              if (order_list.indexOf(data[i][7]) == -1) {
                if (order_list.length < 9) {
                  order_list.push(data[i][7]);
                } else {
                  order_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < order_list.length; j++) {
              if (j < 9) {
                var blob = order_list[j];
                var name = order_list[j] + "_name";
                var length = order_list[j] + "_length";
                var gc = order_list[j] + "_gc";
                var cov = order_list[j] + "_cov";
                var total_length =
                  order_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (data[v][7] == order_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: order_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                order_data.push(window[blob]);
              } else {
                var other_blob = order_list[j];
                var other_name = order_list[j] + "_name";
                var other_length =
                  order_list[j] + "_length";
                var other_gc = order_list[j] + "_gc";
                var other_cov = order_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (
                    order_list.indexOf(data[v][7]) == -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: order_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                order_data.push(window[other_blob]);
              }
            }
            order_data.sort(compare("total_length"));
            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(blob_picture, order_data, layout);
            break;
          case "family":
            var family_list = [];
            var family_data = [];
            for (var i = 0; i < data.length; i++) {
              if (family_list.indexOf(data[i][8]) == -1) {
                if (family_list.length < 9) {
                  family_list.push(data[i][8]);
                } else {
                  family_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < family_list.length; j++) {
              if (j < 9) {
                var blob = family_list[j];
                var name = family_list[j] + "_name";
                var length = family_list[j] + "_length";
                var gc = family_list[j] + "_gc";
                var cov = family_list[j] + "_cov";
                var total_length =
                  family_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (data[v][8] == family_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: family_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                family_data.push(window[blob]);
              } else {
                var other_blob = family_list[j];
                var other_name = family_list[j] + "_name";
                var other_length =
                  family_list[j] + "_length";
                var other_gc = family_list[j] + "_gc";
                var other_cov = family_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (
                    family_list.indexOf(data[v][8]) ==
                    -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: family_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                family_data.push(window[other_blob]);
              }
            }
            family_data.sort(compare("total_length"));
            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(blob_picture, family_data, layout);
            break;
          case "genus":
            var genus_list = [];
            var genus_data = [];
            for (var i = 0; i < data.length; i++) {
              if (genus_list.indexOf(data[i][9]) == -1) {
                if (genus_list.length < 9) {
                  genus_list.push(data[i][9]);
                } else {
                  genus_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < genus_list.length; j++) {
              if (j < 9) {
                var blob = genus_list[j];
                var name = genus_list[j] + "_name";
                var length = genus_list[j] + "_length";
                var gc = genus_list[j] + "_gc";
                var cov = genus_list[j] + "_cov";
                var total_length =
                  genus_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (data[v][9] == genus_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: genus_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                genus_data.push(window[blob]);
              } else {
                var other_blob = genus_list[j];
                var other_name = genus_list[j] + "_name";
                var other_length =
                  genus_list[j] + "_length";
                var other_gc = genus_list[j] + "_gc";
                var other_cov = genus_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (
                    genus_list.indexOf(data[v][9]) == -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: genus_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                genus_data.push(window[other_blob]);
              }
            }
            genus_data.sort(compare("total_length"));

            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(blob_picture, genus_data, layout);
            break;
          case "species":
            var species_list = [];
            var species_data = [];
            for (var i = 0; i < data.length; i++) {
              if (species_list.indexOf(data[i][10]) == -1) {
                if (species_list.length < 9) {
                  species_list.push(data[i][10]);
                } else {
                  species_list.push("others");
                  break;
                }
              }
            }
            for (var j = 0; j < species_list.length; j++) {
              if (j < 9) {
                var blob = species_list[j];
                var name = species_list[j] + "_name";
                var length = species_list[j] + "_length";
                var gc = species_list[j] + "_gc";
                var cov = species_list[j] + "_cov";
                var total_length =
                  species_list[j] + "_total_length";
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (var v = 0; v < data.length; v++) {
                  if (data[v][10] == species_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(
                      data[v][1]
                    );
                  }
                }
                window[length] = window[length].map(
                  function (i) {
                    return (
                      (Math.log(i) / Math.log(2)) * 3
                    );
                  }
                );
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: species_list[j],
                  mode: "markers",
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length],
                };
                species_data.push(window[blob]);
              } else {
                var other_blob = species_list[j];
                var other_name = species_list[j] + "_name";
                var other_length =
                  species_list[j] + "_length";
                var other_gc = species_list[j] + "_gc";
                var other_cov = species_list[j] + "_cov";
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (
                    species_list.indexOf(data[v][10]) ==
                    -1
                  ) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(
                      data[v][1]
                    );
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[
                  other_length
                ].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                });
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: species_list[j],
                  mode: "markers",
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0,
                };
                species_data.push(window[other_blob]);
              }
            }
            species_data.sort(compare("total_length"));
            var layout = {
              yaxis: {
                title: {
                  text: "Coverage",
                },
                type: "log",
                exponentformat: "E",
              },
              xaxis: {
                title: {
                  text: "GC proportion",
                },
              },
              width: 900,
              height: 900,
            };
            Plotly.newPlot(blob_picture, species_data, layout);
            break;
        }
      },
    });
  }

  function getVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (pair[0] == variable) {
        return pair[1];
      }
    }
    return false;
  }

  // compare
  function compare(propertyName) {
    return function (object1, object2) {
      var value1 = object1[propertyName];
      var value2 = object2[propertyName];
      if (value2 < value1) {
        return -1;
      } else if (value2 > value1) {
        return 1;
      } else {
        return 0;
      }
    };
  }
});
