window.onload = function () {
  /**
   * workspace 链接激活状态
   */
  var index = 0;
  var current_url = window.location.href;
  var url = current_url.split('/').pop();
  var url_noparams = url.indexOf('?') != -1 ? url.slice(0, url.indexOf('?')) : url;
  var workspace_nav = document.getElementsByClassName('workspace-nav');
  if (url) {
    for (var i = 0; i < workspace_nav.length; i++) {
      if (workspace_nav[i].getAttribute('href').split('/').pop().indexOf(url_noparams) != -1) {
        index = i;
        workspace_nav[index].className = 'nav-item nav-link workspace-nav active';
        break;
      }
    }
  }
}

$(function () {
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
    var check_progress = false;
    var read_progress;

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
   * 获取url中的参数
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
   * 任务开始时间秒转换年,月,日
   */
  function Sec2Time(time) {
    let datetime = new Date(time).getTime();
    var format_time = new Date(datetime) + '';
    var format_time = format_time.replace('GMT+0800 (中国标准时间)', '');
    return format_time;
  }

  /**
   * sample根据pairends控制上传file数量
   */
  if ($("input[type='radio']:checked").val() == 'singleEnds') {
    $(".file_two").hide();
  } else {
    $(".file_two").show();
  }

  $("input[type = 'radio']").change(function () {
    if ($("input[type='radio']:checked").val() == 'singleEnds') {
      $(".file_two").hide();
    } else {
      $(".file_two").show();
    }
  })

/**
 * sample platform选中时instrument model选项改变
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
   * workspace-nav选中时的阴影效果
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
   * execute params设置
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


  $('#genus').on('change', function () {
    if ($('#genus').is(':checked')) {
      $('.genus_name').show();
    } else {
      $('.genus_name').hide();
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
   * .nextflow.log读取
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


  /**
   * jQuery 文本折叠展开特效
   * @param clas：存放文本的容器
   * @param num：要显示的字数
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

    //初始化fileinput控件（第一次初始化）
    oFile.Init = function(ctrlName, uploadUrl) {
    var control = $('#' + ctrlName);

    //初始化上传控件的样式
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

    //导入文件上传完成之后的事件
    control.on("fileuploaded", function (event, data, previewId, index) {
        $("#myModal_" + ctrlName).modal("hide");
        if (data == undefined) {
            console.log('文件格式类型不正确');
            return;
        }
        location.reload();
    });
}
    return oFile;
};
