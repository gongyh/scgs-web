$(function () {
    var index = 0;
    var current_url = window.location.href;
    var url = current_url.split('/').pop();
    var url_noparams = url.indexOf('?') != -1 ? url.slice(0, url.indexOf('?')) : url;
    var workspace_nav = document.getElementsByClassName('workspace-nav');
    var iframe = document.getElementsByTagName('iframe')[0];
    var MultiQC = document.getElementById('v-pills-multiqc-tab');
    var proj_MultiQC = document.getElementById('v-pills-proj-multiqc-tab');
    var krona_tab = document.getElementById('v-pills-krona-tab');
    var blob_tab = document.getElementById('v-pills-blob-tab');
    var image_blob_tabs = $('#blob_tabs li a');
    var krona = document.createElement('iframe');
    var iframe_krona_tabs = $('#kraken_tabs li a');
    if(window.location.href.indexOf('sampleID') != -1){
        var krona_src = 'results/' + $('.iframe_sample_user').text() + '/' + $('.iframe_sample_uuid').text() + '/kraken/' + $('.iframe_sample_name').text() + '.krona.html';
        krona.setAttribute('src',krona_src);
        krona.setAttribute('class','embed-responsive-item');
        if(krona_tab != null){
            krona_tab.onclick = function(){
              var kraken_report = document.getElementsByClassName('kraken_report')[0];
              kraken_report.appendChild(krona);
            }
        }
    }else{
        var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + iframe_krona_tabs.first().text() + '.krona.html';
        var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + image_blob_tabs.first().text() + '/' + image_blob_tabs.first().text() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
        krona.setAttribute('src',krona_src);
        krona.setAttribute('class','embed-responsive-item');
        if(krona_tab != null){
            krona_tab.onclick = function(){
                $('#kraken_tabs li').first().addClass('active');
                var kraken_report = document.getElementsByClassName('kraken_report')[0];
                kraken_report.appendChild(krona);
            }
        }
        if(blob_tab != null){
            blob_tab.onclick = function(){
                $('#blob_tabs li').first().addClass('active');
                $('#blob_image').attr('src',blob_src);
            }
        }

        iframe_krona_tabs.on('click',function(e){
            e.preventDefault();
            $('#kraken_tabs li').removeClass('active');
            $(this).parent().addClass('active');
            var krona_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/kraken/' + $(this).text() + '.krona.html';
            krona.setAttribute('src',krona_src);
            krona.setAttribute('class','embed-responsive-item');
            $('#kraken_report').empty();
            $('#kraken_report').append(krona);
        })

        image_blob_tabs.on('click',function(e){
            e.preventDefault();
            $('#blob_tabs li').removeClass('active');
            $(this).parent().addClass('active');
            var blob_src = 'results/' + $('.iframe_project_user').text() + '/' + $('.iframe_project_uuid').text() + '/blob/' + $(this).text() + '/' + $(this).text() + '.blobDB.json.bestsum.family.p7.span.200.blobplot.spades.png';
            $('#blob_image').attr('src',blob_src);
        })
    }

    var sample_url = window.location.href;
    var projectID_pos = sample_url.indexOf('projectID');
    if(projectID_pos != -1){
        var projectID = sample_url.substr(projectID_pos);
    }else{
        var projectID = '';
    }

    sample_url = '/samples/upload?' + projectID;
    var sample_file = FileInput();
    sample_file.Init('sample_file',sample_url);
    var species_file = FileInput();
    species_file.Init('species_file','/workspace/species/upload');
    var running_sample_id = getQueryVariable('sampleID');
    var running_project_id = getQueryVariable('projectID');
    var check_progress = false;
    var check_proj_progress = false;
    var read_progress,read_proj_progress;


    if(MultiQC != null){
        MultiQC.onclick = function(){
            setTimeout(() => {
                iframe.contentWindow.location.reload(true);
            }, 1000);
        }
      }

      if(proj_MultiQC != null){
        proj_MultiQC.onclick = function(){
            setTimeout(() => {
                iframe.contentWindow.location.reload(true);
            }, 1000);
        }
      }

      if (url) {
        for (var i = 0; i < workspace_nav.length; i++) {
          if (workspace_nav[i].getAttribute('href').split('/').pop().indexOf(url_noparams) != -1) {
            index = i;
            workspace_nav[index].className = 'nav-item nav-link workspace-nav active';
            break;
          }
        }
      }

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
    var run_time = hours + ' Hours ' + minutes + ' Minutes ' + seconds + ' Seconds ';
    $(this).text(run_time);
  })

  /**
   * Get url params
   */
  function getQueryVariable(variable){
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
    }

  /**
   * Convert task start time to year:month:second
   */
  function Sec2Time(time) {
    let datetime = new Date(time).getTime();
    var format_time = new Date(datetime) + '';
    var format_time = format_time.replace('GMT+0800 (中国标准时间)', '');
    return format_time;
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
$("#platform").change(function(){
    switch($(this).children("option:selected").val()){
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
  const db_list = ['resfinder_db', 'nt_db', 'eggnog_db', 'kraken_db', 'kofam_profile', 'kofam_kolist'];
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

  /**
   * Read .nextflow.log
   */
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function read_nextflowlog() {
    $.ajax({
      url: "/execute/start",
      type: 'POST',
      data: {
        'running_sample_id': running_sample_id,
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          let insert_message = "<p>" + res.data + "</p> "
          $('.command_out').html(insert_message);
        }else{
        }
      }
    })
  }

  function read_proj_nextflowlog() {
    $.ajax({
      url: "/executeProj/start",
      type: 'POST',
      data: {
        'running_project_id': running_project_id,
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          let insert_message = "<p>" + res.data + "</p> "
          $('.proj_command_out').html(insert_message);
        }else{
        }
      }
    })
  }

  $(".detail").on('click', function (e) {
    e.preventDefault();
    check_progress = !check_progress;
    if (check_progress) {
      $('.command_out').removeClass('d-none');
      read_progress = setInterval(() => {
        read_nextflowlog();
        let scrollHeight = $(".command_out").prop('scrollHeight');
        $(".command_out").scrollTop(scrollHeight);
      }, 5000);
    } else {
      $('.command_out').addClass('d-none');
      clearInterval(read_progress);
    }
  })

  $(".proj_detail").on('click', function (e) {
    e.preventDefault();
    check_proj_progress = !check_proj_progress;
    if (check_proj_progress) {
      $('.proj_command_out').removeClass('d-none');
      read_proj_progress = setInterval(() => {
        read_proj_nextflowlog();
        let scrollHeight = $(".proj_command_out").prop('scrollHeight');
        $(".proj_command_out").scrollTop(scrollHeight);
      }, 5000);
    } else {
      $('.proj_command_out').addClass('d-none');
      clearInterval(read_proj_progress);
    }
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

})

var FileInput = function() {
    var oFile = new Object();

    //Init fileInput
    oFile.Init = function(ctrlName, uploadUrl) {
    var control = $('.' + ctrlName);

    //Init fileUpload
    control.fileinput({
        uploadUrl: uploadUrl, //上传的地址
        allowedFileExtensions: ['xls','xlsx'],//接收的文件后缀
        showUpload: true, //是否显示上传按钮
        showCaption: false,//是否显示标题
        browseClass: "btn btn-primary", //按钮样式
        maxFileCount: 10, //表示允许同时上传的最大文件个数
        enctype: 'multipart/form-data',
        validateInitialCount:true,
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
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

