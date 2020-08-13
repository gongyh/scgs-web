/**
 * workspace 链接激活状态
 */

window.onload = function () {
  var index = 0;
  var current_url = window.location.href;
  var url = current_url.split('/').pop();
  var workspace_nav = document.getElementsByClassName('workspace-nav');
  if (url) {
    for (var i = 0; i < workspace_nav.length; i++) {
      if (workspace_nav[i].getAttribute('href').indexOf(url) != -1) {
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
