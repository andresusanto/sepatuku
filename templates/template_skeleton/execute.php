<?php

$data = MergeScenarioDataWithMain($scenario);
$smarty->assign($data);
$smarty->display("$pagename.tpl");
