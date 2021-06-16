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
  var checkM_tab = document.getElementById('v-pills-checkM-tab');
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
    var krona_src = '/kraken?sample_uuid=' + $('.iframe_sample_uuid').text() + '&sample_name=' + $('.iframe_sample_name').text();
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

    if (checkM_tab != null) {
      checkM_tab.onclick = function () {
        if ($('#checkM_dataTable thead tr:has(th)').length == 0) {
          read_checkM_data();
        }
      }
    }
  } else {
    var krona_src = '/kraken?project_uuid=' + $('.iframe_project_uuid').text() + '&sample_name=' + iframe_krona_tabs.first().val();
    var blob_src = '/blob?project_uuid=' + $('.iframe_project_uuid').text() + '&sample_name=' + image_blob_tabs.first().val();
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

    if (checkM_tab != null) {
      checkM_tab.onclick = function () {
        if ($('#checkM_dataTable thead tr:has(th)').length == 0) {
          read_checkM_data();
        }
      }
    }

    iframe_krona_tabs.on('change', function (e) {
      e.preventDefault();
      var krona_src = '/kraken?project_uuid=' + $('.iframe_project_uuid').text() + '&sample_name=' + $('#kraken_tabs option:selected').val();
      krona.setAttribute('src', krona_src);
      krona.setAttribute('class', 'embed-responsive-item');
      $('#kraken_report').empty();
      $('#kraken_report').append(krona);
    })

    image_blob_tabs.on('change', function (e) {
      e.preventDefault();
      var rootPath = getRootPath();
      var blob_src = '/blob?project_uuid=' + $('.iframe_project_uuid').text() + '&sample_name=' + $('#blob_tabs option:selected').val();
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
    blob_classify();
  })

  blob_classify_tabs.on('change', function (e) {
    e.preventDefault();
    blob_classify();
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
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#arg_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#arg_dataTable tbody').append(tr);
            }
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
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#arg_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#arg_dataTable tbody').append(tr);
            }
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
              $('.fading_circles_quast').remove();
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
              $('.fading_circles_blob').remove();
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
              let phylum_list = [];
              let phylum_data = [];
              for (i = 0; i < blob_pic.length; i++) {
                if (phylum_list.indexOf(blob_pic[i][6]) == -1) {
                  if (phylum_list.length < 9) {
                    phylum_list.push(blob_pic[i][6]);
                  } else {
                    phylum_list.push('others');
                    break;
                  }
                }
              }
              for (j = 0; j < phylum_list.length; j++) {
                if (j < 9) {
                  let blob = phylum_list[j];
                  let name = phylum_list[j] + '_name';
                  let length = phylum_list[j] + '_length';
                  let gc = phylum_list[j] + '_gc';
                  let cov = phylum_list[j] + '_cov';
                  let total_length = phylum_list[j] + '_total_length';
                  window[total_length] = 0;
                  window[name] = [];
                  window[length] = [];
                  window[gc] = [];
                  window[cov] = [];
                  for (v = 0; v < blob_pic.length; v++) {
                    if (blob_pic[v][6] == phylum_list[j]) {
                      window[name].push(blob_pic[v][0]);
                      window[length].push(blob_pic[v][1]);
                      window[gc].push(blob_pic[v][2]);
                      window[cov].push(blob_pic[v][4]);
                      window[total_length] += parseInt(blob_pic[v][1]);
                    }
                  }
                  window[length] = window[length].map(function (i) {
                    return (Math.log(i) / Math.log(2)) * 3;
                  })
                  window[blob] = {
                    x: window[gc],
                    y: window[cov],
                    text: window[name],
                    name: phylum_list[j],
                    mode: 'markers',
                    marker: {
                      size: window[length],
                    },
                    total_length: window[total_length]
                  }
                  phylum_data.push(window[blob]);
                } else {
                  let other_blob = phylum_list[j];
                  let other_name = phylum_list[j] + '_name';
                  let other_length = phylum_list[j] + '_length';
                  let other_gc = phylum_list[j] + '_gc';
                  let other_cov = phylum_list[j] + '_cov';
                  window[other_name] = [];
                  window[other_length] = [];
                  window[other_gc] = [];
                  window[other_cov] = [];
                  for (v = 0; v < blob_pic.length; v++) {
                    if (phylum_list.indexOf(blob_pic[v][6]) == -1) {
                      window[other_name].push(blob_pic[v][0]);
                      window[other_length].push(blob_pic[v][1]);
                      window[other_gc].push(blob_pic[v][2]);
                      window[other_cov].push(blob_pic[v][4]);
                    }
                  }
                  window[other_length] = window[other_length].map(function (i) {
                    return (Math.log(i) / Math.log(2)) * 3;
                  })
                  window[other_blob] = {
                    x: window[other_gc],
                    y: window[other_cov],
                    text: window[other_name],
                    name: phylum_list[j],
                    mode: 'markers',
                    marker: {
                      size: window[other_length],
                    },
                    total_length: 0
                  }
                  phylum_data.push(window[other_blob]);
                }
              }
              phylum_data.sort(compare('total_length'));
              var layout = {
                yaxis: {
                  type: 'log',
                  exponentformat: 'E'
                },
                width: 900,
                height: 900
              }
              Plotly.newPlot(blob_picture, phylum_data, layout);
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
              $('.fading_circles_quast').remove();
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
              $('.fading_circles_blob').remove();
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
              let phylum_list = [];
              let phylum_data = [];
              for (i = 0; i < data.length; i++) {
                if (phylum_list.indexOf(data[i][6]) == -1) {
                  if (phylum_list.length < 9) {
                    phylum_list.push(data[i][6]);
                  } else {
                    phylum_list.push('others');
                    break;
                  }
                }
              }
              for (j = 0; j < phylum_list.length; j++) {
                if (j < 9) {
                  let blob = phylum_list[j];
                  let name = phylum_list[j] + '_name';
                  let length = phylum_list[j] + '_length';
                  let gc = phylum_list[j] + '_gc';
                  let cov = phylum_list[j] + '_cov';
                  let total_length = phylum_list[j] + '_total_length';
                  window[total_length] = 0;
                  window[name] = [];
                  window[length] = [];
                  window[gc] = [];
                  window[cov] = [];
                  for (v = 0; v < data.length; v++) {
                    if (data[v][6] == phylum_list[j]) {
                      window[name].push(data[v][0]);
                      window[length].push(data[v][1]);
                      window[gc].push(data[v][2]);
                      window[cov].push(data[v][4]);
                      window[total_length] += parseInt(data[v][1]);
                    }
                  }
                  window[length] = window[length].map(function (i) {
                    return (Math.log(i) / Math.log(2)) * 3;
                  })
                  window[blob] = {
                    x: window[gc],
                    y: window[cov],
                    text: window[name],
                    name: phylum_list[j],
                    mode: 'markers',
                    marker: {
                      size: window[length],
                    },
                    total_length: window[total_length]
                  }
                  phylum_data.push(window[blob]);
                } else {
                  let other_blob = phylum_list[j];
                  let other_name = phylum_list[j] + '_name';
                  let other_length = phylum_list[j] + '_length';
                  let other_gc = phylum_list[j] + '_gc';
                  let other_cov = phylum_list[j] + '_cov';
                  window[other_name] = [];
                  window[other_length] = [];
                  window[other_gc] = [];
                  window[other_cov] = [];
                  for (v = 0; v < data.length; v++) {
                    if (phylum_list.indexOf(data[v][6]) == -1) {
                      window[other_name].push(data[v][0]);
                      window[other_length].push(data[v][1]);
                      window[other_gc].push(data[v][2]);
                      window[other_cov].push(data[v][4]);
                    }
                  }
                  window[other_length] = window[other_length].map(function (i) {
                    return (Math.log(i) / Math.log(2)) * 3;
                  })
                  window[other_blob] = {
                    x: window[other_gc],
                    y: window[other_cov],
                    text: window[other_name],
                    name: phylum_list[j],
                    mode: 'markers',
                    marker: {
                      size: window[other_length],
                    },
                    total_length: 0
                  }
                  phylum_data.push(window[other_blob]);
                }
              }
              phylum_data.sort(compare('total_length'));
              var layout = {
                yaxis: {
                  type: 'log',
                  exponentformat: 'E'
                },
                width: 900,
                height: 900
              }
              Plotly.newPlot(blob_picture, phylum_data, layout);
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
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#bowtie_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#bowtie_dataTable tbody').append(tr);
            }
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
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#bowtie_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#bowtie_dataTable tbody').append(tr);
            }
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

  //read checkM data
  function read_checkM_data() {
    if (window.location.href.indexOf('projectID') != -1) {
      var projectID = getVariable('projectID');
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/successRunning',
        type: 'POST',
        data: {
          'checkM': true,
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var checkM_data = res.data;
            let data = []
            checkM_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            });
            $('#checkM_dataTable thead tr').empty();
            $('#checkM_dataTable tbody').empty();
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#checkM_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#checkM_dataTable tbody').append(tr);
            }
            var checkM_table = $('#checkM_dataTable').DataTable({
              data: data,
            });
            $('#checkM_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                checkM_table.$('tr.selected').removeClass('selected');
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
          'checkM': true,
          'sampleID': sampleID
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var checkM_data = res.data;
            let data = []
            checkM_data.forEach(item => {
              item.forEach((d, i) => {
                let a = data[i] = data[i] || []
                a.push(d)
              })
            })
            $('#checkM_dataTable thead tr').empty();
            $('#checkM_dataTable tbody').empty();
            for (let j = 0; j < data[0].length; j++) {
              let th = $('<th></th>');
              if (j == 0) {
                th.text('Item');
              }
              if (j == 1) {
                th.text('Value');
              }
              $('#checkM_dataTable thead tr').append(th);
            }
            for (let i = 0; i < data.length; i++) {
              let tr = $('<tr></tr>');
              for (let j = 0; j < data[i].length; j++) {
                let td = $('<td></td>');
                tr.append(td);
              }
              $('#checkM_dataTable tbody').append(tr);
            }
            var checkM_table = $('#checkM_dataTable').DataTable({
              data: data,
            });

            $('#checkM_dataTable tbody').on('click', 'tr', function () {
              if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
              }
              else {
                checkM_table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
              }
            });
          }
        }
      })
    }
  }

  // blob classify
  function blob_classify() {
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
          'projectID': projectID
        },
        dataType: 'json',
        success: function (res) {
          let blob_picture = document.getElementById('blob_pic');
          let data = res.data;
          switch ($('#blob_classify option:selected').val()) {
          case 'superkingdom':
            let superkingdom_list = [];
            let superkingdom_data = [];
            for (i = 0; i < data.length; i++) {
              if (superkingdom_list.indexOf(data[i][5]) == -1) {
                if (superkingdom_list.length < 9) {
                  superkingdom_list.push(data[i][5]);
                } else {
                  superkingdom_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < superkingdom_list.length; j++) {
              if (j < 9) {
                let blob = superkingdom_list[j];
                let name = superkingdom_list[j] + '_name';
                let length = superkingdom_list[j] + '_length';
                let gc = superkingdom_list[j] + '_gc';
                let cov = superkingdom_list[j] + '_cov';
                let total_length = superkingdom_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][5] == superkingdom_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: superkingdom_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                superkingdom_data.push(window[blob]);
              } else {
                let other_blob = superkingdom_list[j];
                let other_name = superkingdom_list[j] + '_name';
                let other_length = superkingdom_list[j] + '_length';
                let other_gc = superkingdom_list[j] + '_gc';
                let other_cov = superkingdom_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (superkingdom_list.indexOf(data[v][5]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: superkingdom_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                superkingdom_data.push(window[other_blob]);
              }
            }
            superkingdom_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, superkingdom_data, layout);
            break;
          case 'phylum':
            let phylum_list = [];
            let phylum_data = [];
            for (i = 0; i < data.length; i++) {
              if (phylum_list.indexOf(data[i][6]) == -1) {
                if (phylum_list.length < 9) {
                  phylum_list.push(data[i][6]);
                } else {
                  phylum_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < phylum_list.length; j++) {
              if (j < 9) {
                let blob = phylum_list[j];
                let name = phylum_list[j] + '_name';
                let length = phylum_list[j] + '_length';
                let gc = phylum_list[j] + '_gc';
                let cov = phylum_list[j] + '_cov';
                let total_length = phylum_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][6] == phylum_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: phylum_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                phylum_data.push(window[blob]);
              } else {
                let other_blob = phylum_list[j];
                let other_name = phylum_list[j] + '_name';
                let other_length = phylum_list[j] + '_length';
                let other_gc = phylum_list[j] + '_gc';
                let other_cov = phylum_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (phylum_list.indexOf(data[v][6]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: phylum_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                phylum_data.push(window[other_blob]);
              }
            }
            phylum_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, phylum_data, layout);
            break;
          case 'order':
            let order_list = [];
            let order_data = [];
            for (i = 0; i < data.length; i++) {
              if (order_list.indexOf(data[i][7]) == -1) {
                if (order_list.length < 9) {
                  order_list.push(data[i][7]);
                } else {
                  order_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < order_list.length; j++) {
              if (j < 9) {
                let blob = order_list[j];
                let name = order_list[j] + '_name';
                let length = order_list[j] + '_length';
                let gc = order_list[j] + '_gc';
                let cov = order_list[j] + '_cov';
                let total_length = order_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][7] == order_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: order_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                order_data.push(window[blob]);
              } else {
                let other_blob = order_list[j];
                let other_name = order_list[j] + '_name';
                let other_length = order_list[j] + '_length';
                let other_gc = order_list[j] + '_gc';
                let other_cov = order_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (order_list.indexOf(data[v][7]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: order_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                order_data.push(window[other_blob]);
              }
            }
            order_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, order_data, layout);
            break;
          case 'family':
            let family_list = [];
            let family_data = [];
            for (i = 0; i < data.length; i++) {
              if (family_list.indexOf(data[i][8]) == -1) {
                if (family_list.length < 9) {
                  family_list.push(data[i][8]);
                } else {
                  family_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < family_list.length; j++) {
              if (j < 9) {
                let blob = family_list[j];
                let name = family_list[j] + '_name';
                let length = family_list[j] + '_length';
                let gc = family_list[j] + '_gc';
                let cov = family_list[j] + '_cov';
                let total_length = family_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][8] == family_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: family_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                family_data.push(window[blob]);
              } else {
                let other_blob = family_list[j];
                let other_name = family_list[j] + '_name';
                let other_length = family_list[j] + '_length';
                let other_gc = family_list[j] + '_gc';
                let other_cov = family_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (family_list.indexOf(data[v][8]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: family_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                family_data.push(window[other_blob]);
              }
            }
            family_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, family_data, layout);
            break;
          case 'genus':
            let genus_list = [];
            let genus_data = [];
            for (i = 0; i < data.length; i++) {
              if (genus_list.indexOf(data[i][9]) == -1) {
                if (genus_list.length < 9) {
                  genus_list.push(data[i][9]);
                } else {
                  genus_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < genus_list.length; j++) {
              if (j < 9) {
                let blob = genus_list[j];
                let name = genus_list[j] + '_name';
                let length = genus_list[j] + '_length';
                let gc = genus_list[j] + '_gc';
                let cov = genus_list[j] + '_cov';
                let total_length = genus_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][9] == genus_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: genus_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                genus_data.push(window[blob]);
              } else {
                let other_blob = genus_list[j];
                let other_name = genus_list[j] + '_name';
                let other_length = genus_list[j] + '_length';
                let other_gc = genus_list[j] + '_gc';
                let other_cov = genus_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (genus_list.indexOf(data[v][9]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: genus_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                genus_data.push(window[other_blob]);
              }
            }
            genus_data.sort(compare('total_length'));

            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, genus_data, layout);
            break;
          case 'species':
            let species_list = [];
            let species_data = [];
            for (i = 0; i < data.length; i++) {
              if (species_list.indexOf(data[i][10]) == -1) {
                if (species_list.length < 9) {
                  species_list.push(data[i][10]);
                } else {
                  species_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < species_list.length; j++) {
              if (j < 9) {
                let blob = species_list[j];
                let name = species_list[j] + '_name';
                let length = species_list[j] + '_length';
                let gc = species_list[j] + '_gc';
                let cov = species_list[j] + '_cov';
                let total_length = species_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][10] == species_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: species_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                species_data.push(window[blob]);
              } else {
                let other_blob = species_list[j];
                let other_name = species_list[j] + '_name';
                let other_length = species_list[j] + '_length';
                let other_gc = species_list[j] + '_gc';
                let other_cov = species_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (species_list.indexOf(data[v][10]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: species_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                species_data.push(window[other_blob]);
              }
            }
            species_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, species_data, layout);
            break;
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
          let blob_picture = document.getElementById('blob_pic');
          let data = res.data;
          switch ($('#blob_classify option:selected').val()) {
          case 'superkingdom':
            let superkingdom_list = [];
            let superkingdom_data = [];
            for (i = 0; i < data.length; i++) {
              if (superkingdom_list.indexOf(data[i][5]) == -1) {
                if (superkingdom_list.length < 9) {
                  superkingdom_list.push(data[i][5]);
                } else {
                  superkingdom_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < superkingdom_list.length; j++) {
              if (j < 9) {
                let blob = superkingdom_list[j];
                let name = superkingdom_list[j] + '_name';
                let length = superkingdom_list[j] + '_length';
                let gc = superkingdom_list[j] + '_gc';
                let cov = superkingdom_list[j] + '_cov';
                let total_length = superkingdom_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][5] == superkingdom_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: superkingdom_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                superkingdom_data.push(window[blob]);
              } else {
                let other_blob = superkingdom_list[j];
                let other_name = superkingdom_list[j] + '_name';
                let other_length = superkingdom_list[j] + '_length';
                let other_gc = superkingdom_list[j] + '_gc';
                let other_cov = superkingdom_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (superkingdom_list.indexOf(data[v][5]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: superkingdom_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                superkingdom_data.push(window[other_blob]);
              }
            }
            superkingdom_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, superkingdom_data, layout);
            break;
          case 'phylum':
            let phylum_list = [];
            let phylum_data = [];
            for (i = 0; i < data.length; i++) {
              if (phylum_list.indexOf(data[i][6]) == -1) {
                if (phylum_list.length < 9) {
                  phylum_list.push(data[i][6]);
                } else {
                  phylum_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < phylum_list.length; j++) {
              if (j < 9) {
                let blob = phylum_list[j];
                let name = phylum_list[j] + '_name';
                let length = phylum_list[j] + '_length';
                let gc = phylum_list[j] + '_gc';
                let cov = phylum_list[j] + '_cov';
                let total_length = phylum_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][6] == phylum_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: phylum_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                phylum_data.push(window[blob]);
              } else {
                let other_blob = phylum_list[j];
                let other_name = phylum_list[j] + '_name';
                let other_length = phylum_list[j] + '_length';
                let other_gc = phylum_list[j] + '_gc';
                let other_cov = phylum_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (phylum_list.indexOf(data[v][6]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: phylum_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                phylum_data.push(window[other_blob]);
              }
            }
            phylum_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, phylum_data, layout);
            break;
          case 'order':
            let order_list = [];
            let order_data = [];
            for (i = 0; i < data.length; i++) {
              if (order_list.indexOf(data[i][7]) == -1) {
                if (order_list.length < 9) {
                  order_list.push(data[i][7]);
                } else {
                  order_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < order_list.length; j++) {
              if (j < 9) {
                let blob = order_list[j];
                let name = order_list[j] + '_name';
                let length = order_list[j] + '_length';
                let gc = order_list[j] + '_gc';
                let cov = order_list[j] + '_cov';
                let total_length = order_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][7] == order_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: order_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                order_data.push(window[blob]);
              } else {
                let other_blob = order_list[j];
                let other_name = order_list[j] + '_name';
                let other_length = order_list[j] + '_length';
                let other_gc = order_list[j] + '_gc';
                let other_cov = order_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (order_list.indexOf(data[v][7]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: order_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                order_data.push(window[other_blob]);
              }
            }
            order_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, order_data, layout);
            break;
          case 'family':
            let family_list = [];
            let family_data = [];
            for (i = 0; i < data.length; i++) {
              if (family_list.indexOf(data[i][8]) == -1) {
                if (family_list.length < 9) {
                  family_list.push(data[i][8]);
                } else {
                  family_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < family_list.length; j++) {
              if (j < 9) {
                let blob = family_list[j];
                let name = family_list[j] + '_name';
                let length = family_list[j] + '_length';
                let gc = family_list[j] + '_gc';
                let cov = family_list[j] + '_cov';
                let total_length = family_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][8] == family_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: family_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                family_data.push(window[blob]);
              } else {
                let other_blob = family_list[j];
                let other_name = family_list[j] + '_name';
                let other_length = family_list[j] + '_length';
                let other_gc = family_list[j] + '_gc';
                let other_cov = family_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (family_list.indexOf(data[v][8]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: family_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                family_data.push(window[other_blob]);
              }
            }
            family_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, family_data, layout);
            break;
          case 'genus':
            let genus_list = [];
            let genus_data = [];
            for (i = 0; i < data.length; i++) {
              if (genus_list.indexOf(data[i][9]) == -1) {
                if (genus_list.length < 9) {
                  genus_list.push(data[i][9]);
                } else {
                  genus_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < genus_list.length; j++) {
              if (j < 9) {
                let blob = genus_list[j];
                let name = genus_list[j] + '_name';
                let length = genus_list[j] + '_length';
                let gc = genus_list[j] + '_gc';
                let cov = genus_list[j] + '_cov';
                let total_length = genus_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][9] == genus_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: genus_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                genus_data.push(window[blob]);
              } else {
                let other_blob = genus_list[j];
                let other_name = genus_list[j] + '_name';
                let other_length = genus_list[j] + '_length';
                let other_gc = genus_list[j] + '_gc';
                let other_cov = genus_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (genus_list.indexOf(data[v][9]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: genus_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                genus_data.push(window[other_blob]);
              }
            }
            genus_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, genus_data, layout);
            break;
          case 'species':
            let species_list = [];
            let species_data = [];
            for (i = 0; i < data.length; i++) {
              if (species_list.indexOf(data[i][10]) == -1) {
                if (species_list.length < 9) {
                  species_list.push(data[i][10]);
                } else {
                  species_list.push('others');
                  break;
                }
              }
            }
            for (j = 0; j < species_list.length; j++) {
              if (j < 9) {
                let blob = species_list[j];
                let name = species_list[j] + '_name';
                let length = species_list[j] + '_length';
                let gc = species_list[j] + '_gc';
                let cov = species_list[j] + '_cov';
                let total_length = species_list[j] + '_total_length';
                window[total_length] = 0;
                window[name] = [];
                window[length] = [];
                window[gc] = [];
                window[cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (data[v][10] == species_list[j]) {
                    window[name].push(data[v][0]);
                    window[length].push(data[v][1]);
                    window[gc].push(data[v][2]);
                    window[cov].push(data[v][4]);
                    window[total_length] += parseInt(data[v][1]);
                  }
                }
                window[length] = window[length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[blob] = {
                  x: window[gc],
                  y: window[cov],
                  text: window[name],
                  name: species_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[length],
                  },
                  total_length: window[total_length]
                }
                species_data.push(window[blob]);
              } else {
                let other_blob = species_list[j];
                let other_name = species_list[j] + '_name';
                let other_length = species_list[j] + '_length';
                let other_gc = species_list[j] + '_gc';
                let other_cov = species_list[j] + '_cov';
                window[other_name] = [];
                window[other_length] = [];
                window[other_gc] = [];
                window[other_cov] = [];
                for (v = 0; v < data.length; v++) {
                  if (species_list.indexOf(data[v][10]) == -1) {
                    window[other_name].push(data[v][0]);
                    window[other_length].push(data[v][1]);
                    window[other_gc].push(data[v][2]);
                    window[other_cov].push(data[v][4]);
                  }
                }
                window[other_length] = window[other_length].map(function (i) {
                  return (Math.log(i) / Math.log(2)) * 3;
                })
                window[other_blob] = {
                  x: window[other_gc],
                  y: window[other_cov],
                  text: window[other_name],
                  name: species_list[j],
                  mode: 'markers',
                  marker: {
                    size: window[other_length],
                  },
                  total_length: 0
                }
                species_data.push(window[other_blob]);
              }
            }
            species_data.sort(compare('total_length'));
            var layout = {
              yaxis: {
                type: 'log',
                exponentformat: 'E'
              },
              width: 900,
              height: 900
            }
            Plotly.newPlot(blob_picture, species_data, layout);
            break;
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
    }
  }
})
