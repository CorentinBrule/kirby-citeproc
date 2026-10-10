<?php 

use Seboettg\CiteProc\StyleSheet;
use Seboettg\CiteProc\CiteProc;

$style = StyleSheet::loadStyleSheet(option('corentinbrule.citeproc.csl_name'));
$citeProc = new CiteProc($style, option('corentinbrule.citeproc.csl_location'));

echo $citeProc->render($data, "citation", json_decode('[{"id":"' . $key .'"}]'));
?>