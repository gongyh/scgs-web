$(function () {

  $('.home-slider').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: true,
    arrows: true,
    lazyLoad: 'ondemand',
    autoplay: true,
    autoplaySpeed: 5000,
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
  })
  /*
    $('.owl-carousel').owlCarousel({
      loop: true,
      margin: 10,
      nav: false,
      dots: true,
      lazyLoad: true,
      lazyLoadEager: 1,
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 2
        },
        1000: {
          items: 4
        }
      }
    })
  */
  var index = 0;
  var current_url = window.location.href;
  var url = current_url.split('/').pop();
  var url_noparams = url.indexOf('?') != -1 ? url.slice(0, url.indexOf('?')) : url;
  var workspace_nav = document.getElementsByClassName('workspace-nav');
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
  var running_sample_id = getQueryVariable('sampleID');
  var running_project_id = getQueryVariable('projectID');
  var selectedTypes = [];
  //   var check_progress = false;
  //   var read_progress;

  $('#type').on('change', function () {
    var type = $('#type').val();
    $('#new_type').val(type);
  });

  $('#datetimepicker,#datetimepicker1,#datetimepicker3,#datetimepicker4').datetimepicker({
    format: 'L'
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
   * Get url params
   */
  function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
  }

  /**
   * Convert task start time to year:month:second
   */
  function Sec2Time(time) {
    let datetime = new Date(time).getTime();
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

  /**
   * Pairend control upload files number
   */
  if ($("input[type='radio']:checked").val() == 'Single') {
    $(".file_two").hide();
  } else {
    $(".file_two").show();
  }

  $("input[type = 'radio']").change(function () {
    if ($("input[type='radio']:checked").val() == 'Single') {
      $(".file_two").hide();
    } else {
      $(".file_two").show();
    }
  })

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

  /**
   * Execute params setting
   */
  const db_list = ['resfinder_db', 'nt_db', 'eggnog_db', 'kraken_db', 'kofam_profile', 'kofam_kolist', 'eukcc_db'];
  for (let i = 0; i < db_list.length; i++) {
    if ($('#' + db_list[i]).is(':checked')) {
      $('.' + db_list[i] + '_path').show();
    } else {
      $('.' + db_list[i] + '_path').hide();
    }
  }

  if ($('#genus').is(':checked')) {
    $('.genus_name').show();
  } else {
    $('.genus_name').hide();
  }

  if ($('#augustus_species').is(':checked')) {
    $('.augustus_species_name').show();
  } else {
    $('.augustus_species_name').hide();
  }

  $('#genus').on('change', function () {
    if ($('#genus').is(':checked')) {
      $('.genus_name').show();
    } else {
      $('.genus_name').hide();
    }
  })
  $('#augustus_species').on('change', function () {
    if ($('#augustus_species').is(':checked')) {
      $('.augustus_species_name').show();
    } else {
      $('.augustus_species_name').hide();
    }
  })
  $('#resfinder_db').on('change', function () {
    if ($('#resfinder_db').is(':checked')) {
      $('.resfinder_db_path').show();
    } else {
      $('.resfinder_db_path').hide();
    }
  })
  $('#nt_db').on('change', function () {
    if ($('#nt_db').is(':checked')) {
      $('.nt_db_path').show();
    } else {
      $('.nt_db_path').hide();
    }
  })
  $('#eggnog_db').on('change', function () {
    if ($('#eggnog_db').is(':checked')) {
      $('.eggnog_db_path').show();
    } else {
      $('.eggnog_db_path').hide();
    }
  })
  $('#kraken_db').on('change', function () {
    if ($('#kraken_db').is(':checked')) {
      $('.kraken_db_path').show();
    } else {
      $('.kraken_db_path').hide();
    }
  })
  $('#kofam_profile').on('change', function () {
    if ($('#kofam_profile').is(':checked')) {
      $('.kofam_profile_path').show();
    } else {
      $('.kofam_profile_path').hide();
    }
  })
  $('#kofam_kolist').on('change', function () {
    if ($('#kofam_kolist').is(':checked')) {
      $('.kofam_kolist_path').show();
    } else {
      $('.kofam_kolist_path').hide();
    }
  })
  $('#eukcc_db').on('change', function () {
    if ($('#eukcc_db').is(':checked')) {
      $('.eukcc_db_path').show();
    } else {
      $('.eukcc_db_path').hide();
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
            let data = res.data;
            let div = $('<div></div>');
            for (let i = 0; i < data.length; i++) {
              let insert_message = "<div class=\"rem1\">" + i + "  [" + data[i].process + "] " + data[i].event + " " + data[i].utcTime + "</div>";
              div.append(insert_message);
            }
            $('.command_out').html(div);
          } else {
              let msg = 'pipeline is preparing...';
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
            let data = res.data;
            let div = $('<div></div>');
            for (let i = 0; i < data.length; i++) {
              let insert_message = "<span class=\"rem1\">[" + data[i].process + "] " + data[i].event + " " + data[i].utcTime + "</span>";
              div.append(insert_message);
            }
            $('.command_out').html(div);
          } else {
            let msg = 'pipeline is preparing...';
            $('.command_out').text(msg);
          }
        }
      })
    }
  }

  /**
   * Check Progress to read weblog
   */
  //   $(".detail").on('click', function (e) {
  //     e.preventDefault();
  //     check_progress = !check_progress;
  //     if (check_progress) {
  //       $('.command_out').removeClass('d-none');
  //       read_progress = setInterval(() => {
  //         read_weblog();
  //       }, 5000);
  //     } else {
  //       $('.command_out').addClass('d-none');
  //       clearInterval(read_progress);
  //     }
  //   })


  $('.sample_files_save').on('click', function () {
    if ($('input[type=checkbox]:checked').length > 2) {
      window.alert('Too many sample files are chosen');
    } else if ($('input[type=checkbox]:checked').length == 2) {
      $('#new_fileOne').val($('input[type=checkbox]:checked')[0].value);
      $('#new_fileTwo').val($('input[type=checkbox]:checked')[1].value);
      $('#addFileModal').modal('hide');
    } else {
      $('#new_fileOne').val($('input[type=checkbox]:checked')[0].value);
      $('#addFileModal').modal('hide');
    }
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
    }, 5000);
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
        console.log('incorrect file type!');
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
      allowedFileExtensions: ['fasta.gz', 'fastq.gz', 'fasta', 'fastq', 'fa', 'fq'],
      showUpload: true,
      showCaption: true,
      dropZoneEnabled: true,
      elCaptionText: 'Upload Files',
      browseClass: "btn btn-primary",
      uploadClass: "btn btn-success",
      maxFileCount: 5,
      enctype: 'multipart/form-data',
      validateInitialCount: true,
      previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
      msgFilesTooMany: "You have uploaded ({n}) files, but max file count is {m}！",
    });

    //Event after upload file
    control.on("fileuploaded", function (event, data, previewId, index) {
      $("#" + ctrlName).modal("hide");
      if (data.code == 200) {
        console.log(data);
        return;
      }
    });
  }
  return oFile;
};
