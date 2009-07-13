<?php
/** Adminer Editor - Compact MySQL editor
* @link http://www.adminer.org/
* @author Jakub Vrana, http://php.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
*/

include "../adminer/include/bootstrap.inc.php";

$confirm = " onclick=\"return confirm('" . lang('Are you sure?') . "');\"";
$error = "";

if (isset($_GET["download"])) {
	include "../adminer/download.inc.php";
} else { // uses CSRF token
	$token = $_SESSION["tokens"][$_GET["server"]];
	if ($_POST) {
		if ($_POST["token"] != $token) {
			$error = lang('Invalid CSRF token. Send the form again.');
		}
	} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
		// posted form with no data means exceeded post_max_size because Adminer always sends token at least
		$error = lang('Too big POST data. Reduce the data or increase the "post_max_size" configuration directive.');
	}
	if (isset($_GET["select"]) && ($_POST["edit"] || $_POST["clone"]) && !$_POST["save"]) {
		$_GET["edit"] = $_GET["select"];
	}
	if (isset($_GET["edit"])) {
		include "../adminer/edit.inc.php";
	} elseif (isset($_GET["select"])) {
		include "../adminer/select.inc.php";
	} else {
		include "./db.inc.php";
	}
}

// each page calls its own page_header(), if the footer should not be called then the page exits
page_footer();
