/**
 * workspace 链接激活状态
 */

window.onload = function () {
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

/**
 * project description文本展开与折叠
 */

$(function () {
  text_folded('.proj_desc', 200);
});

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

// sample根据pairends控制上传file数量
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

// workspace-nav选中时的阴影效果
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

//workspace用户卡片淡出
$(document).ready(
  function () {
    $('.workspace-menu-nodisplay').fadeIn(1000);
  }
)

// execute params setting
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

var time = $(".run_time").text();
var run_period = parseInt(time) * 1000;
setInterval(function () {
  var hours = Math.floor(run_period / 3600000);
  var minutes = Math.floor((run_period % 3600000) / 60000);
  var seconds = Math.floor((run_period - hours * 3600 * 1000 - minutes * 60 * 1000) / 1000);
  var period = hours + ' Hours ' + minutes + ' Minutes ' + seconds + ' Seconds ';
  $(".run_period").text(period);
  run_period += 1000;
}, 1000)


function showCommand() {
  $(function () {
    $.ajax({
      url: 'command.text',
      dataType: 'text',
      success: function (msg) {
        $('.command_out').text(msg);
      },
    })
  });
}

$('.execute').onclick(setInterval(
  showCommand()
), 1000);
