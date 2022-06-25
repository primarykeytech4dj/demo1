<?php 
$upload_dir = ['img'=>'img/'];

$imgset = [
	'maxsize'=>2000,
	'maxwidth'=>1200,
	'maxheight'=>1200,
	'minwidth'=>10,
	'minheight'=>10,
	'type'=>['bmp', 'gif', 'jpg', 'jpeg', 'png'],
];

define('RENAME_F', 1);
$re = '';
if(isset($_FILES['upload']) && strlen($_FILES['upload']['name'])>1){
	define('F_NAME', preg_replace('/\.(.*?)$/i', '', basename($_FILES['upload']['name'])));
	$site = $_SERVER['PHP_SELF'];
	$site2 = explode('/', $site);
	$site = "http://".$_SERVER['HTTP_HOST'];
	for($i=0;$i<count($site2)-1;$i++)
		$site.=$site2[$i].'/';
	$sepext = explode('.', strtolower($_FILES['upload']['name']));
	$type = end($sepext);
	$upload_dir = in_array($type, $imgset['type']) ? $upload_dir['img'] : $upload_dir['audio'];
	$upload_dir = trim($upload_dir, '/') . '/';
	//checking for image
	if(in_array($type, $imgset['type'])){
		list($width, $height) = getimagesize($_FILES['upload']['tmp_name']);
		if(isset($width) && isset($height)) {
			if($width > $imgset['maxwidth'] || $height > $imgset['maxheight']) {
				$re .= '\\n Width x Height = '.$width . ' x '.$height.' \\n The maximum width x Height must be: '.$imgset['maxwidth'].' x '.$imgset['maxheight'];
			}
			if($width < $imgset['minwidth'] || $height < $imgset['minheight']) {
				$re .= '\\n Width x Height = '.$width.' x '.$height.'\\n The minimum Width x Height must be: '.$imgset['minwidth'].' x '.$imgset['minheight'];
			}
		}
	}else{
		$re .= 'The file : '.$_FILES['upload']['name'].' has not the allowed extension type.';
	}

	function setFName($p, $fn, $ex, $i){
		if(RENAME_F == 1 && file_exists($p.$fn.$ex)) return setFName($p, F_NAME.'_'.($i+1), $ex, ($i+1));
		else
			return $fn.$ex;
	}

	$f_name = setFName($upload_dir, F_NAME, ".$type", 0);
	$uploadpath = $upload_dir.$f_name;

	if($re == ''){
		if(move_uploaded_file($_FILES['upload']['tmp_name'], $uploadpath)){
			$CKEditorFuncNum = $_GET['CKEditorFuncNum'];
			$url = $site.$upload_dir.$f_name;
			$msg = F_NAME.'.'.$type.' successfully Uploaded: \\n- Size: '.number_format($_FILES['upload']['size']/1024, 2, '.', '').' KB';
			$re = in_array($type, $imgset['type']) ? "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')": 'var cke_ob = window.parent.CKEDITOR; for(var ckid in cke_ob.instances){ if(cke_ob.instances[ckid].focusManager.hasFocus) break;}
				cke_ob.instances[ckid].insertHtml(\'<audio src="'.$url.'" controls></audio>\', \'unfiltered_html\'); alert("'.$msg.'"); var dialog = cke_ob.dialog.getCurrent(); dialog.hide();
			';

		}else 
		$re = 'alert("Unable to upload the file")';
	}else $re = 'alert("'.$re.'")';
	//$re.= 'alert("'.$re.'")';

}

@header('Content-type:text/html; charset=utf-8');
echo '<script>'.$re.';</script>';
 ?>
