var utils = require('./utils.js');

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

layui.use('element', function () {
  var element = layui.element;
});

layui.use('form', function () {
  var form = layui.form;
  var $ = layui.$;
  var layer = layui.layer;
  var field = new Object();
  var d = new Object();
  var sampleID = utils.getQueryVariable('sampleID');
  var dataBefore;
  var dataAfter;
  form.on('radio(single)', function (data) {
    $(".file_two").hide();
  });
  form.on('radio(paired)', function (data) {
    $(".file_two").show();
  });


  var t = $('#sampleUpdateForm [name]').serializeArray();
  $.each(t, function () {
    field[this.name] = this.value;
  });
  dataBefore = field;

  form.on('submit(demo-submit)', function (data) {
    var field = data.field;
    dataAfter = field;
    Object.keys(dataBefore).map(
      key => {
        if (dataAfter[key] !== dataBefore[key]) {
          d[key] = dataAfter[key]
        }
      }
    )
    $.ajax({
      url: '/samples/update',
      method: 'POST',
      data: {
        'data': d,
        'sampleID': sampleID
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          if (res.info == 'change') {
            var info = '';
            Object.keys(d).forEach((key) => {
              var s = '<div style=\"margin:5px 20px\">' + key + ' has changed to <span class=\"layui-font-red\">' +
                d[key] + '</span></div>';
              info += s;
            })
            layer.open(
            {
              type: 1,
              content: info,
              title: 'CHANGE',
              yes: function () {
                window.history.back(-1);
              },
              btn: ['confirm']
            });
          } else {
            layer.open(
            {
              type: 1,
              content: '<div style=\"margin:10px 20px\">Nothing change</div>',
              title: 'CHANGE',
              yes: function () {
                window.history.back(-1);
              },
              btn: ['confirm']
            });
          }
        }
      }
    })
  });
});
