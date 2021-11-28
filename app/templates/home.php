<?php

function _($s) { echo $s; }
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dossier Files : Upload</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'header.php' ?>
  <h1>Dossier Files</h1>
  <div id="listing">
    <?php foreach ($all as $item) { ?>
    <div>
      <a href="/?action=view&f=<?php _($item[0]) ?>">
        <img src="/files/<?php _($item[1]) ?>">
      </a>
    </div>
    <?php } ?>
  </div>
  <h1>Upload</h1>
  <div id="main">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="upload">

      <div class="drop-zone">
        <input type="file" name="file">
      </div>
      <div id="preview"></div>

      <div class="control primary">
        <label>Name</label>
        <input type="text" name="name">
      </div>

      <div class="control">
        <label>Notes</label>
        <textarea name="notes"></textarea>
      </div>

      <div class="control">
        <label>Tags</label>
        <input type="text" name="tags" autocomplete="no">
      </div>

      <div>
        <button type="submit">Upload</button>
      </div>
    </form>
  </div>
  <?php include 'footer.php' ?>
  <script>
  let upload = undefined;

    document.addEventListener('DOMContentLoaded', () => {
      let uploader = document.querySelector('.drop-zone input[type="file"]')
      let dz = document.querySelector('.drop-zone')
      dz.addEventListener('click', ev => {
        if (ev.target != uploader) {
          ev.preventDefault()
          uploader.click()
        }
      })
      dz.addEventListener('dragover', ev => ev.preventDefault())
      dz.addEventListener('drop', ev => {
        ev.preventDefault()
        if (ev.dataTransfer.items) {
          // Use DataTransferItemList interface to access the file(s)
          for (var i = 0; i < ev.dataTransfer.items.length; i++) {
            // If dropped items aren't files, reject them
            if (ev.dataTransfer.items[i].kind === 'file') {
              upload = ev.dataTransfer.items[i].getAsFile();
            }
          }

        } else {
          // Use DataTransfer interface to access the file(s)
          for (var i = 0; i < ev.dataTransfer.files.length; i++) {
                upload =  ev.dataTransfer.files[i]
          }
        }

        if (upload) {
          if (upload.type.startsWith('image/')) {
            const img = document.createElement("img");
            img.classList.add("obj");
            img.file = upload;
            let preview = document.querySelector('#preview');
            preview.appendChild(img); // Assuming that "preview" is the div output where the content will be displayed.

            const reader = new FileReader();
            reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
            reader.readAsDataURL(upload);
          }
        }
      })

      let form = document.querySelector('form');
      form.addEventListener('submit', ev => {
      ev.preventDefault()
          new FormData(form)
      })
      form.addEventListener('formdata', ev => {
      let data = ev.formData
        if (upload) {
          data.set('file', upload)
        }
        for (var value of data.values()) {
          console.log(value);
        }
        let request = new XMLHttpRequest();
        request.addEventListener('load', ev => {
          console.log('uploaded')
          document.location.href = '/';
        })
        request.open("POST", "/");
        request.send(data);
      })
    })
  </script>
</body>
</html>
