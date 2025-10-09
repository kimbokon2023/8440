<?php
$scale = isset($_REQUEST["scale"]) ? $_REQUEST["scale"] : 100;

$check = isset($_REQUEST["check"]) ? $_REQUEST["check"] : (isset($_POST["check"]) ? $_POST["check"] : '1');

$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

$find = isset($_REQUEST["find"]) ? $_REQUEST["find"] : "";

$cursort = isset($_REQUEST["cursort"]) ? $_REQUEST["cursort"] : '0';

$sortof = isset($_REQUEST["sortof"]) ? $_REQUEST["sortof"] : '0';

$stable = isset($_REQUEST["stable"]) ? $_REQUEST["stable"] : '0';

$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";

$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

$buttonval = isset($_REQUEST["buttonval"]) ? $_REQUEST["buttonval"] : "";

$navibar = isset($_REQUEST["navibar"]) ? $_REQUEST["navibar"] : (isset($_POST["navibar"]) ? $_POST["navibar"] : '0');

?>