require('layui')

layui.use('element', function () {
  var element = layui.element;

});

layui.use('form', function () {
  var form = layui.form;
  form.on('radio(single)', function (data) {
    $(".file_two").hide();
  });
  form.on('radio(paired)', function (data) {
    $(".file_two").show();
  });
});
