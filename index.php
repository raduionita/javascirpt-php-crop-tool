<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Crop Tool</title>
<style type="text/css">
  * { margin: 0; padding: 0; }
  body { width: 810px; }
</style>
</head>
<body>
<button id="button">Add image</button>
<iframe id="iframe" frameborder="0" width="800" height="600" style="border: 1px solid silver;"></iframe>

<script type="text/javascript">
  var button = document.getElementById('button');
  var iframe = document.getElementById('iframe');
  button.onclick = function(evt) {
    evt.preventDefault();
    iframe.src = 'upload.php';
  }
</script>
</body>
</html>