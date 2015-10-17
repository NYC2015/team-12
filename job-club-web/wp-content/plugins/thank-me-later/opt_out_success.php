<?php

if (!defined("ABSPATH")) {
	exit;
}

?><!DOCTYPE html>
<html>
<head>
<title><?php echo __("Opt out", "bbpp-thankmelater"); ?></title>
<meta name="robots" content="noindex, nofollow">
</head>

<body>
	<h1><?php echo __("Opt out", "bbpp-thankmelater"); ?></h1>
	<p><?php echo __("You have opted out of future emails. We will no longer send you emails after you comment.", "bbpp-thankmelater"); ?></p>
	<p><a href="<?php echo esc_attr(get_site_url()); ?>"><?php echo __("Continue to website.", "bbpp-thankmelater"); ?></a></p>
</body>
</html>