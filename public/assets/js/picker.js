$('#date_box').pickadate({
  format: 'yyyy/mm/dd'
});

$('#date_box').blur(function() {
  var target = $("date_box").get(0);
  target.method = "post";
  target.submit();
});