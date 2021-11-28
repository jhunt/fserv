<?php

$sha = $manifest['file']['sha256'];
$a = substr($sha, 0, 4);
$b = substr($sha, 4, 4);
$c = substr($sha, 8, 8);
$d = substr($sha, 8);

$relative_url = "/files/$a/$b/$c/$d/$sha/".$manifest['file']['name'];
$title = $manifest['name'] . ' (' . $manifest['file']['name'] .')';
$is_image = substr($manifest['file']['type'], 0, 6) === 'image/';

function _($s) { echo $s; }

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php _($title) ?> : Dossier Files</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'header.php' ?>
  <h1><?php _($title) ?></h1>
  <nav>
	<li><a href="/?action=edit&f=<?php _($sha) ?>">edit</a></li>
  </nav>
<?php if ($is_image) { ?>
  <img src="<?php _($relative_url) ?>">
<?php } ?>
  <a class="download" href="<?php _($relative_url) ?>">Download</a>
  <pre><code><?php _(
    json_encode(
      $manifest,
      JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
    )) ?></code></pre>
  <?php include 'footer.php' ?>
</body>
</html>
