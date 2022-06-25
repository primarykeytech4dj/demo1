<div style="padding: 200px">
<?php

if(isset($_POST['generate_barcode']))
{
 $text=$_POST['barcode_text'];
 echo "<img alt='testing' src='phpqrcode/barcode.php?codetype=Code128&size=40&text=".$text."&print=true'/>";
}
?>
</div>

<!-- <input type="file" accept="image/*;capture=camera"> -->