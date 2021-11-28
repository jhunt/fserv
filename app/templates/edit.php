<?php

function _($s) { echo $s; }
function _a($s) { echo $s; }
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dossier Files : Edit</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'header.php' ?>
  <h1>Edit <?php _($manifest['name']) ?></h1>
  <div id="main">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="f" value="<?php _($sha) ?>">

      <div class="control primary">
        <label>Name</label>
          <input type="text" name="name" value="<?php _a($manifest['name']) ?>">
      </div>

      <div class="control">
        <label>Notes</label>
          <textarea name="notes"><?php _($manifest['notes']) ?></textarea>
      </div>

      <div class="control">
        <label>Tags</label>
          <input type="text" name="tags" autocomplete="no" value="<?php _(implode(', ', $manifest['tags'])) ?>">
      </div>

      <div>
        <button type="submit">Save Changes</button>
      </div>
    </form>
  </div>
  <?php include 'footer.php' ?>
</body>
</html>
