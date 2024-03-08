//const { head } = require("lodash");
var utils = require('./utils.js');

import { indexOf } from "lodash";
import { monthsShort } from "moment";

$(function () {

    $('.home-slider').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        lazyLoad: 'ondemand',
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [{
            breakpoint: 1000,
            settings: {
              slidesToShow: 3
            }
           }, {
            breakpoint: 600,
            settings: {
              slidesToShow: 2
            }
           }, {
            breakpoint: 0,
            settings: {
              slidesToShow: 1
            }
           }]
      });
//   $('.owl-carousel').owlCarousel({
//     loop: true,
//     margin: 10,
//     nav: false,
//     dots: true,
//     lazyLoad: true,
//     lazyLoadEager: 1,
//     autoplay: true,
//     autoplayHoverPause: true,
//     responsive: {
//       0: {
//         items: 1
//       },
//       600: {
//         items: 2
//       },
//       1000: {
//         items: 4
//       }
//     }
//   })
  var index = 0;
  var current_url = window.location.href;
  var url = current_url.split('/').pop();
  var url_noparams = url.indexOf('?') != -1 ? url.slice(0, url.indexOf('?')) : url;
  var workspace_nav = document.getElementsByClassName('workspace-nav');
  var create_sample_form = document.getElementById('create_sample_form');
  var sample_url = window.location.href;
  var projectID_pos = sample_url.indexOf('projectID');
  if (projectID_pos != -1) {
    var projectID = sample_url.substr(projectID_pos);
  } else {
    var projectID = '';
  }

  sample_url = '/samples/upload?' + projectID;
  var sample_file = FileInput();
  sample_file.Init('sample_file', sample_url);
  var species_file = FileInput();
  species_file.Init('species_file', '/workspace/species/upload');
  var add_sample_files = sampleFileInput();
  add_sample_files.Init('addSampleFiles', '/workspace/addSampleFiles/upload');
  var running_sample_id = utils.getQueryVariable('sampleID');
  var running_project_id = utils.getQueryVariable('projectID');
  var selectedTypes = [];
  //   var check_progress = false;
  //   var read_progress;

  if (current_url.endsWith('/projects') || current_url.endsWith('/myProject')) {
    $('.layui-footer').hide();
  }

  $('#type').on('change', function () {
    var type = $('#type').val();
    $('#new_type').val(type);
  });

  // collection date/release date datetimepicker init
  $('#cDate').datetimepicker(
  {
    timepicker: false,
    format: 'Y-m-d'
  });

  $('#rDate').datetimepicker(
  {
    value: utils.getNextYear(),
    timepicker: false,
    format: 'Y-m-d'
  });

  $('#rDate_m, #cDate_m').datetimepicker({
    timepicker: false,
    format: 'Y-m-d'
  });

  $('.type').on('click', function () {
    $('#new_type').val($(this).text());
  })

  $('.file_one_add').on('click', function () {
    var file_one_add = $(this).text();
    $('#new_fileOne').attr('value', file_one_add);
  });

  $('.file_two_add').on('click', function () {
    var file_two_add = $(this).text();
    $('#new_fileTwo').attr('value', file_two_add);
  });

  $('.file_one_update').on('click', function () {
    var file_one_update = $(this).text();
    $('#fileOne').attr('value', file_one_update);
  });

  $('.file_two_update').on('click', function () {
    var file_two_update = $(this).text();
    $('#fileTwo').attr('value', file_two_update);
  });

  if (url) {
    for (var i = 0; i < workspace_nav.length; i++) {
      if (workspace_nav[i].getAttribute('href').split('/').pop().indexOf(url_noparams) != -1) {
        index = i;
        workspace_nav[index].className = 'nav-item nav-link workspace-nav active';
        break;
      }
    }
  }

  $('div.flash_msg').not('.warning').delay(1500).fadeOut(350);

  //sra_id_file upload filename display
  $('#sra_id_file').on('change', function (file) {
    $("#sra_id_label").html(this.files[0].name);
  })

  text_folded('.proj_desc', 200);
  $('.start_time').each(function () {
    var start_time = $(this).text();
    var start_time = parseInt(start_time) * 1000;
    var start_time = Sec2Time(start_time);
    $(this).text(start_time);
  })

  $('.finish_time').each(function () {
    var finish_time = $(this).text();
    var finish_time = parseInt(finish_time) * 1000;
    var finish_time = Sec2Time(finish_time);
    $(this).text(finish_time);
  })

  $('.Run_time').each(function () {
    var run_time = $(this).text();
    var run_time = parseInt(run_time) * 1000;
    var hours = Math.floor(run_time / 3600000);
    var minutes = Math.floor((run_time % 3600000) / 60000);
    var seconds = Math.floor((run_time - hours * 3600 * 1000 - minutes * 60 * 1000) / 1000);
    var run_time = hours + ':' + minutes + ':' + seconds;
    $(this).text(run_time);
  })

  /**
   *
   */
  //   $('.search-sample').on('input', function (e) {
  //     projectID = getQueryVariable('projectID')
  //     axios.post('/samples/search', {
  //         identity: this.value,
  //         projectID: projectID
  //       })
  //       .then(function (response) {
  //         if (response.data.tag) {
  //           $('.sample_list').show()
  //         } else {
  //           if (response.data.files.length != 0) {
  //             var list = response.data.files
  //             console.log(list)
  //             for (var i = 0; i < list.length; i++) {
  //               var insert_list = '';
  //               insert_list += ' <input type=\"checkbox\" id="' + list[i] + '" value="' + list[i] + '" title="' + list[i] + '" />'
  //             }
  //             var insert_text = '<label class=\"layui-form-label\">File</label><div class=\"layui-input-block\"><input type=\"checkbox\">123</input></div>';
  //             var insert = '<label class=\"layui-form-label\">File</label><div class=\"layui-input-block\"></div>';
  //             $('.sample_list').empty()
  //             $('.sample_list').html(insert_text)
  //           } else {
  //             $('.sample_list').hide()
  //           }
  //         }
  //       })
  //       .catch(function (error) {
  //         console.log(error);
  //       });
  //   })

  /**
   * Sample platform selected change instrument model options
   */
  $("#platform").change(function () {
    switch ($(this).children("option:selected").val()) {
    case "_LS454":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Helicos,.ABI_SOLID,.Complete_genomics,.Pacbio_smrt,.Ion_torrent,.Capillary,.Oxford_nanopore,.BgiSeq").hide();
      break;
    case "ABI_SOLID":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Helicos,._LS454,.Complete_genomics,.Pacbio_smrt,.Ion_torrent,.Capillary,.Oxford_nanopore,.BgiSeq").hide();
      break;
    case "BGISEQ":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Helicos,._LS454,.Complete_genomics,.Pacbio_smrt,.Ion_torrent,.Capillary,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "CAPILLARY":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Helicos,._LS454,.Complete_genomics,.Pacbio_smrt,.Ion_torrent,.BgiSeq,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "COMPLETE_GENOMICS":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Helicos,._LS454,.Capillary,.Pacbio_smrt,.Ion_torrent,.BgiSeq,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "HELICOS":
      $("#instrument_model").children("option").show();
      $(".Illumina,.Complete_genomics,._LS454,.Capillary,.Pacbio_smrt,.Ion_torrent,.BgiSeq,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "ILLUMINA":
      $("#instrument_model").children("option").show();
      $(".Helicos,.Complete_genomics,._LS454,.Capillary,.Pacbio_smrt,.Ion_torrent,.BgiSeq,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "ION_TORRENT":
      $("#instrument_model").children("option").show();
      $(".Helicos,.Complete_genomics,._LS454,.Capillary,.Pacbio_smrt,.Illumina,.BgiSeq,.Oxford_nanopore,.ABI_SOLID").hide();
      break;
    case "OXFORD_NANOPORE":
      $("#instrument_model").children("option").show();
      $(".Helicos,.Complete_genomics,._LS454,.Capillary,.Pacbio_smrt,.Illumina,.BgiSeq,.Ion_torrent,.ABI_SOLID").hide();
      break;
    case "PACBIO_SMRT":
      $("#instrument_model").children("option").show();
      $(".Helicos,.Complete_genomics,._LS454,.Capillary,.Oxford_nanopore,.Illumina,.BgiSeq,.Ion_torrent,.ABI_SOLID").hide();
      break;
    }
  })

  /**
   * Workspace-nav selected shadow
   */
  $('.workspace-nav').hover(function () {
    $(this).addClass('shadow p-3 rounded');
  }, function () {
    $(this).removeClass('shadow p-3 rounded');
  })
  $('.nav-menu').hover(function () {
    $(this).addClass('shadow rounded');
  }, function () {
    $(this).removeClass('shadow rounded');
  })

  if ($('#nanopore_1').is(':checked')) {
    $('#cnv_2').prop('checked', true);
    $('#snv_2').prop('checked', true);
    $('#cnv_1').attr('disabled', 'disabled');
    $('#snv_1').attr('disabled', 'disabled');
  } else {}

  if ($('#genus_1').is(':checked')) {
    $('.genus_name').show();
  } else {
    $('.genus_name').hide();
  }

  if ($('#euk_1').is(':checked')) {
    $('.euk_true').show();
    $('.euk_false').hide();
  } else {
    $('.euk_true').hide();
    $('.euk_false').show();
  }

  if ($('#split_1').is(':checked')) {
    $('.split_true').show();
  } else {
    $('.split_true').hide();
  }

  if ($('#augustus_species_1').is(':checked')) {
    $('.augustus_species_name').show();
  } else {
    $('.augustus_species_name').hide();
  }

  $('.nanopore').on('change', function () {
    if ($('#nanopore_1').is(':checked')) {
      $('#cnv_2').prop('checked', true);
      $('#snv_2').prop('checked', true);
      $('#cnv_1').attr('disabled', 'disabled');
      $('#snv_1').attr('disabled', 'disabled');
    } else {
      $('#cnv_1').removeAttr('disabled');
      $('#snv_1').removeAttr('disabled');
      $('#cnv_1').prop('checked', true);
      $('#snv_1').prop('checked', true);
    }
  })

  $('.genus').on('change', function () {
    if ($('#genus_1').is(':checked')) {
      $('.genus_name').show();
    } else {
      $('.genus_name').hide();
    }
  })

  $('.euk').on('change', function () {
    if ($('#euk_1').is(':checked')) {
      $('.euk_true').show();
      $('.euk_false').hide();
      $('#eukcc_db_1').prop('checked', true);
    } else {
      $('.euk_true').hide();
      $('.euk_false').show();
      $('#eukcc_db_2').prop('checked', true);
    }
  })

  $('.split').on('change', function () {
    if ($('#split_1').is(':checked')) {
      $('.split_true').show();
    } else {
      $('.split_true').hide();
    }
  })

  $('.augustus_species').on('change', function () {
    if ($('#augustus_species_1').is(':checked')) {
      $('.augustus_species_name').show();
    } else {
      $('.augustus_species_name').hide();
    }
  })

  /**
   * Read .nextflow.log
   */
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function read_weblog() {
    if (window.location.href.indexOf('sampleID') != -1) {
      $.ajax({
        url: "/execute/start/status",
        type: 'POST',
        data: {
          'running_sample_id': running_sample_id,
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var weblogs = res.data.weblogs;
            var runStatus = res.data.runStatus;
            var div = $('<div></div>');
            for (var i = 0; i < weblogs.length; i++) {
              var insert_message = "<div class=\"rem1\">" + weblogs[i].utcTime + "  [" + weblogs[i].process + "] " + weblogs[i].event + "</div>";
              div.append(insert_message);
            }
            if (runStatus == 0) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              var span_dot = $('<span></span>');
              span_wrapper.addClass('badge badge-warning');
              span_inner.text('waiting');
              span_dot.text('...');
              span_dot.addClass('dot');
              span_wrapper.append(span_inner);
              span_wrapper.append(span_dot);
              $('.pipeline_status').html(span_wrapper);
            } else if (runStatus == 1) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              var span_dot = $('<span></span>');
              span_wrapper.addClass('badge badge-info');
              span_inner.text('running');
              span_dot.text('...');
              span_dot.addClass('dot');
              span_wrapper.append(span_inner);
              span_wrapper.append(span_dot);
              $('.pipeline_status').html(span_wrapper);
            } else if (runStatus == 2) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              span_wrapper.addClass('badge badge-danger');
              span_inner.text('failed');
              span_wrapper.append(span_inner);
              $('.pipeline_status').html(span_wrapper);
            } else {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              span_wrapper.addClass('badge badge-success');
              span_inner.text('success');
              span_wrapper.append(span_inner);
              $('.pipeline_status').html(span_wrapper);
            }
            $('.command_out').html(div);
          } else {
            var msg = 'pipeline is preparing...';
            $('.command_out').text(msg);
          }
        }
      })
    } else {
      $.ajax({
        url: "/execute/start/status",
        type: 'POST',
        data: {
          'running_project_id': running_project_id,
        },
        dataType: 'json',
        success: function (res) {
          if (res.code == 200) {
            var weblogs = res.data.weblogs;
            var runStatus = res.data.runStatus;
            var div = $('<div></div>');
            for (var i = 0; i < weblogs.length; i++) {
              var insert_message = "<div class=\"rem1\">" + weblogs[i].utcTime + "  [" + weblogs[i].process + "] " + weblogs[i].event + "</div>";
              div.append(insert_message);
            }
            if (runStatus == 0) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              var span_dot = $('<span></span>');
              span_wrapper.addClass('badge badge-warning');
              span_inner.text('waiting');
              span_dot.text('...');
              span_dot.addClass('dot');
              span_wrapper.append(span_inner);
              span_wrapper.append(span_dot);
              $('.pipeline_status').html(span_wrapper);
            } else if (runStatus == 1) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              var span_dot = $('<span></span>');
              span_wrapper.addClass('badge badge-info');
              span_inner.text('running');
              span_dot.text('...');
              span_dot.addClass('dot');
              span_wrapper.append(span_inner);
              span_wrapper.append(span_dot);
              $('.pipeline_status').html(span_wrapper);
            } else if (runStatus == 2) {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              span_wrapper.addClass('badge badge-danger');
              span_inner.text('failed');
              span_wrapper.append(span_inner);
              $('.pipeline_status').html(span_wrapper);
            } else {
              var span_wrapper = $('<span></span>');
              var span_inner = $('<span></span>');
              span_wrapper.addClass('badge badge-success');
              span_inner.text('success');
              span_wrapper.append(span_inner);
              $('.pipeline_status').html(span_wrapper);
            }
            $('.command_out').html(div);
          } else {
            var msg = 'pipeline is preparing...';
            $('.command_out').text(msg);
          }
        }
      })
    }
  }

  /**
   * Check Progress to read weblog
   *
   *
   * $(".detail").on('click', function (e) {
      e.preventDefault();
      check_progress = !check_progress;
      if (check_progress) {
        $('.command_out').removeClass('d-none');
        read_progress = setInterval(() => {
          read_weblog();
        }, 5000);
      } else {
        $('.command_out').addClass('d-none');
        clearInterval(read_progress);
      }
    })
   */

  $('.sample_files_save').on('click', function () {
    $('#new_fileOne').val($('#new_file1 option:selected').text());
    $('#new_fileTwo').val($('#new_file2 option:selected').text());
    $('#addFileModal').modal('hide');
  })

  //select types
  $('#search_type').on('change', function (e) {
    $("#search_type option:selected").each(function () {
      selectedTypes.push($(this).val());
    });
  })

  /**
   * jQuery text fold/unfold
   * @param clas：text container
   * @param num：font number
   */

  function text_folded(clas, num) {
    var num = num;
    var a_unfold = $("<a></a>").on('click', showText).addClass('text-primary').text("[spread]");
    var a_fold = $("<a></a>").on('click', showText).addClass('text-primary').text("[fold]");
    var div = $("<div></div>").addClass('project_desc_hidden');
    var str = $(clas).text();
    $(clas).after(div);
    if (str.length > num) {
      var text = str.substring(0, num) + "...";
      $(clas).html(text).append(a_unfold);
    }
    $('.project_desc_hidden').html(str).append(a_fold);

    function showText() {
      $(this).parent().hide().siblings().show();
    }
  }

  //read weblog
  if (window.location.href.indexOf('/execute/start') != -1) {
    var weblogReader = setInterval(() => {
      read_weblog();
    }, 10000);
  } else {
    clearInterval(weblogReader);
  }


  //ncbi-download status
  if (window.location.href.indexOf('/workspace/addSampleFiles') != -1) {
    var getPreparingList = setInterval(() => {
      $.ajax({
        url: '/workspace/ncbi_download_status',
        method: 'GET',
        success: function (res) {
          if (res.code == 200) {
            if (res.data.length != 0) {
              var span1 = $("<span></span>");
              var span2 = $("<span></span>");
              var span3 = $("<span></span>");
              var div1 = $("<div></div>");
              var div2 = $("<div></div>");
              var div4 = $("<div></div>");
              span1.addClass('badge badge-primary');
              span2.text('Upload');
              span3.addClass('dot');
              span3.text('...');
              span1.append(span2);
              span1.append(span3);
              div1.append(span1);
              for (i = 0; i < res.data.length; i++) {
                var div3 = $("<div></div>");
                div3.addClass('rem1');
                div3.text(res.data[i]);
                div2.append(div3);
              }
              div4.append(div1);
              div4.append(div2);
              $("#preparing_list").html(div4);
            } else {
              $("#preparing_list").empty();
            }
          }
        }
      })
    }, 2000);
  } else {
    clearInterval(getPreparingList);
  }
})

var dateToString = function (date) {
  var year = date.getFullYear();
  var month = (date.getMonth() + 1).toString();
  var day = (date.getDate()).toString();
  if (month.length == 1) {
    month = "0" + month;
  }
  if (day.length == 1) {
    day = "0" + day;
  }
  var dateTime = year + "-" + month + "-" + day;
  return dateTime;
}

/**
 * Convert task start time to year:month:second
 */
function Sec2Time(time) {
  var datetime = new Date(time).getTime();
  var date = new Date(datetime);
  var y = date.getFullYear();
  var m = date.getMonth() + 1;
  m = m < 10 ? ('0' + m) : m;
  var d = date.getDate();
  d = d < 10 ? ('0' + d) : d;
  var h = date.getHours();
  h = h < 10 ? ('0' + h) : h;
  var minute = date.getMinutes();
  var second = date.getSeconds();
  minute = minute < 10 ? ('0' + minute) : minute;
  second = second < 10 ? ('0' + second) : second;
  return y + '-' + m + '-' + d + ' ' + ' ' + h + ':' + minute + ':' + second;
}

var FileInput = function () {
  var oFile = new Object();

  //Init fileInput
  oFile.Init = function (ctrlName, uploadUrl) {
    var control = $('#' + ctrlName);

    //Init fileUpload
    control.fileinput({
      uploadUrl: uploadUrl,
      allowedFileExtensions: ['xls', 'xlsx'],
      showUpload: true,
      showCaption: true,
      browseClass: "btn btn-primary",
      maxFileCount: 10,
      enctype: 'multipart/form-data',
      validateInitialCount: true,
      previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
      msgFilesTooMany: "You have uploaded ({n}) files, but max file count is {m}！",
    });

    //Event after upload file
    control.on("fileuploaded", function (event, data, previewId, index) {
      $("#myModal_" + ctrlName).modal("hide");
      if (data == undefined) {
        return;
      }
      location.reload();
    });
  }
  return oFile;
};

var sampleFileInput = function () {
  var oFile = new Object();

  //Init fileInput
  oFile.Init = function (ctrlName, uploadUrl) {
    var control = $('#' + ctrlName);

    //Init fileUpload
    control.fileinput({
      uploadUrl: uploadUrl,
      allowedFileExtensions: ['gz', 'fastq', 'fq'],
      notAllowedFilenameExtensions: ['\\s+', '^((?!(_1\.fastq\.gz)?(_2\.fastq\.gz)?(_1\.fq\.gz)?(_2\.fq\.gz)?(_1\.fq)?(_2\.fq)?(_R1\.fastq\.gz)?(_R2\.fastq\.gz)?(_R1\.fq\.gz)?(_R2\.fq\.gz)?(_R1\.fq)?(_R2\.fq)?$).)*$'],
      showUpload: true,
      showCaption: true,
      dropZoneEnabled: true,
      elCaptionText: 'Upload Files',
      maxFileCount: 5,
      enctype: 'multipart/form-data',
      validateInitialCount: true,
      previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
      msgFilesTooMany: "You have uploaded ({n}) files, but max file count is {m}!",
    });

    //Event after upload file
    control.on("fileuploaded", function (event, data, previewId, index) {
      $("#" + ctrlName).modal("hide");
      if (data.code == 200) {
        return;
      }
    });
  }
  return oFile;
};
