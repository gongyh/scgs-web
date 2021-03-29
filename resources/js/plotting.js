var Plotly = require('plotly.js/dist/plotly');
var $ = require('jquery');
require('datatables.net');

$(function () {
  var iframe = document.getElementsByTagName('iframe')[0];
  var MultiQC = document.getElementById('v-pills-multiqc-tab');
  var proj_MultiQC = document.getElementById('v-pills-proj-multiqc-tab');
  var krona_tab = document.getElementById('v-pills-krona-tab');
  var blob_tab = document.getElementById('v-pills-blob-tab');
  var preseq_tab = document.getElementById('v-pills-preseq-tab');
  var arg_tab = document.getElementById('v-pills-arg-tab');
  var bowtie_tab = document.getElementById('v-pills-bowtie-tab');
  var image_blob_tabs = $('#blob_tabs');
  var iframe_krona_tabs = $('#kraken_tabs');
  var preseq_proj_tabs = $('#preseq_proj_tabs');
  var preseq_tabs = $('#preseq_tabs');
  var arg_tabs = $('#arg_tabs');
  var bowtie_tabs = $('#bowtie_tabs');
  var blob_txt_tabs = $('#blob_txt_tabs');
  var blob_pic_tabs = $('#blob_pic_tabs');
  var blob_classify_tabs = $('#blob_classify');
  var krona = document.createElement('iframe');


  if (window.location.href.indexOf('successRunning') != -1) {
    // init
    $('#quast_dataTable thead tr').empty();
    $('#quast_dataTable tbody').empty();
    $('#blob_dataTable thead tr').empty();
    $('#blob_dataTable tbody').empty();
    quast_blob_data();
  }

  if (window.location.href.indexOf('sampleID') != -1) {
    var krona_src = 'results/' + $('.iframe_sample_user').text() + '/' + $('.iframe_sample_uuid').text() + '/kraken/' + $('.iframe_sample_name').text() + '.krona.html';
    krona.setAttribute('src', krona_src);
    krona.setAttribute('class', 'embed-responsive-item');
    if (krona_tab != null) {
      krona_tab.onclick = function () {
        var kraken_report = document.getElementsByClassName('kraken_report')[0];
        kraken_report.appendChild(krona);
      }
    }

    if (arg_tab != null) {
      arg_tab.onclick = function () {
        if ($('#arg_dataTable thead tr:has(th)').length == 0) {
          read_arg_data();
        }
      }
    }

    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        if ($('#bowtie_dataTable thead tr:has(th)').length == 0) {
          read_bowtie_data();
        }
      }
    }
  } else {
    var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + iframe_krona_tabs.first().val() + '.krona.html';
    var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + image_blob_tabs.first().val() + '/' + image_blob_tabs.first().val() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
    krona.setAttribute('src', krona_src);
    krona.setAttribute('class', 'embed-responsive-item');
    if (krona_tab != null) {
      krona_tab.onclick = function () {
        var kraken_report = document.getElementsByClassName('kraken_report')[0];
        kraken_report.appendChild(krona);
      }
    }

    if (blob_tab != null) {
      blob_tab.onclick = function () {
        $('#blob_image').attr('src', blob_src);
      }
    }

    if (preseq_tab != null) {
      preseq_tab.onclick = function () {
        read_preseq_cdata();
      }
    }

    if (arg_tab != null) {
      arg_tab.onclick = function () {
        window.alert = function () {};
        if ($('#arg_dataTable thead tr:has(th)').length == 0) {
          read_arg_data();
        }
      }
    }

    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        window.alert = function () {};
        if ($('#bowtie_dataTable thead tr:has(th)').length == 0) {
          read_bowtie_data();
        }
      }
    }

    iframe_krona_tabs.on('change', function (e) {
      e.preventDefault();
      var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + $('#kraken_tabs option:selected').val() + '.krona.html';
      krona.setAttribute('src', krona_src);
      krona.setAttribute('class', 'embed-responsive-item');
      $('#kraken_report').empty();
      $('#kraken_report').append(krona);
    })

    image_blob_tabs.on('change', function (e) {
      e.preventDefault();
      var rootPath = getRootPath();
      var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + $('#blob_tabs option:selected').val() + '/' + $('#blob_tabs option:selected').val() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
      $('#blob_image').attr('src', blob_src);
    })

  }

  preseq_tabs.on('click', function (e) {
    e.preventDefault();
    $('#preseq_report').remove();
    $('#preseq_tabs li').removeClass('active');
    $(this).parent().addClass('active');
    var new_preseq_report = $('<div></div>');
    new_preseq_report.attr('id', 'preseq_report');
    new_preseq_report.addClass('w-100 overflow-hidden');
    $('.preseq_report').append(new_preseq_report);
    if ($(this).text().indexOf('_c') != -1) {
      read_preseq_cdata();
    } else {
      read_preseq_lcgcdata();
    }
  })

  preseq_proj_tabs.on('change', function (e) {
    e.preventDefault();
    $('#preseq_report').remove();
    var new_preseq_report = $('<div></div>');
    new_preseq_report.attr('id', 'preseq_report');
    new_preseq_report.addClass('w-100 overflow-hidden');
    $('.preseq_report').append(new_preseq_report);
    if ($('#preseq_proj_tabs option:selected').val().indexOf('_c') != -1) {
      read_preseq_cdata();
    } else {
      read_preseq_lcgcdata();
    }
  })

  arg_tabs.on('change', function (e) {
    e.preventDefault();
    window.alert = function () {};
    var table = $('#arg_dataTable').DataTable({
      paging: false
    });
    table.destroy();
    $('#arg_dataTable thead tr').empty();
    $('#arg_dataTable tbody').empty();
    read_arg_data();
  })

  blob_txt_tabs.on('change', function (e) {
    e.preventDefault();
    window.alert = function () {};
    var table = $('#blob_dataTable').DataTable({
      paging: false
    });
    table.destroy();
    $('#blob_dataTable thead tr').empty();
    $('#blob_dataTable tbody').empty();
    read_blob_txt();
  })

  blob_pic_tabs.on('change', function (e) {
    e.preventDefault();
    get_blob_pic();
  })

  blob_classify_tabs.on('change', function (e) {
    e.preventDefault();
    switch ($('#blob_classify option:selected').val()) {
    case 'superkingdom':
      blob_superkingdom();
      break;
    case 'phylum':
      blob_phylum();
      break;
    case 'order':
      blob_order();
      break;
    case 'family':
      blob_family();
      console.log(111);
      break;
    case 'genus':
      blob_genus();
      break;
    case 'species':
      blob_species();
      break;
    }
  })

  bowtie_tabs.on('change', function (e) {
    e.preventDefault();
    var table = $('#bowtie_dataTable').DataTable({
      paging: false
    });
    table.destroy();
    $('#bowtie_dataTable thead tr').empty();
    $('#bowtie_dataTable tbody').empty();
    read_bowtie_data();
  })

  if (MultiQC != null) {
    MultiQC.onclick = function () {
      setTimeout(() => {
        iframe.contentWindow.location.reload(true);
      }, 1000);
    }
  }

  if (proj_MultiQC != null) {
    proj_MultiQC.onclick = function () {
      setTimeout(() => {
        iframe.contentWindow.location.reload(true);
      }, 1000);
    }
  }

  function read_preseq_cdata() {
    if (window.location.href.indexOf('projectID') != -1) {
      var preseq = $('#preseq_proj_tabs option:selected').val();
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'preseq': preseq,
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var preseq_report = document.getElementById('preseq_report');
          if (res.code == 200) {
            a_axios = res.data[0];
            y_axios = res.data[1];
            Plotly.plot(preseq_report, [{
              x: a_axios,
              y: y_axios
            }], {
              margin: { t: 0 }
            })
          }
        }
      })
    } else {
      var preseq = $('#preseq_tabs li.active').children().text();
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'preseq': preseq,
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var preseq_report = document.getElementById('preseq_report');
          if (res.code == 200) {
            a_axios = res.data[0];
            y_axios = res.data[1];
            Plotly.plot(preseq_report, [{
              x: a_axios,
              y: y_axios
            }], {
              margin: { t: 0 }
            })
          }
        }
      })
    }
  }

  function read_preseq_lcgcdata() {
    if (window.location.href.indexOf('projectID') != -1) {
      var preseq = $('#preseq_proj_tabs option:selected').val();
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'preseq': preseq,
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var preseq_report = document.getElementById('preseq_report');
          if (res.code == 200) {
            var upper = Math.max(...res.data[3]);
            var lower = Math.min(...res.data[2]);
            var range = [];
            range = range.push(lower, upper);
            var x_axis = [...res.data[0]];
            var x_axios = res.data[0].concat(x_axis.reverse());
            var confidence = res.data[3].concat(res.data[2].reverse());
            trace1 = {
              fill: 'tonexty',
              mode: 'none',
              name: '95% Confidence Interval',
              type: 'scatter',
              x: x_axios,
              y: confidence,
              fillcolor: '#d8b365'
            };
            trace2 = {
              mode: 'lines',
              name: preseq,
              type: 'scatter',
              x: res.data[0],
              y: res.data[1],
              marker: { color: 'black' }
            };
            data = [trace1, trace2];
            layout = {
              yaxis1: {
                range: range,
                title: preseq
              },
              yaxis2: {
                side: 'right',
                title: 'Number of Manual Labels',
                overlaying: 'y'
              }
            };
            Plotly.plot(preseq_report, {
              data: data,
              layout: layout
            });
          }
        }
      })
    } else {
      var preseq = $('#preseq_tabs li.active').children().text();
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'preseq': preseq,
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var preseq_report = document.getElementById('preseq_report');
          if (res.code == 200) {
            var upper = Math.max(...res.data[3]);
            var lower = Math.min(...res.data[2]);
            var range = [];
            range = range.push(lower, upper);
            var x_axis = [...res.data[0]];
            var x_axios = res.data[0].concat(x_axis.reverse());
            var confidence = res.data[3].concat(res.data[2].reverse());
            trace1 = {
              fill: 'tonexty',
              mode: 'none',
              name: '95% Confidence Interval',
              type: 'scatter',
              x: x_axios,
              y: confidence,
              fillcolor: '#d8b365'
            };
            trace2 = {
              mode: 'lines',
              name: preseq,
              type: 'scatter',
              x: res.data[0],
              y: res.data[1],
              marker: { color: 'black' }
            };
            data = [trace1, trace2];
            layout = {
              yaxis1: {
                range: range,
                title: preseq
              },
              yaxis2: {
                side: 'right',
                title: 'Number of Manual Labels',
                overlaying: 'y'
              }
            };
            Plotly.plot(preseq_report, {
              data: data,
              layout: layout
            });
          }
        }
      })
    }
  }

  function read_arg_data() {
    if (window.location.href.indexOf('projectID') != -1) {
      var arg = $('#arg_tabs option:selected').val();
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'arg': arg,
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var arg_data = res.data;
            let data = []
            arg_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            });
            $('#arg_dataTable thead tr').empty();
            $('#arg_dataTable tbody').empty();
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              th.html(data[0][j]);
              $('#arg_dataTable thead tr').append(th);
            }
            for (let i = 1; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#arg_dataTable tbody').append(tr);
            }
            data.shift();
            var arg_table = $('#arg_dataTable').DataTable({
              data: data,
            });
            $('#arg_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    } else {
      var arg = $('.iframe_sample_name').text();
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'arg': arg,
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var arg_data = res.data;
            let data = []
            arg_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            })
            $('#arg_dataTable thead tr').empty();
            $('#arg_dataTable tbody').empty();
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              th.html(data[0][j]);
              $('#arg_dataTable thead tr').append(th);
            }
            for (let i = 1; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#arg_dataTable tbody').append(tr);
            }
            data.shift();
            var arg_table = $('#arg_dataTable').DataTable({
              data: data,
            });

            $('#arg_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                arg_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    }
  }

  function quast_blob_data() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_txt_tabs option:selected').val();
      var blob_pic = $('#blob_pic_tabs option:selected').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/home',
        type: 'POST',
        data: {
          projectID: projectID,
          blob: blob,
          blob_pic: blob_pic,
          home: true
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let data = res.data.quast;
            let blob_data = res.data.blob_table;
            let blob_pic = res.data.blob_pic;
            console.log(blob_pic)
            // quast
            if (data != null) {
              for (let j = 0; j < data[0].length; j++) {
                let th = $('<th></th>');
                th.html(data[0][j]);
                $('#quast_dataTable thead tr').append(th);
              }
              for (let i = 1; i < data.length; i++) {
                let tr = $('<tr></tr>');
                for (let j = 0; j < data[i].length; j++) {
                  let td = $('<td></td>');
                  tr.append(td);
                }
                $('#quast_dataTable tbody').append(tr);
              }
              data.shift();
              var quast_table = $('#quast_dataTable').DataTable({
                data: data,
              });
              $('#quast_dataTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                  $(this).removeClass('selected');
                }
                else {
                  quast_table.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
                }
              });
            }

            // blob
            if (blob_data != null) {
              for (let j = 0; j < blob_data[0].length; j++) {
                let th = $('<th></th>');
                th.html(blob_data[0][j]);
                $('#blob_dataTable thead tr').append(th);
              }
              for (let i = 1; i < blob_data.length; i++) {
                let tr = $('<tr></tr>');
                for (let j = 0; j < blob_data[i].length; j++) {
                  let td = $('<td></td>');
                  tr.append(td);
                }
                $('#blob_dataTable tbody').append(tr);
              }
              blob_data.shift();
              var blob_table = $('#blob_dataTable').DataTable({
                data: blob_data,
              });
              $('#blob_dataTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                  $(this).removeClass('selected');
                }
                else {
                  blob_table.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
                }
              });
            }

            // blob pic
            if (blob_pic != null) {
              var blob_picture = document.getElementById('blob_pic');
              var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });

              var Proteobacteria = {
                x: blob_pic.Proteobacteria.Proteobacteria_gc,
                y: blob_pic.Proteobacteria.Proteobacteria_cov,
                text: blob_pic.Proteobacteria.Proteobacteria_name,
                mode: 'markers',
                name: 'Proteobacteria',
                marker: {
                  size: Proteobacteria_length,
                }
              };

              var Chordata = {
                x: blob_pic.Chordata.Chordata_gc,
                y: blob_pic.Chordata.Chordata_cov,
                text: blob_pic.Chordata.Chordata_name,
                mode: 'markers',
                name: 'Chordata',
                marker: {
                  size: Chordata_length,
                }
              };

              var Cyanobacteria = {
                x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
                y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
                text: blob_pic.Cyanobacteria.Cyanobacteria_name,
                mode: 'markers',
                name: 'Cyanobacteria',
                marker: {
                  size: Cyanobacteria_length,
                }
              };

              var Actinobacteria = {
                x: blob_pic.Actinobacteria.Actinobacteria_gc,
                y: blob_pic.Actinobacteria.Actinobacteria_cov,
                text: blob_pic.Actinobacteria.Actinobacteria_name,
                mode: 'markers',
                name: 'Actinobacteria',
                marker: {
                  size: Actinobacteria_length,
                }
              };

              var Firmicutes = {
                x: blob_pic.Firmicutes.Firmicutes_gc,
                y: blob_pic.Firmicutes.Firmicutes_cov,
                text: blob_pic.Firmicutes.Firmicutes_name,
                mode: 'markers',
                name: 'Firmicutes',
                marker: {
                  size: Firmicutes_length,
                }
              };

              var Basidiomycota = {
                x: blob_pic.Basidiomycota.Basidiomycota_gc,
                y: blob_pic.Basidiomycota.Basidiomycota_cov,
                text: blob_pic.Basidiomycota.Basidiomycota_name,
                mode: 'markers',
                name: 'Basidiomycota',
                marker: {
                  size: Basidiomycota_length,
                }
              };

              var Nucleocytoviricota = {
                x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
                y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
                text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
                mode: 'markers',
                name: 'Nucleocytoviricota',
                marker: {
                  size: Nucleocytoviricota_length,
                }
              };

              var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
              var layout = {
                height: 1000,
                width: 1000
              }
              Plotly.newPlot(blob_picture, r_data, layout);
            }

          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      var blob = $('.iframe_sample_name').text();
      var blob_pic = $('.iframe_sample_name').text();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/home',
        type: 'POST',
        data: {
          sampleID: sampleID,
          blob: blob,
          blob_pic: blob_pic,
          home: true
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let data = res.data.quast;
            let blob_data = res.data.blob_table;
            let blob_pic = res.data.blob_pic;
            // quast
            if (data != null) {
              for (let j = 0; j < data[0].length; j++) {
                let th = $('<th></th>');
                th.html(data[0][j]);
                $('#quast_dataTable thead tr').append(th);
              }
              for (let i = 1; i < data.length; i++) {
                let tr = $('<tr></tr>');
                for (let j = 0; j < data[i].length; j++) {
                  let td = $('<td></td>');
                  tr.append(td);
                }
                $('#quast_dataTable tbody').append(tr);
              }
              data.shift();
              var quast_table = $('#quast_dataTable').DataTable({
                data: data,
              });
              $('#quast_dataTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                  $(this).removeClass('selected');
                }
                else {
                  quast_table.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
                }
              });
            }

            // blob
            if (blob_data != null) {
              for (let j = 0; j < blob_data[0].length; j++) {
                let th = $('<th></th>');
                th.html(blob_data[0][j]);
                $('#blob_dataTable thead tr').append(th);
              }
              for (let i = 1; i < blob_data.length; i++) {
                let tr = $('<tr></tr>');
                for (let j = 0; j < blob_data[i].length; j++) {
                  let td = $('<td></td>');
                  tr.append(td);
                }
                $('#blob_dataTable tbody').append(tr);
              }
              blob_data.shift();
              var blob_table = $('#blob_dataTable').DataTable({
                data: blob_data,
              });
              $('#blob_dataTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                  $(this).removeClass('selected');
                }
                else {
                  blob_table.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
                }
              });
            }

            // blob_pic
            if (blob_pic != null) {
              var blob_picture = document.getElementById('blob_pic');
              var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });
              var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
                return (Math.log(i) / Math.log(2)) * 3;
              });

              var Proteobacteria = {
                x: blob_pic.Proteobacteria.Proteobacteria_gc,
                y: blob_pic.Proteobacteria.Proteobacteria_cov,
                text: blob_pic.Proteobacteria.Proteobacteria_name,
                name: 'Proteobacteria',
                mode: 'markers',
                marker: {
                  size: Proteobacteria_length,
                }
              };

              var Chordata = {
                x: blob_pic.Chordata.Chordata_gc,
                y: blob_pic.Chordata.Chordata_cov,
                text: blob_pic.Chordata.Chordata_name,
                mode: 'markers',
                name: 'Chordata',
                marker: {
                  size: Chordata_length,
                }
              };

              var Cyanobacteria = {
                x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
                y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
                text: blob_pic.Cyanobacteria.Cyanobacteria_name,
                mode: 'markers',
                name: 'Cyanobacteria',
                marker: {
                  size: Cyanobacteria_length,
                }
              };

              var Actinobacteria = {
                x: blob_pic.Actinobacteria.Actinobacteria_gc,
                y: blob_pic.Actinobacteria.Actinobacteria_cov,
                text: blob_pic.Actinobacteria.Actinobacteria_name,
                mode: 'markers',
                name: 'Actinobacteria',
                marker: {
                  size: Actinobacteria_length,
                }
              };

              var Firmicutes = {
                x: blob_pic.Firmicutes.Firmicutes_gc,
                y: blob_pic.Firmicutes.Firmicutes_cov,
                text: blob_pic.Firmicutes.Firmicutes_name,
                mode: 'markers',
                name: 'Firmicutes',
                marker: {
                  size: Firmicutes_length,
                }
              };

              var Basidiomycota = {
                x: blob_pic.Basidiomycota.Basidiomycota_gc,
                y: blob_pic.Basidiomycota.Basidiomycota_cov,
                text: blob_pic.Basidiomycota.Basidiomycota_name,
                mode: 'markers',
                name: 'Basidiomycota',
                marker: {
                  size: Basidiomycota_length,
                }
              };

              var Nucleocytoviricota = {
                x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
                y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
                text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
                mode: 'markers',
                name: 'Nucleocytoviricota',
                marker: {
                  size: Nucleocytoviricota_length,
                }
              };

              var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
              var layout = {
                height: 1000,
                width: 1000
              }
              Plotly.newPlot(blob_picture, r_data, layout);
            }
          }
        }
      })
    }
  }

  function read_blob_txt() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_txt_tabs option:selected').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob',
        type: 'POST',
        data: {
          projectID: projectID,
          blob: blob
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let blob_data = res.data.blob_table;
            $('#blob_dataTable thead tr').empty();
            $('#blob_dataTable tbody').empty();
            // blob
            for (let j = 0; j < blob_data[0].length; j++) {
              let th = $('<th></th>');
              th.html(blob_data[0][j]);
              $('#blob_dataTable thead tr').append(th);
            }
            for (let i = 1; i < blob_data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < blob_data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#blob_dataTable tbody').append(tr);
            }
            blob_data.shift();
            var blob_table = $('#blob_dataTable').DataTable({
              data: blob_data,
            });
            $('#blob_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                blob_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob',
        type: 'POST',
        data: {
          sampleID: sampleID,
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let data = res.data.quast;
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              th.html(data[0][j]);
              $('#quast_dataTable thead tr').append(th);
            }
            for (let i = 1; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#quast_dataTable tbody').append(tr);
            }
            data.shift();
            var quast_table = $('#quast_dataTable').DataTable({
              data: data,
            });
            $('#quast_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                quast_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    }
  }

  function read_bowtie_data() {
    if (window.location.href.indexOf('projectID') != -1) {
      var bowtie = $('#bowtie_tabs option:selected').val();
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          bowtie: bowtie,
          projectID: projectID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let bowtie_data = res.data;
            let data = []
            bowtie_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            });
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              th.html(data[0][j]);
              $('#bowtie_dataTable thead tr').append(th);
            }
            for (let i = 1; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#bowtie_dataTable tbody').append(tr);
            }
            data.shift();
            var bowtie_table = $('#bowtie_dataTable').DataTable({
              data: data,
            });
            $('#bowtie_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                bowtie_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    } else {
      var bowtie = $('.iframe_sample_name').text();
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          bowtie: bowtie,
          sampleID: sampleID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let bowtie_data = res.data;
            let data = []
            bowtie_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            });
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              th.html(data[0][j]);
              $('#bowtie_dataTable thead tr').append(th);
            }
            for (let i = 1; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#bowtie_dataTable tbody').append(tr);
            }
            data.shift();
            var bowtie_table = $('#bowtie_dataTable').DataTable({
              data: data,
            });
            $('#bowtie_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                bowtie_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    }
  }

  function get_blob_pic() {
    if (window.location.href.indexOf('projectID') != -1) {
      var blob_pic_val = $('#blob_pic_tabs option:selected').val();
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_pic',
        type: 'POST',
        data: {
          'blob_pic': blob_pic_val,
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data.blob_picture;
            var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Proteobacteria = {
              x: blob_pic.Proteobacteria.Proteobacteria_gc,
              y: blob_pic.Proteobacteria.Proteobacteria_cov,
              text: blob_pic.Proteobacteria.Proteobacteria_name,
              name: 'Proteobacteria',
              mode: 'markers',
              marker: {
                size: Proteobacteria_length,
              }
            };

            var Chordata = {
              x: blob_pic.Chordata.Chordata_gc,
              y: blob_pic.Chordata.Chordata_cov,
              text: blob_pic.Chordata.Chordata_name,
              name: 'Chordata',
              mode: 'markers',
              marker: {
                size: Chordata_length,
              }
            };

            var Cyanobacteria = {
              x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
              y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
              text: blob_pic.Cyanobacteria.Cyanobacteria_name,
              name: 'Cyanobacteria',
              mode: 'markers',
              marker: {
                size: Cyanobacteria_length,
              }
            };

            var Actinobacteria = {
              x: blob_pic.Actinobacteria.Actinobacteria_gc,
              y: blob_pic.Actinobacteria.Actinobacteria_cov,
              text: blob_pic.Actinobacteria.Actinobacteria_name,
              name: 'Actinobacteria',
              mode: 'markers',
              marker: {
                size: Actinobacteria_length,
              }
            };

            var Firmicutes = {
              x: blob_pic.Firmicutes.Firmicutes_gc,
              y: blob_pic.Firmicutes.Firmicutes_cov,
              text: blob_pic.Firmicutes.Firmicutes_name,
              name: 'Firmicutes',
              mode: 'markers',
              marker: {
                size: Firmicutes_length,
              }
            };

            var Basidiomycota = {
              x: blob_pic.Basidiomycota.Basidiomycota_gc,
              y: blob_pic.Basidiomycota.Basidiomycota_cov,
              text: blob_pic.Basidiomycota.Basidiomycota_name,
              name: 'Basidiomycota',
              mode: 'markers',
              marker: {
                size: Basidiomycota_length,
              }
            };

            var Nucleocytoviricota = {
              x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
              y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
              text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
              name: 'Nucleocytoviricota',
              mode: 'markers',
              marker: {
                size: Nucleocytoviricota_length,
              }
            };

            var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data.blob_picture;
            console.log(blob_pic)
            var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Proteobacteria = {
              x: blob_pic.Proteobacteria.Proteobacteria_gc,
              y: blob_pic.Proteobacteria.Proteobacteria_cov,
              text: blob_pic.Proteobacteria.Proteobacteria_name,
              name: 'Proteobacteria',
              mode: 'markers',
              marker: {
                size: Proteobacteria_length,
              }
            };

            var Chordata = {
              x: blob_pic.Chordata.Chordata_gc,
              y: blob_pic.Chordata.Chordata_cov,
              text: blob_pic.Chordata.Chordata_name,
              name: 'Chordata',
              mode: 'markers',
              marker: {
                size: Chordata_length,
              }
            };

            var Cyanobacteria = {
              x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
              y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
              text: blob_pic.Cyanobacteria.Cyanobacteria_name,
              name: 'Cyanobacteria',
              mode: 'markers',
              marker: {
                size: Cyanobacteria_length,
              }
            };

            var Actinobacteria = {
              x: blob_pic.Actinobacteria.Actinobacteria_gc,
              y: blob_pic.Actinobacteria.Actinobacteria_cov,
              text: blob_pic.Actinobacteria.Actinobacteria_name,
              name: 'Actinobacteria',
              mode: 'markers',
              marker: {
                size: Actinobacteria_length,
              }
            };

            var Firmicutes = {
              x: blob_pic.Firmicutes.Firmicutes_gc,
              y: blob_pic.Firmicutes.Firmicutes_cov,
              text: blob_pic.Firmicutes.Firmicutes_name,
              name: 'Firmicutes',
              mode: 'markers',
              marker: {
                size: Firmicutes_length,
              }
            };

            var Basidiomycota = {
              x: blob_pic.Basidiomycota.Basidiomycota_gc,
              y: blob_pic.Basidiomycota.Basidiomycota_cov,
              text: blob_pic.Basidiomycota.Basidiomycota_name,
              name: 'Basidiomycota',
              mode: 'markers',
              marker: {
                size: Basidiomycota_length,
              }
            };

            var Nucleocytoviricota = {
              x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
              y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
              text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
              name: 'Nucleocytoviricota',
              mode: 'markers',
              marker: {
                size: Nucleocytoviricota_length,
              }
            };

            var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // blob superkingdom
  function blob_superkingdom() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'superkingdom',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Bacteria_length = blob_pic.Bacteria.Bacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Eukaryota_length = blob_pic.Eukaryota.Eukaryota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Viruses_length = blob_pic.Viruses.Viruses_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Bacteria = {
              x: blob_pic.Bacteria.Bacteria_gc,
              y: blob_pic.Bacteria.Bacteria_cov,
              text: blob_pic.Bacteria.Bacteria_name,
              name: 'Bacteria',
              mode: 'markers',
              marker: {
                size: Bacteria_length,
              }
            };

            var Eukaryota = {
              x: blob_pic.Eukaryota.Eukaryota_gc,
              y: blob_pic.Eukaryota.Eukaryota_cov,
              text: blob_pic.Eukaryota.Eukaryota_name,
              name: 'Eukaryota',
              mode: 'markers',
              marker: {
                size: Eukaryota_length,
              }
            };

            var Viruses = {
              x: blob_pic.Viruses.Viruses_gc,
              y: blob_pic.Viruses.Viruses_cov,
              text: blob_pic.Viruses.Viruses_name,
              name: 'Viruses',
              mode: 'markers',
              marker: {
                size: Viruses_length,
              }
            };

            var r_data = [Bacteria, Eukaryota, Viruses];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob_classify': 'superkingdom',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Bacteria_length = blob_pic.Bacteria.Bacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Eukaryota_length = blob_pic.Eukaryota.Eukaryota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Viruses_length = blob_pic.Viruses.Viruses_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Bacteria = {
              x: blob_pic.Bacteria.Bacteria_gc,
              y: blob_pic.Bacteria.Bacteria_cov,
              text: blob_pic.Bacteria.Bacteria_name,
              name: 'Bacteria',
              mode: 'markers',
              marker: {
                size: Bacteria_length,
              }
            };

            var Eukaryota = {
              x: blob_pic.Eukaryota.Eukaryota_gc,
              y: blob_pic.Eukaryota.Eukaryota_cov,
              text: blob_pic.Eukaryota.Eukaryota_name,
              name: 'Eukaryota',
              mode: 'markers',
              marker: {
                size: Eukaryota_length,
              }
            };

            var Viruses = {
              x: blob_pic.Viruses.Viruses_gc,
              y: blob_pic.Viruses.Viruses_cov,
              text: blob_pic.Viruses.Viruses_name,
              name: 'Viruses',
              mode: 'markers',
              marker: {
                size: Viruses_length,
              }
            };

            var r_data = [Bacteria, Eukaryota, Viruses];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // phylum
  function blob_phylum() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'phylum',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data.blob_picture;
            console.log(blob_pic)
            var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Proteobacteria = {
              x: blob_pic.Proteobacteria.Proteobacteria_gc,
              y: blob_pic.Proteobacteria.Proteobacteria_cov,
              text: blob_pic.Proteobacteria.Proteobacteria_name,
              name: 'Proteobacteria',
              mode: 'markers',
              marker: {
                size: Proteobacteria_length,
              }
            };

            var Chordata = {
              x: blob_pic.Chordata.Chordata_gc,
              y: blob_pic.Chordata.Chordata_cov,
              text: blob_pic.Chordata.Chordata_name,
              name: 'Chordata',
              mode: 'markers',
              marker: {
                size: Chordata_length,
              }
            };

            var Cyanobacteria = {
              x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
              y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
              text: blob_pic.Cyanobacteria.Cyanobacteria_name,
              name: 'Cyanobacteria',
              mode: 'markers',
              marker: {
                size: Cyanobacteria_length,
              }
            };

            var Actinobacteria = {
              x: blob_pic.Actinobacteria.Actinobacteria_gc,
              y: blob_pic.Actinobacteria.Actinobacteria_cov,
              text: blob_pic.Actinobacteria.Actinobacteria_name,
              name: 'Actinobacteria',
              mode: 'markers',
              marker: {
                size: Actinobacteria_length,
              }
            };

            var Firmicutes = {
              x: blob_pic.Firmicutes.Firmicutes_gc,
              y: blob_pic.Firmicutes.Firmicutes_cov,
              text: blob_pic.Firmicutes.Firmicutes_name,
              name: 'Firmicutes',
              mode: 'markers',
              marker: {
                size: Firmicutes_length,
              }
            };

            var Basidiomycota = {
              x: blob_pic.Basidiomycota.Basidiomycota_gc,
              y: blob_pic.Basidiomycota.Basidiomycota_cov,
              text: blob_pic.Basidiomycota.Basidiomycota_name,
              name: 'Basidiomycota',
              mode: 'markers',
              marker: {
                size: Basidiomycota_length,
              }
            };

            var Nucleocytoviricota = {
              x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
              y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
              text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
              name: 'Nucleocytoviricota',
              mode: 'markers',
              marker: {
                size: Nucleocytoviricota_length,
              }
            };

            var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob_classify': 'phylum',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data.blob_picture;
            console.log(blob_pic)
            var Proteobacteria_length = blob_pic.Proteobacteria.Proteobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Chordata_length = blob_pic.Chordata.Chordata_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cyanobacteria_length = blob_pic.Cyanobacteria.Cyanobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Actinobacteria_length = blob_pic.Actinobacteria.Actinobacteria_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Firmicutes_length = blob_pic.Firmicutes.Firmicutes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Basidiomycota_length = blob_pic.Basidiomycota.Basidiomycota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Nucleocytoviricota_length = blob_pic.Nucleocytoviricota.Nucleocytoviricota_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Proteobacteria = {
              x: blob_pic.Proteobacteria.Proteobacteria_gc,
              y: blob_pic.Proteobacteria.Proteobacteria_cov,
              text: blob_pic.Proteobacteria.Proteobacteria_name,
              name: 'Proteobacteria',
              mode: 'markers',
              marker: {
                size: Proteobacteria_length,
              }
            };

            var Chordata = {
              x: blob_pic.Chordata.Chordata_gc,
              y: blob_pic.Chordata.Chordata_cov,
              text: blob_pic.Chordata.Chordata_name,
              name: 'Chordata',
              mode: 'markers',
              marker: {
                size: Chordata_length,
              }
            };

            var Cyanobacteria = {
              x: blob_pic.Cyanobacteria.Cyanobacteria_gc,
              y: blob_pic.Cyanobacteria.Cyanobacteria_cov,
              text: blob_pic.Cyanobacteria.Cyanobacteria_name,
              name: 'Cyanobacteria',
              mode: 'markers',
              marker: {
                size: Cyanobacteria_length,
              }
            };

            var Actinobacteria = {
              x: blob_pic.Actinobacteria.Actinobacteria_gc,
              y: blob_pic.Actinobacteria.Actinobacteria_cov,
              text: blob_pic.Actinobacteria.Actinobacteria_name,
              name: 'Actinobacteria',
              mode: 'markers',
              marker: {
                size: Actinobacteria_length,
              }
            };

            var Firmicutes = {
              x: blob_pic.Firmicutes.Firmicutes_gc,
              y: blob_pic.Firmicutes.Firmicutes_cov,
              text: blob_pic.Firmicutes.Firmicutes_name,
              name: 'Firmicutes',
              mode: 'markers',
              marker: {
                size: Firmicutes_length,
              }
            };

            var Basidiomycota = {
              x: blob_pic.Basidiomycota.Basidiomycota_gc,
              y: blob_pic.Basidiomycota.Basidiomycota_cov,
              text: blob_pic.Basidiomycota.Basidiomycota_name,
              name: 'Basidiomycota',
              mode: 'markers',
              marker: {
                size: Basidiomycota_length,
              }
            };

            var Nucleocytoviricota = {
              x: blob_pic.Nucleocytoviricota.Nucleocytoviricota_gc,
              y: blob_pic.Nucleocytoviricota.Nucleocytoviricota_cov,
              text: blob_pic.Nucleocytoviricota.Nucleocytoviricota_name,
              name: 'Nucleocytoviricota',
              mode: 'markers',
              marker: {
                size: Nucleocytoviricota_length,
              }
            };

            var r_data = [Proteobacteria, Chordata, Cyanobacteria, Actinobacteria, Firmicutes, Basidiomycota, Nucleocytoviricota];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // order
  function blob_order() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'order',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Enterobacterales_length = blob_pic.Enterobacterales.Enterobacterales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Primates_length = blob_pic.Primates.Primates_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcales_length = blob_pic.Synechococcales.Synechococcales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Propionibacteriales_length = blob_pic.Propionibacteriales.Propionibacteriales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Bacillales_length = blob_pic.Bacillales.Bacillales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malasseziales_length = blob_pic.Malasseziales.Malasseziales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Campylobacterales_length = blob_pic.Campylobacterales.Campylobacterales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Imitervirales_length = blob_pic.Imitervirales.Imitervirales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Enterobacterales = {
              x: blob_pic.Enterobacterales.Enterobacterales_gc,
              y: blob_pic.Enterobacterales.Enterobacterales_cov,
              text: blob_pic.Enterobacterales.Enterobacterales_name,
              name: 'Enterobacterales',
              mode: 'markers',
              marker: {
                size: Enterobacterales_length,
              }
            };

            var Primates = {
              x: blob_pic.Primates.Primates_gc,
              y: blob_pic.Primates.Primates_cov,
              text: blob_pic.Primates.Primates_name,
              name: 'Primates',
              mode: 'markers',
              marker: {
                size: Primates_length,
              }
            };

            var Synechococcales = {
              x: blob_pic.Synechococcales.Synechococcales_gc,
              y: blob_pic.Synechococcales.Synechococcales_cov,
              text: blob_pic.Synechococcales.Synechococcales_name,
              name: 'Synechococcales',
              mode: 'markers',
              marker: {
                size: Synechococcales_length,
              }
            };

            var Propionibacteriales = {
              x: blob_pic.Propionibacteriales.Propionibacteriales_gc,
              y: blob_pic.Propionibacteriales.Propionibacteriales_cov,
              text: blob_pic.Propionibacteriales.Propionibacteriales_name,
              name: 'Propionibacteriales',
              mode: 'markers',
              marker: {
                size: Propionibacteriales_length,
              }
            };

            var Bacillales = {
              x: blob_pic.Bacillales.Bacillales_gc,
              y: blob_pic.Bacillales.Bacillales_cov,
              text: blob_pic.Bacillales.Bacillales_name,
              name: 'Bacillales',
              mode: 'markers',
              marker: {
                size: Bacillales_length,
              }
            };

            var Malasseziales = {
              x: blob_pic.Malasseziales.Malasseziales_gc,
              y: blob_pic.Malasseziales.Malasseziales_cov,
              text: blob_pic.Malasseziales.Malasseziales_name,
              name: 'Malasseziales',
              mode: 'markers',
              marker: {
                size: Malasseziales_length,
              }
            };

            var Campylobacterales = {
              x: blob_pic.Campylobacterales.Campylobacterales_gc,
              y: blob_pic.Campylobacterales.Campylobacterales_cov,
              text: blob_pic.Campylobacterales.Campylobacterales_name,
              name: 'Campylobacterales',
              mode: 'markers',
              marker: {
                size: Campylobacterales_length,
              }
            };

            var Imitervirales = {
              x: blob_pic.Imitervirales.Imitervirales_gc,
              y: blob_pic.Imitervirales.Imitervirales_cov,
              text: blob_pic.Imitervirales.Imitervirales_name,
              name: 'Imitervirales',
              mode: 'markers',
              marker: {
                size: Imitervirales_length,
              }
            };

            var r_data = [Enterobacterales, Primates, Synechococcales, Propionibacteriales, Bacillales, Malasseziales, Campylobacterales, Imitervirales];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'blob_classify': 'order',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data.blob_picture;
            console.log(blob_pic)
            var Enterobacterales_length = blob_pic.Enterobacterales.Enterobacterales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Primates_length = blob_pic.Primates.Primates_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcales_length = blob_pic.Synechococcales.Synechococcales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Propionibacteriales_length = blob_pic.Propionibacteriales.Propionibacteriales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Bacillales_length = blob_pic.Bacillales.Bacillales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malasseziales_length = blob_pic.Malasseziales.Malasseziales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Campylobacterales_length = blob_pic.Campylobacterales.Campylobacterales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Imitervirales_length = blob_pic.Imitervirales.Imitervirales_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Enterobacterales = {
              x: blob_pic.Enterobacterales.Enterobacterales_gc,
              y: blob_pic.Enterobacterales.Enterobacterales_cov,
              text: blob_pic.Enterobacterales.Enterobacterales_name,
              name: 'Enterobacterales',
              mode: 'markers',
              marker: {
                size: Enterobacterales_length,
              }
            };

            var Primates = {
              x: blob_pic.Primates.Primates_gc,
              y: blob_pic.Primates.Primates_cov,
              text: blob_pic.Primates.Primates_name,
              name: 'Primates',
              mode: 'markers',
              marker: {
                size: Primates_length,
              }
            };

            var Synechococcales = {
              x: blob_pic.Synechococcales.Synechococcales_gc,
              y: blob_pic.Synechococcales.Synechococcales_cov,
              text: blob_pic.Synechococcales.Synechococcales_name,
              name: 'Synechococcales',
              mode: 'markers',
              marker: {
                size: Synechococcales_length,
              }
            };

            var Propionibacteriales = {
              x: blob_pic.Propionibacteriales.Propionibacteriales_gc,
              y: blob_pic.Propionibacteriales.Propionibacteriales_cov,
              text: blob_pic.Propionibacteriales.Propionibacteriales_name,
              name: 'Propionibacteriales',
              mode: 'markers',
              marker: {
                size: Propionibacteriales_length,
              }
            };

            var Bacillales = {
              x: blob_pic.Bacillales.Bacillales_gc,
              y: blob_pic.Bacillales.Bacillales_cov,
              text: blob_pic.Bacillales.Bacillales_name,
              name: 'Bacillales',
              mode: 'markers',
              marker: {
                size: Bacillales_length,
              }
            };

            var Malasseziales = {
              x: blob_pic.Malasseziales.Malasseziales_gc,
              y: blob_pic.Malasseziales.Malasseziales_cov,
              text: blob_pic.Malasseziales.Malasseziales_name,
              name: 'Malasseziales',
              mode: 'markers',
              marker: {
                size: Malasseziales_length,
              }
            };

            var Campylobacterales = {
              x: blob_pic.Campylobacterales.Campylobacterales_gc,
              y: blob_pic.Campylobacterales.Campylobacterales_cov,
              text: blob_pic.Campylobacterales.Campylobacterales_name,
              name: 'Campylobacterales',
              mode: 'markers',
              marker: {
                size: Campylobacterales_length,
              }
            };

            var Imitervirales = {
              x: blob_pic.Imitervirales.Imitervirales_gc,
              y: blob_pic.Imitervirales.Imitervirales_cov,
              text: blob_pic.Imitervirales.Imitervirales_name,
              name: 'Imitervirales',
              mode: 'markers',
              marker: {
                size: Imitervirales_length,
              }
            };

            var r_data = [Enterobacterales, Primates, Synechococcales, Propionibacteriales, Bacillales, Malasseziales, Campylobacterales, Imitervirales];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // family
  function blob_family() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'family',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Enterobacteriaceae_length = blob_pic.Enterobacteriaceae.Enterobacteriaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Hominidae_length = blob_pic.Hominidae.Hominidae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Staphylococcaceae_length = blob_pic.Staphylococcaceae.Staphylococcaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Moraxellaceae_length = blob_pic.Moraxellaceae.Moraxellaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Propionibacteriaceae_length = blob_pic.Propionibacteriaceae.Propionibacteriaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malasseziaceae_length = blob_pic.Malasseziaceae.Malasseziaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcaceae_length = blob_pic.Synechococcaceae.Synechococcaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Mimiviridae_length = blob_pic.Mimiviridae.Mimiviridae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacteraceae_length = blob_pic.Helicobacteraceae.Helicobacteraceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Rhizobiaceae_length = blob_pic.Rhizobiaceae.Rhizobiaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cryptosporidiidae_length = blob_pic.Cryptosporidiidae.Cryptosporidiidae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Enterobacteriaceae = {
              x: blob_pic.Enterobacteriaceae.Enterobacteriaceae_gc,
              y: blob_pic.Enterobacteriaceae.Enterobacteriaceae_cov,
              text: blob_pic.Enterobacteriaceae.Enterobacteriaceae_name,
              name: 'Enterobacteriaceae',
              mode: 'markers',
              marker: {
                size: Enterobacteriaceae_length,
              }
            };

            var Hominidae = {
              x: blob_pic.Hominidae.Hominidae_gc,
              y: blob_pic.Hominidae.Hominidae_cov,
              text: blob_pic.Hominidae.Hominidae_name,
              name: 'Hominidae',
              mode: 'markers',
              marker: {
                size: Hominidae_length,
              }
            };

            var Staphylococcaceae = {
              x: blob_pic.Staphylococcaceae.Staphylococcaceae_gc,
              y: blob_pic.Staphylococcaceae.Staphylococcaceae_cov,
              text: blob_pic.Staphylococcaceae.Staphylococcaceae_name,
              name: 'Staphylococcaceae',
              mode: 'markers',
              marker: {
                size: Staphylococcaceae_length,
              }
            };

            var Moraxellaceae = {
              x: blob_pic.Moraxellaceae.Moraxellaceae_gc,
              y: blob_pic.Moraxellaceae.Moraxellaceae_cov,
              text: blob_pic.Moraxellaceae.Moraxellaceae_name,
              name: 'Moraxellaceae',
              mode: 'markers',
              marker: {
                size: Moraxellaceae_length,
              }
            };

            var Propionibacteriaceae = {
              x: blob_pic.Propionibacteriaceae.Propionibacteriaceae_gc,
              y: blob_pic.Propionibacteriaceae.Propionibacteriaceae_cov,
              text: blob_pic.Propionibacteriaceae.Propionibacteriaceae_name,
              name: 'Propionibacteriaceae',
              mode: 'markers',
              marker: {
                size: Propionibacteriaceae_length,
              }
            };

            var Malasseziaceae = {
              x: blob_pic.Malasseziaceae.Malasseziaceae_gc,
              y: blob_pic.Malasseziaceae.Malasseziaceae_cov,
              text: blob_pic.Malasseziaceae.Malasseziaceae_name,
              name: 'Malasseziaceae',
              mode: 'markers',
              marker: {
                size: Malasseziaceae_length,
              }
            };

            var Synechococcaceae = {
              x: blob_pic.Synechococcaceae.Synechococcaceae_gc,
              y: blob_pic.Synechococcaceae.Synechococcaceae_cov,
              text: blob_pic.Synechococcaceae.Synechococcaceae_name,
              name: 'Synechococcaceae',
              mode: 'markers',
              marker: {
                size: Synechococcaceae_length,
              }
            };

            var Mimiviridae = {
              x: blob_pic.Mimiviridae.Mimiviridae_gc,
              y: blob_pic.Mimiviridae.Mimiviridae_cov,
              text: blob_pic.Mimiviridae.Mimiviridae_name,
              name: 'Mimiviridae',
              mode: 'markers',
              marker: {
                size: Mimiviridae_length,
              }
            };

            var Helicobacteraceae = {
              x: blob_pic.Helicobacteraceae.Helicobacteraceae_gc,
              y: blob_pic.Helicobacteraceae.Helicobacteraceae_cov,
              text: blob_pic.Helicobacteraceae.Helicobacteraceae_name,
              name: 'Helicobacteraceae',
              mode: 'markers',
              marker: {
                size: Helicobacteraceae_length,
              }
            };

            var Rhizobiaceae = {
              x: blob_pic.Rhizobiaceae.Rhizobiaceae_gc,
              y: blob_pic.Rhizobiaceae.Rhizobiaceae_cov,
              text: blob_pic.Rhizobiaceae.Rhizobiaceae_name,
              name: 'Rhizobiaceae',
              mode: 'markers',
              marker: {
                size: Rhizobiaceae_length,
              }
            };

            var Cryptosporidiidae = {
              x: blob_pic.Cryptosporidiidae.Cryptosporidiidae_gc,
              y: blob_pic.Cryptosporidiidae.Cryptosporidiidae_cov,
              text: blob_pic.Cryptosporidiidae.Cryptosporidiidae_name,
              name: 'Cryptosporidiidae',
              mode: 'markers',
              marker: {
                size: Cryptosporidiidae_length,
              }
            };

            var r_data = [Enterobacteriaceae, Hominidae, Staphylococcaceae, Moraxellaceae, Propionibacteriaceae, Malasseziaceae, Synechococcaceae, Mimiviridae, Helicobacteraceae, Rhizobiaceae, Cryptosporidiidae];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'blob_classify': 'order',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Enterobacteriaceae_length = blob_pic.Enterobacteriaceae.Enterobacteriaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Hominidae_length = blob_pic.Hominidae.Hominidae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Staphylococcaceae_length = blob_pic.Staphylococcaceae.Staphylococcaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Moraxellaceae_length = blob_pic.Moraxellaceae.Moraxellaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Propionibacteriaceae_length = blob_pic.Propionibacteriaceae.Propionibacteriaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malasseziaceae_length = blob_pic.Malasseziaceae.Malasseziaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcaceae_length = blob_pic.Synechococcaceae.Synechococcaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Mimiviridae_length = blob_pic.Mimiviridae.Mimiviridae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacteraceae_length = blob_pic.Helicobacteraceae.Helicobacteraceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Rhizobiaceae_length = blob_pic.Rhizobiaceae.Rhizobiaceae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cryptosporidiidae_length = blob_pic.Cryptosporidiidae.Cryptosporidiidae_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Enterobacteriaceae = {
              x: blob_pic.Enterobacteriaceae.Enterobacteriaceae_gc,
              y: blob_pic.Enterobacteriaceae.Enterobacteriaceae_cov,
              text: blob_pic.Enterobacteriaceae.Enterobacteriaceae_name,
              name: 'Enterobacteriaceae',
              mode: 'markers',
              marker: {
                size: Enterobacteriaceae_length,
              }
            };

            var Hominidae = {
              x: blob_pic.Hominidae.Hominidae_gc,
              y: blob_pic.Hominidae.Hominidae_cov,
              text: blob_pic.Hominidae.Hominidae_name,
              name: 'Hominidae',
              mode: 'markers',
              marker: {
                size: Hominidae_length,
              }
            };

            var Staphylococcaceae = {
              x: blob_pic.Staphylococcaceae.Staphylococcaceae_gc,
              y: blob_pic.Staphylococcaceae.Staphylococcaceae_cov,
              text: blob_pic.Staphylococcaceae.Staphylococcaceae_name,
              name: 'Staphylococcaceae',
              mode: 'markers',
              marker: {
                size: Staphylococcaceae_length,
              }
            };

            var Moraxellaceae = {
              x: blob_pic.Moraxellaceae.Moraxellaceae_gc,
              y: blob_pic.Moraxellaceae.Moraxellaceae_cov,
              text: blob_pic.Moraxellaceae.Moraxellaceae_name,
              name: 'Moraxellaceae',
              mode: 'markers',
              marker: {
                size: Moraxellaceae_length,
              }
            };

            var Propionibacteriaceae = {
              x: blob_pic.Propionibacteriaceae.Propionibacteriaceae_gc,
              y: blob_pic.Propionibacteriaceae.Propionibacteriaceae_cov,
              text: blob_pic.Propionibacteriaceae.Propionibacteriaceae_name,
              name: 'Propionibacteriaceae',
              mode: 'markers',
              marker: {
                size: Propionibacteriaceae_length,
              }
            };

            var Malasseziaceae = {
              x: blob_pic.Malasseziaceae.Malasseziaceae_gc,
              y: blob_pic.Malasseziaceae.Malasseziaceae_cov,
              text: blob_pic.Malasseziaceae.Malasseziaceae_name,
              name: 'Malasseziaceae',
              mode: 'markers',
              marker: {
                size: Malasseziaceae_length,
              }
            };

            var Synechococcaceae = {
              x: blob_pic.Synechococcaceae.Synechococcaceae_gc,
              y: blob_pic.Synechococcaceae.Synechococcaceae_cov,
              text: blob_pic.Synechococcaceae.Synechococcaceae_name,
              name: 'Synechococcaceae',
              mode: 'markers',
              marker: {
                size: Synechococcaceae_length,
              }
            };

            var Mimiviridae = {
              x: blob_pic.Mimiviridae.Mimiviridae_gc,
              y: blob_pic.Mimiviridae.Mimiviridae_cov,
              text: blob_pic.Mimiviridae.Mimiviridae_name,
              name: 'Mimiviridae',
              mode: 'markers',
              marker: {
                size: Mimiviridae_length,
              }
            };

            var Helicobacteraceae = {
              x: blob_pic.Helicobacteraceae.Helicobacteraceae_gc,
              y: blob_pic.Helicobacteraceae.Helicobacteraceae_cov,
              text: blob_pic.Helicobacteraceae.Helicobacteraceae_name,
              name: 'Helicobacteraceae',
              mode: 'markers',
              marker: {
                size: Helicobacteraceae_length,
              }
            };

            var Rhizobiaceae = {
              x: blob_pic.Rhizobiaceae.Rhizobiaceae_gc,
              y: blob_pic.Rhizobiaceae.Rhizobiaceae_cov,
              text: blob_pic.Rhizobiaceae.Rhizobiaceae_name,
              name: 'Rhizobiaceae',
              mode: 'markers',
              marker: {
                size: Rhizobiaceae_length,
              }
            };

            var Cryptosporidiidae = {
              x: blob_pic.Cryptosporidiidae.Cryptosporidiidae_gc,
              y: blob_pic.Cryptosporidiidae.Cryptosporidiidae_cov,
              text: blob_pic.Cryptosporidiidae.Cryptosporidiidae_name,
              name: 'Cryptosporidiidae',
              mode: 'markers',
              marker: {
                size: Cryptosporidiidae_length,
              }
            };

            var r_data = [Enterobacteriaceae, Hominidae, Staphylococcaceae, Moraxellaceae, Propionibacteriaceae, Malasseziaceae, Synechococcaceae, Mimiviridae, Helicobacteraceae, Rhizobiaceae, Cryptosporidiidae];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // genus
  function blob_genus() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'genus',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Escherichia_length = blob_pic.Escherichia.Escherichia_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacter_length = blob_pic.Helicobacter.Helicobacter_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Homo_length = blob_pic.Homo.Homo_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_length = blob_pic.Synechococcus.Synechococcus_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cutibacterium_length = blob_pic.Cutibacterium.Cutibacterium_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Moraxella_length = blob_pic.Moraxella.Moraxella_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malassezia_length = blob_pic.Malassezia.Malassezia_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Mimiviridae_undef_length = blob_pic.Mimiviridae_undef.Mimiviridae_undef_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_length = blob_pic.Shigella.Shigella_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Escherichia = {
              x: blob_pic.Escherichia.Escherichia_gc,
              y: blob_pic.Escherichia.Escherichia_cov,
              text: blob_pic.Escherichia.Escherichia_name,
              name: 'Escherichia',
              mode: 'markers',
              marker: {
                size: Escherichia_length,
              }
            };

            var Helicobacter = {
              x: blob_pic.Helicobacter.Helicobacter_gc,
              y: blob_pic.Helicobacter.Helicobacter_cov,
              text: blob_pic.Helicobacter.Helicobacter_name,
              name: 'Helicobacter',
              mode: 'markers',
              marker: {
                size: Helicobacter_length,
              }
            };

            var Homo = {
              x: blob_pic.Homo.Homo_gc,
              y: blob_pic.Homo.Homo_cov,
              text: blob_pic.Homo.Homo_name,
              name: 'Homo',
              mode: 'markers',
              marker: {
                size: Homo_length,
              }
            };

            var Synechococcus = {
              x: blob_pic.Synechococcus.Synechococcus_gc,
              y: blob_pic.Synechococcus.Synechococcus_cov,
              text: blob_pic.Synechococcus.Synechococcus_name,
              name: 'Synechococcus',
              mode: 'markers',
              marker: {
                size: Synechococcus_length,
              }
            };

            var Cutibacterium = {
              x: blob_pic.Cutibacterium.Cutibacterium_gc,
              y: blob_pic.Cutibacterium.Cutibacterium_cov,
              text: blob_pic.Cutibacterium.Cutibacterium_name,
              name: 'Cutibacterium',
              mode: 'markers',
              marker: {
                size: Cutibacterium_length,
              }
            };

            var Moraxella = {
              x: blob_pic.Moraxella.Moraxella_gc,
              y: blob_pic.Moraxella.Moraxella_cov,
              text: blob_pic.Moraxella.Moraxella_name,
              name: 'Moraxella',
              mode: 'markers',
              marker: {
                size: Moraxella_length,
              }
            };

            var Malassezia = {
              x: blob_pic.Malassezia.Malassezia_gc,
              y: blob_pic.Malassezia.Malassezia_cov,
              text: blob_pic.Malassezia.Malassezia_name,
              name: 'Malassezia',
              mode: 'markers',
              marker: {
                size: Malassezia_length,
              }
            };

            var Mimiviridae_undef = {
              x: blob_pic.Mimiviridae_undef.Mimiviridae_undef_gc,
              y: blob_pic.Mimiviridae_undef.Mimiviridae_undef_cov,
              text: blob_pic.Mimiviridae_undef.Mimiviridae_undef_name,
              name: 'Mimiviridae_undef',
              mode: 'markers',
              marker: {
                size: Mimiviridae_undef_length,
              }
            };

            var Shigella = {
              x: blob_pic.Shigella.Shigella_gc,
              y: blob_pic.Shigella.Shigella_cov,
              text: blob_pic.Shigella.Shigella_name,
              name: 'Shigella',
              mode: 'markers',
              marker: {
                size: Shigella_length,
              }
            };

            var r_data = [Escherichia, Helicobacter, Homo, Synechococcus, Cutibacterium, Moraxella, Malassezia, Mimiviridae_undef, Shigella];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'blob_classify': 'order',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Escherichia_length = blob_pic.Escherichia.Escherichia_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacter_length = blob_pic.Helicobacter.Helicobacter_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Homo_length = blob_pic.Homo.Homo_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_length = blob_pic.Synechococcus.Synechococcus_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cutibacterium_length = blob_pic.Cutibacterium.Cutibacterium_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Moraxella_length = blob_pic.Moraxella.Moraxella_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malassezia_length = blob_pic.Malassezia.Malassezia_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Mimiviridae_undef_length = blob_pic.Mimiviridae_undef.Mimiviridae_undef_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_length = blob_pic.Shigella.Shigella_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Escherichia = {
              x: blob_pic.Escherichia.Escherichia_gc,
              y: blob_pic.Escherichia.Escherichia_cov,
              text: blob_pic.Escherichia.Escherichia_name,
              name: 'Escherichia',
              mode: 'markers',
              marker: {
                size: Escherichia_length,
              }
            };

            var Helicobacter = {
              x: blob_pic.Helicobacter.Helicobacter_gc,
              y: blob_pic.Helicobacter.Helicobacter_cov,
              text: blob_pic.Helicobacter.Helicobacter_name,
              name: 'Helicobacter',
              mode: 'markers',
              marker: {
                size: Helicobacter_length,
              }
            };

            var Homo = {
              x: blob_pic.Homo.Homo_gc,
              y: blob_pic.Homo.Homo_cov,
              text: blob_pic.Homo.Homo_name,
              name: 'Homo',
              mode: 'markers',
              marker: {
                size: Homo_length,
              }
            };

            var Synechococcus = {
              x: blob_pic.Synechococcus.Synechococcus_gc,
              y: blob_pic.Synechococcus.Synechococcus_cov,
              text: blob_pic.Synechococcus.Synechococcus_name,
              name: 'Synechococcus',
              mode: 'markers',
              marker: {
                size: Synechococcus_length,
              }
            };

            var Cutibacterium = {
              x: blob_pic.Cutibacterium.Cutibacterium_gc,
              y: blob_pic.Cutibacterium.Cutibacterium_cov,
              text: blob_pic.Cutibacterium.Cutibacterium_name,
              name: 'Cutibacterium',
              mode: 'markers',
              marker: {
                size: Cutibacterium_length,
              }
            };

            var Moraxella = {
              x: blob_pic.Moraxella.Moraxella_gc,
              y: blob_pic.Moraxella.Moraxella_cov,
              text: blob_pic.Moraxella.Moraxella_name,
              name: 'Moraxella',
              mode: 'markers',
              marker: {
                size: Moraxella_length,
              }
            };

            var Malassezia = {
              x: blob_pic.Malassezia.Malassezia_gc,
              y: blob_pic.Malassezia.Malassezia_cov,
              text: blob_pic.Malassezia.Malassezia_name,
              name: 'Malassezia',
              mode: 'markers',
              marker: {
                size: Malassezia_length,
              }
            };

            var Mimiviridae_undef = {
              x: blob_pic.Mimiviridae_undef.Mimiviridae_undef_gc,
              y: blob_pic.Mimiviridae_undef.Mimiviridae_undef_cov,
              text: blob_pic.Mimiviridae_undef.Mimiviridae_undef_name,
              name: 'Mimiviridae_undef',
              mode: 'markers',
              marker: {
                size: Mimiviridae_undef_length,
              }
            };

            var Shigella = {
              x: blob_pic.Shigella.Shigella_gc,
              y: blob_pic.Shigella.Shigella_cov,
              text: blob_pic.Shigella.Shigella_name,
              name: 'Shigella',
              mode: 'markers',
              marker: {
                size: Shigella_length,
              }
            };

            var r_data = [Escherichia, Helicobacter, Homo, Synechococcus, Cutibacterium, Moraxella, Malassezia, Mimiviridae_undef, Shigella];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  // species
  function blob_species() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      var blob = $('#blob_pic_tabs option:selected').val()
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob': blob,
          'blob_classify': 'species',
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Escherichia_coli_length = blob_pic.Escherichia_coli.Escherichia_coli_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Homo_sapiens_length = blob_pic.Homo_sapiens.Homo_sapiens_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_sp_UTEX_length = blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cutibacterium_acnes_length = blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Staphylococcus_epidermidis_length = blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacter_pylori_length = blob_pic.Helicobacter_pylori.Helicobacter_pylori_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_flexneri_length = blob_pic.Shigella_flexneri.Shigella_flexneri_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_sonnei_length = blob_pic.Shigella_sonnei.Shigella_sonnei_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malassezia_restricta_length = blob_pic.Malassezia_restricta.Malassezia_restricta_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var eukaryotic_synthetic_construct_length = blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_elongatus_length = blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Pan_troglodytes_length = blob_pic.Pan_troglodytes.Pan_troglodytes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Haloarcula_hispanica_length = blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Escherichia_coli = {
              x: blob_pic.Escherichia_coli.Escherichia_coli_gc,
              y: blob_pic.Escherichia_coli.Escherichia_coli_cov,
              text: blob_pic.Escherichia_coli.Escherichia_coli_name,
              name: 'Escherichia_coli',
              mode: 'markers',
              marker: {
                size: Escherichia_coli_length,
              }
            };

            var Homo_sapiens = {
              x: blob_pic.Homo_sapiens.Homo_sapiens_gc,
              y: blob_pic.Homo_sapiens.Homo_sapiens_cov,
              text: blob_pic.Homo_sapiens.Homo_sapiens_name,
              name: 'Homo_sapiens',
              mode: 'markers',
              marker: {
                size: Homo_sapiens_length,
              }
            };

            var Synechococcus_sp_UTEX = {
              x: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_gc,
              y: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_cov,
              text: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_name,
              name: 'Synechococcus_sp_UTEX',
              mode: 'markers',
              marker: {
                size: Synechococcus_sp_UTEX_length,
              }
            };

            var Cutibacterium_acnes = {
              x: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_gc,
              y: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_cov,
              text: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_name,
              name: 'Cutibacterium_acnes',
              mode: 'markers',
              marker: {
                size: Cutibacterium_acnes_length,
              }
            };

            var Staphylococcus_epidermidis = {
              x: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_gc,
              y: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_cov,
              text: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_name,
              name: 'Staphylococcus_epidermidis',
              mode: 'markers',
              marker: {
                size: Staphylococcus_epidermidis_length,
              }
            };

            var Helicobacter_pylori = {
              x: blob_pic.Helicobacter_pylori.Helicobacter_pylori_gc,
              y: blob_pic.Helicobacter_pylori.Helicobacter_pylori_cov,
              text: blob_pic.Helicobacter_pylori.Helicobacter_pylori_name,
              name: 'Helicobacter_pylori',
              mode: 'markers',
              marker: {
                size: Helicobacter_pylori_length,
              }
            };

            var Shigella_flexneri = {
              x: blob_pic.Shigella_flexneri.Shigella_flexneri_gc,
              y: blob_pic.Shigella_flexneri.Shigella_flexneri_cov,
              text: blob_pic.Shigella_flexneri.Shigella_flexneri_name,
              name: 'Shigella_flexneri',
              mode: 'markers',
              marker: {
                size: Shigella_flexneri_length,
              }
            };

            var Shigella_sonnei = {
              x: blob_pic.Shigella_sonnei.Shigella_sonnei_gc,
              y: blob_pic.Shigella_sonnei.Shigella_sonnei_cov,
              text: blob_pic.Shigella_sonnei.Shigella_sonnei_name,
              name: 'Shigella_sonnei',
              mode: 'markers',
              marker: {
                size: Shigella_sonnei_length,
              }
            };

            var Malassezia_restricta = {
              x: blob_pic.Malassezia_restricta.Malassezia_restricta_gc,
              y: blob_pic.Malassezia_restricta.Malassezia_restricta_cov,
              text: blob_pic.Malassezia_restricta.Malassezia_restricta_name,
              name: 'Malassezia_restricta',
              mode: 'markers',
              marker: {
                size: Malassezia_restricta_length,
              }
            };

            var eukaryotic_synthetic_construct = {
              x: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_gc,
              y: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_cov,
              text: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_name,
              name: 'eukaryotic_synthetic_construct',
              mode: 'markers',
              marker: {
                size: eukaryotic_synthetic_construct_length,
              }
            };

            var Synechococcus_elongatus = {
              x: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_gc,
              y: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_cov,
              text: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_name,
              name: 'Synechococcus_elongatus',
              mode: 'markers',
              marker: {
                size: Synechococcus_elongatus_length,
              }
            };

            var Pan_troglodytes = {
              x: blob_pic.Pan_troglodytes.Pan_troglodytes_gc,
              y: blob_pic.Pan_troglodytes.Pan_troglodytes_cov,
              text: blob_pic.Pan_troglodytes.Pan_troglodytes_name,
              name: 'Pan_troglodytes',
              mode: 'markers',
              marker: {
                size: Pan_troglodytes_length,
              }
            };

            var Haloarcula_hispanica = {
              x: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_gc,
              y: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_cov,
              text: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_name,
              name: 'Haloarcula_hispanica',
              mode: 'markers',
              marker: {
                size: Haloarcula_hispanica_length,
              }
            };

            var r_data = [Escherichia_coli, Homo_sapiens, Synechococcus_sp_UTEX, Cutibacterium_acnes, Staphylococcus_epidermidis, Helicobacter_pylori, Shigella_flexneri, Shigella_sonnei, Malassezia_restricta, eukaryotic_synthetic_construct, Synechococcus_elongatus, Pan_troglodytes, Haloarcula_hispanica];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning/blob_classify',
        type: 'POST',
        data: {
          'blob_classify': 'species',
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          var blob_picture = document.getElementById('blob_pic');
          if (res.code == 200) {
            let blob_pic = res.data;
            console.log(blob_pic)
            var Escherichia_coli_length = blob_pic.Escherichia_coli.Escherichia_coli_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Homo_sapiens_length = blob_pic.Homo_sapiens.Homo_sapiens_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_sp_UTEX_length = blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Cutibacterium_acnes_length = blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Staphylococcus_epidermidis_length = blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Helicobacter_pylori_length = blob_pic.Helicobacter_pylori.Helicobacter_pylori_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_flexneri_length = blob_pic.Shigella_flexneri.Shigella_flexneri_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Shigella_sonnei_length = blob_pic.Shigella_sonnei.Shigella_sonnei_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Malassezia_restricta_length = blob_pic.Malassezia_restricta.Malassezia_restricta_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var eukaryotic_synthetic_construct_length = blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Synechococcus_elongatus_length = blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Pan_troglodytes_length = blob_pic.Pan_troglodytes.Pan_troglodytes_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });
            var Haloarcula_hispanica_length = blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_length.map(function (i) {
              return (Math.log(i) / Math.log(2)) * 3;
            });

            var Escherichia_coli = {
              x: blob_pic.Escherichia_coli.Escherichia_coli_gc,
              y: blob_pic.Escherichia_coli.Escherichia_coli_cov,
              text: blob_pic.Escherichia_coli.Escherichia_coli_name,
              name: 'Escherichia_coli',
              mode: 'markers',
              marker: {
                size: Escherichia_coli_length,
              }
            };

            var Homo_sapiens = {
              x: blob_pic.Homo_sapiens.Homo_sapiens_gc,
              y: blob_pic.Homo_sapiens.Homo_sapiens_cov,
              text: blob_pic.Homo_sapiens.Homo_sapiens_name,
              name: 'Homo_sapiens',
              mode: 'markers',
              marker: {
                size: Homo_sapiens_length,
              }
            };

            var Synechococcus_sp_UTEX = {
              x: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_gc,
              y: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_cov,
              text: blob_pic.Synechococcus_sp_UTEX.Synechococcus_sp_UTEX_name,
              name: 'Synechococcus_sp_UTEX',
              mode: 'markers',
              marker: {
                size: Synechococcus_sp_UTEX_length,
              }
            };

            var Cutibacterium_acnes = {
              x: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_gc,
              y: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_cov,
              text: blob_pic.Cutibacterium_acnes.Cutibacterium_acnes_name,
              name: 'Cutibacterium_acnes',
              mode: 'markers',
              marker: {
                size: Cutibacterium_acnes_length,
              }
            };

            var Staphylococcus_epidermidis = {
              x: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_gc,
              y: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_cov,
              text: blob_pic.Staphylococcus_epidermidis.Staphylococcus_epidermidis_name,
              name: 'Staphylococcus_epidermidis',
              mode: 'markers',
              marker: {
                size: Staphylococcus_epidermidis_length,
              }
            };

            var Helicobacter_pylori = {
              x: blob_pic.Helicobacter_pylori.Helicobacter_pylori_gc,
              y: blob_pic.Helicobacter_pylori.Helicobacter_pylori_cov,
              text: blob_pic.Helicobacter_pylori.Helicobacter_pylori_name,
              name: 'Helicobacter_pylori',
              mode: 'markers',
              marker: {
                size: Helicobacter_pylori_length,
              }
            };

            var Shigella_flexneri = {
              x: blob_pic.Shigella_flexneri.Shigella_flexneri_gc,
              y: blob_pic.Shigella_flexneri.Shigella_flexneri_cov,
              text: blob_pic.Shigella_flexneri.Shigella_flexneri_name,
              name: 'Shigella_flexneri',
              mode: 'markers',
              marker: {
                size: Shigella_flexneri_length,
              }
            };

            var Shigella_sonnei = {
              x: blob_pic.Shigella_sonnei.Shigella_sonnei_gc,
              y: blob_pic.Shigella_sonnei.Shigella_sonnei_cov,
              text: blob_pic.Shigella_sonnei.Shigella_sonnei_name,
              name: 'Shigella_sonnei',
              mode: 'markers',
              marker: {
                size: Shigella_sonnei_length,
              }
            };

            var Malassezia_restricta = {
              x: blob_pic.Malassezia_restricta.Malassezia_restricta_gc,
              y: blob_pic.Malassezia_restricta.Malassezia_restricta_cov,
              text: blob_pic.Malassezia_restricta.Malassezia_restricta_name,
              name: 'Malassezia_restricta',
              mode: 'markers',
              marker: {
                size: Malassezia_restricta_length,
              }
            };

            var eukaryotic_synthetic_construct = {
              x: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_gc,
              y: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_cov,
              text: blob_pic.eukaryotic_synthetic_construct.eukaryotic_synthetic_construct_name,
              name: 'eukaryotic_synthetic_construct',
              mode: 'markers',
              marker: {
                size: eukaryotic_synthetic_construct_length,
              }
            };

            var Synechococcus_elongatus = {
              x: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_gc,
              y: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_cov,
              text: blob_pic.Synechococcus_elongatus.Synechococcus_elongatus_name,
              name: 'Synechococcus_elongatus',
              mode: 'markers',
              marker: {
                size: Synechococcus_elongatus_length,
              }
            };

            var Pan_troglodytes = {
              x: blob_pic.Pan_troglodytes.Pan_troglodytes_gc,
              y: blob_pic.Pan_troglodytes.Pan_troglodytes_cov,
              text: blob_pic.Pan_troglodytes.Pan_troglodytes_name,
              name: 'Pan_troglodytes',
              mode: 'markers',
              marker: {
                size: Pan_troglodytes_length,
              }
            };

            var Haloarcula_hispanica = {
              x: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_gc,
              y: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_cov,
              text: blob_pic.Haloarcula_hispanica.Haloarcula_hispanica_name,
              name: 'Haloarcula_hispanica',
              mode: 'markers',
              marker: {
                size: Haloarcula_hispanica_length,
              }
            };

            var r_data = [Escherichia_coli, Homo_sapiens, Synechococcus_sp_UTEX, Cutibacterium_acnes, Staphylococcus_epidermidis, Helicobacter_pylori, Shigella_flexneri, Shigella_sonnei, Malassezia_restricta, eukaryotic_synthetic_construct, Synechococcus_elongatus, Pan_troglodytes, Haloarcula_hispanica];
            var layout = {
              height: 1000,
              width: 1000
            }
            Plotly.newPlot(blob_picture, r_data, layout);
          }
        }
      })
    }
  }

  function getVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
  }

  function getRootPath() {
    var wwwPath = window.document.location.href;
    var pathName = window.document.location.pathname;
    var pos = wwwPath.indexOf(pathName);
    var localhostPath = wwwPath.substring(0, pos);
    return localhostPath;
  }
})
