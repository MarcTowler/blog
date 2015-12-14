<?php
include('../../../../classes/Parsedown.php');

$p = new Parsedown();

$html = $p->text($_POST['data']);

$html = str_replace('<script', '&lt;script>', $html);
$html = str_replace('<style', '&lt;style', $html);
echo $html;