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
  var image_blob_tabs = $('#blob_tabs li a');
  var krona = document.createElement('iframe');
  var iframe_krona_tabs = $('#kraken_tabs li a');
  var preseq_tabs = $('#preseq_tabs li a');
  var arg_tabs = $('#arg_tabs li a');
  var bowtie_tabs = $('#bowtie_tabs li a');

  if (window.location.href.indexOf('successRunning') != -1) {
    $('#quast_dataTable thead tr').empty();
    $('#quast_dataTable tbody').empty();
    read_quast_data();
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
        window.alert = function () {};
        read_arg_data();
      }
    }

    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        read_bowtie_data();
      }
    }
  } else {
    var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + iframe_krona_tabs.first().text() + '.krona.html';
    var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + image_blob_tabs.first().text() + '/' + image_blob_tabs.first().text() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
    krona.setAttribute('src', krona_src);
    krona.setAttribute('class', 'embed-responsive-item');
    if (krona_tab != null) {
      krona_tab.onclick = function () {
        $('#kraken_tabs li').first().addClass('active');
        var kraken_report = document.getElementsByClassName('kraken_report')[0];
        kraken_report.appendChild(krona);
      }
    }
    if (blob_tab != null) {
      blob_tab.onclick = function () {
        $('#blob_tabs li').first().addClass('active');
        $('#blob_image').attr('src', blob_src);
      }
    }

    if (arg_tab != null) {
      arg_tab.onclick = function () {
        window.alert = function () {};
        $('#arg_tabs li').first().addClass('active');
        read_arg_data();
      }
    }

    if (bowtie_tab != null) {
      bowtie_tab.onclick = function () {
        window.alert = function () {};
        $('#bowtie_tabs li').first().addClass('active');
        read_bowtie_data();
      }
    }

    iframe_krona_tabs.on('click', function (e) {
      e.preventDefault();
      $('#kraken_tabs li').removeClass('active');
      $(this).parent().addClass('active');
      var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + $(this).text() + '.krona.html';
      krona.setAttribute('src', krona_src);
      krona.setAttribute('class', 'embed-responsive-item');
      $('#kraken_report').empty();
      $('#kraken_report').append(krona);
    })

    image_blob_tabs.on('click', function (e) {
      e.preventDefault();
      $('#blob_tabs li').removeClass('active');
      $(this).parent().addClass('active');
      var rootPath = getRootPath();
      var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + $(this).text() + '/' + $(this).text() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
      $('#blob_image').attr('src', blob_src);
      console.log(blob_src);
    })
  }

  if ($('.blob_browser:has(img)').length == 0) {
    $('.blob_browser').append('<p> No Blob Result! </p>');
  }

  if (preseq_tab != null) {
    preseq_tab.onclick = function () {
      $('#preseq_tabs li').first().addClass('active');
      read_preseq_cdata();
    }
  }

  preseq_tabs.on('click', function (e) {
    e.preventDefault();
    $('#preseq_report').remove();
    $('#preseq_tabs li').removeClass('active');
    $(this).parent().addClass('active');
    var new_preseq_report = $('<div></div>');
    new_preseq_report.attr('id', 'preseq_report');
    new_preseq_report.addClass('w-100 overflow-hidden mt-4');
    $('.preseq_report').append(new_preseq_report);
    if ($(this).text().indexOf('_c') != -1) {
      read_preseq_cdata();
    } else {
      read_preseq_lcgcdata();
    }
  })

  arg_tabs.on('click', function (e) {
    e.preventDefault();
    window.alert = function () {};
    $('#arg_tabs li').removeClass('active');
    $(this).parent().addClass('active');
    var table = $('#arg_dataTable').DataTable({
      paging: false
    });
    table.destroy();
    read_arg_data();

  })

  bowtie_tabs.on('click', function (e) {
    e.preventDefault();
    $('#bowtie_tabs li').removeClass('active');
    $(this).parent().addClass('active');
    var table = $('#bowtie_dataTable').DataTable({
      paging: false
    });
    table.destroy();
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
      var preseq = $('#preseq_tabs li.active').children().text();
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
      var preseq = $('#preseq_tabs li.active').children().text();
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
            console.log(x_axios);
            console.log(res);
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
      var arg = $('#arg_tabs li.active').children().text();
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
            var data = res.data;
            $('#arg_dataTable').DataTable({
              data: data,
            });

            $('#table_id_example tbody').on('click', 'tr', function () {
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
            var data = res.data;
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

  function read_quast_data() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          projectID: projectID,
          quast: true
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let data = res.data;
            console.log(data);
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#quast_dataTable tbody').append(tr);
            }
            for (let j = 0; j < data[0].length; j++) {
              let td = $('<td></td>');
              $('#quast_dataTable thead tr').append(td);
            }
            var quast_table = $('#quast_dataTable').DataTable({
              data: data,
            });
            change_footer_position();
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
    } else {
      var sampleID = getVariable('sampleID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          sampleID: sampleID,
          quast: true
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            let data = res.data;
            let quast_header = data.quast_header;
            let quast_result = data.quast_result;
            console.log(quast_header);
            console.log(quast_result);
            let quast = [];
            quast.push(quast_result);
            for (i = 0; i < quast_header.length; i++) {
              var td = $('<td></td>').text(quast_header[i]);
              $('#quast_dataTable thead tr').append(td);
            }
            var quast_table = $('#quast_dataTable').DataTable({
              data: quast,
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
      var bowtie = $('#bowtie_tabs li.active').children().text();
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
            let data = res.data;
            let bowtie_header = data[0];
            let bowtie_result = [];
            bowtie_result.push(data[1]);
            $('#bowtie_table thead tr').empty();
            $('#bowtie_table tbody tr').empty();
            for (i = 0; i < bowtie_header.length; i++) {
              var td = $('<td></td>').text(bowtie_header[i]);
              $('#bowtie_dataTable thead tr').append(td);
            }
            var bowtie_table = $('#bowtie_dataTable').DataTable({
              data: bowtie_result,
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
            let data = res.data;
            let bowtie_header = data[0];
            let bowtie_result = [];
            bowtie_result.push(data[1]);
            $('#bowtie_dataTable thead tr').empty();
            $('#bowtie_dataTable tbody tr').empty();
            for (i = 0; i < res.data[0].length; i++) {
              var td = $('<td></td>').text(res.data[0][i]);
              $('#bowtie_dataTable thead tr').append(td);
            }
            var bowtie_table = $('#bowtie_dataTable').DataTable({
              data: bowtie_result,
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

  function change_footer_position(){
    if ($(window).height() > $('body').height()) {
        $('footer').css('top', $(window).height() - $('footer').height() + 'px');
      } else {
        $('footer').css('top', $('body').height() - $('footer').height() + 30 + 'px');
      }
  }
})
