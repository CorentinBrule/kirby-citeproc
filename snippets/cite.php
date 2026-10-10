<?php 

use Seboettg\CiteProc\StyleSheet;
use Seboettg\CiteProc\CiteProc;

// $data:Array input snippet
// $key:String input snippet

$style = StyleSheet::loadStyleSheet(option('corentinbrule.citeproc.csl_name'));
$citeProc = new CiteProc($style, "fr-FR");

echo $citeProc->render($data, "citation", json_decode('[{"id":"' . $key .'"}]'));
?>