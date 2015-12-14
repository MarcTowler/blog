<?php
include('../../../../classes/Parsedown.php');

$p = new Parsedown();

$html = $p->text($_POST['data']);

$html = str_replace('<', '&lt;', $html);
$html = str_replace('>', '&gt;', $html);
echo $html;