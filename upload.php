<?php
if(!empty($_FILES))
{
  $image =& $_FILES['image'];
  
  $filename = basename($image['name']);
  $filepath = 'files/'. $filename;
  move_uploaded_file($image['tmp_name'], $filepath);
  unset($image); unset($_FILES); 
  
  list($src_w, $src_h) = getimagesize($filepath);
  $dst_w = 640; $dst_h = 480;
  $src = imagecreatefromjpeg($filepath);
  $dst = imagecreatetruecolor($dst_w, $dst_h);
  imagecolorallocate($src, 255, 255, 255);
  imagecolorallocate($dst, 255, 255, 255);
  
  $src_r = $src_w / $src_h;
  $dst_r = $dst_w / $dst_h;
  
  $src_x = floor(abs($src_w - $dst_w) / 2);
  $src_y = floor(abs($src_h - $dst_h) / 2);
  
  imagecopyresized($dst, $src, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $dst_w, $dst_h);
  
  imagejpeg($dst, $filepath, 99); 
  
  header('Location: cropper.php?image='. $filename);
?><?php } else { ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Crop Tool</title>
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data" id="form">
  <input type="file" name="image" id="image" style="display: none">
  <input type="text" id="filepath" disabled>
  <button type="submit" id="browse">Browse</button>
</form>
<script type="text/javascript">
  var form   = document.getElementById('form');
  var browse = document.getElementById('browse');
  var image  = document.getElementById('image');
  var filepath  = document.getElementById('filepath');
  var hasSelected = false;
  browse.onclick = function(evt) {
    evt.preventDefault();
    if(hasSelected)
      form.submit();
    else
      image.click();
  };
  image.onchange = function(evt) {
    hasSelected = true;
    browse.innerText = 'Upload';
    filepath.value = image.value;
  };
</script>
</body>
</html>
<?php } ?>