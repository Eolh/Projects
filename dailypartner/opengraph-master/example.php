<?php

require_once('OpenGraph.php');

$url = "https://www.youtube.com/watch?v=4yGKbh69OzE";

$graph = OpenGraph::fetch($url);

$graph->keys();
$graph->schema;

echo "<pre>";
var_dump($graph);
echo "</pre>";


$graph->next();
$graph->next();
$graph->next();
$graph->next();
$graph->next();
$graph->next();
//echo $graph->key();
$text = $graph->current();
?>
<embed src="<?=$text?>"></embed>
    <iframe src="http://web-nara.com/bbs/board.php?bo_table=bo_f_1&wr_id=11" frameborder="0"></iframe>