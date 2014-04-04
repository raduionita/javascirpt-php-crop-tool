<?php
  if(!empty($_POST))      // process cropping
  {
    $data = json_decode($_POST['data'], true);
    $filename = $_POST['image'];
    $filepath = 'files/'. $filename;
    
    $src_x = intval($data['x']);
    $src_y = intval($data['y']);
    $dst_w = intval($data['width']);
    $dst_h = intval($data['height']);
    $src = imagecreatefromjpeg($filepath);
    $dst = imagecreatetruecolor($dst_w, $dst_h);
    imagecolorallocate($src, 255, 255, 255);
    imagecolorallocate($dst, 255, 255, 255);
    
    imagecopyresized($dst, $src, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $dst_w, $dst_h);
    imagejpeg($dst, $filepath, 99); 
  }
  else
  {
    $filename = $_GET['image'];
    $filepath = 'files/'. $filename;
  }
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Crop Tool</title>
<style type="text/css">
  * { margin: 0; padding: 0; }
  #croper { position: relative; }
  #gizmo  { position: absolute; top: 0; margin: 0; width: 320px; height: 240px; left: 100px; top: 100px; border: 1px dashed black; cursor: move; }
  #image  { opacity: 0.6; outline: 1px solid black; }
  #button { position: absolute; bottom: 10px; right: 10px; }
</style>
</head>
<body>
  <form action="cropper.php" method="post" id="croper" style="width: 640px; height: 480px;">
    <input type="hidden" name="image" value="<?= $filename ?>">
    <input type="hidden" name="data" id="data">
    <img src="<?= $filepath ?>" id="image"  />
    <div id="gizmo"></div>
    <button id="button">Crop</button>
  </form>
<script type="text/javascript">  
  (function() {  
    String.prototype.capitalize = function() { return this.charAt(0).toUpperCase() + this.slice(1); }
    var croper = document.getElementById('croper');
    var data = document.getElementById('data');
    var gizmo  = document.getElementById('gizmo');
    var button  = document.getElementById('button');
    var isDragging = false;
    var move = 'left';
    var difx = dify = curx = cury = oldx = oldy = 0;
    gizmo.onmouseout = function(evt) {
      if(!isDragging) {
        evt.preventDefault();
        evt.stopPropagation();
        var empty = '';
        gizmo.style['borderTop']    = empty;
        gizmo.style['borderRight']  = empty;
        gizmo.style['borderBottom'] = empty;
        gizmo.style['borderLeft']   = empty;
      }
    };
    gizmo.onmousemove = function(evt) {
      if(!isDragging) {
        evt.preventDefault();
        evt.stopPropagation();
        oldx = evt.clientX; 
        oldy = evt.clientY;
        var empty = '';
        var move  = null;
        gizmo.style['borderTop']    = empty;
        gizmo.style['borderRight']  = empty;
        gizmo.style['borderBottom'] = empty;
        gizmo.style['borderLeft']   = empty;
        if(evt.offsetX < 5)
          move = 'left';
        else if(evt.offsetX > gizmo.clientWidth - 5)
          move = 'right';
        else if(evt.offsetY < 5)
          move = 'top';
        else if(evt.offsetY > gizmo.clientHeight - 5)
          move = 'bottom';
        if(move)
          gizmo.style['border'+ move.capitalize()] = '1px solid black';
      }
    };
    gizmo.onmousedown = function(evt) {
      evt.preventDefault();
      evt.stopPropagation();
      isDragging = true;
      oldx = evt.clientX; 
      oldy = evt.clientY;
      if(evt.offsetX < 5)
        move = 'left';
      else if(evt.offsetX > gizmo.clientWidth - 5)
        move = 'right';
      else if(evt.offsetY < 5)
        move = 'top';
      else if(evt.offsetY > gizmo.clientHeight - 5)
        move = 'bottom';
      else
        move = '';
      gizmo.style['border'+ move.capitalize()] = '1px solid red';
    };
    document.onmousemove = function(evt) {
      if(isDragging) {
        evt.preventDefault();
        evt.stopPropagation();
        curx = evt.clientX;
        cury = evt.clientY;
        difx = curx - oldx;
        dify = cury - oldy;
        switch(move) {
          case '':
            gizmo.style.left  = (gizmo.offsetLeft + difx) +'px';
            gizmo.style.top    = (gizmo.offsetTop + dify) +'px';
          break;
          case 'top':
            gizmo.style.top    = (gizmo.offsetTop + dify) +'px';
            gizmo.style.height = (gizmo.clientHeight - dify) +'px';
          break;
          case 'right':
            gizmo.style.width = (gizmo.clientWidth + difx) +'px';
          break;
          case 'bottom':
            gizmo.style.height = (gizmo.clientHeight + dify) + 'px';
          break;
          case 'left':
            gizmo.style.left  = (gizmo.offsetLeft + difx) +'px';
            gizmo.style.width = (gizmo.clientWidth - difx) +'px';
          break;
        }
        oldx = curx;
        oldy = cury;
      }
    };
    document.onmouseup = function(evt) {
      if(isDragging) {
        evt.preventDefault();
        evt.stopPropagation();
        difx = dify = curx = cury = oldx = oldy = 0;
        gizmo.style['border'+ move.capitalize()] = '';
        isDragging = false;
      }
    };
    button.onclick = function(evt) {
      evt.preventDefault();
      data.value = JSON.stringify({ x: gizmo.offsetLeft, y: gizmo.offsetTop, width: gizmo.clientWidth, height: gizmo.clientHeight });
      croper.submit();
    };
  })();
  
</script>
</body>
</html>