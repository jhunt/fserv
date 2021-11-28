<?php

define('FSERV', 1);

$action = isset($_REQUEST['action'])
              ? $_REQUEST['action']
              : 'home';

switch ($action) {
	case 'info':
		phpinfo();
		break;
	case 'home':
		$root = "/srv/files";
		$all = [];
		$fh = fopen("$root/manifest", "r");
		while (($line = fgets($fh)) !== false) {
			[$sha, $file] = preg_split('/\s+/', $line);
			$all[] = [$sha, $file];
		}
		fclose($fh);

		include 'templates/home.php';
		break;

	case 'upload':
		$file = $_FILES['file'];
		$sha = hash_file('sha256', $file['tmp_name']);
		echo "$sha<br>";
		print_r($_REQUEST);
		print_r($_FILES);

		$root = "/srv/files";
		$a = substr($sha, 0, 4); @mkdir("$root/$a");
		$b = substr($sha, 4, 4); @mkdir("$root/$a/$b");
		$c = substr($sha, 8, 8); @mkdir("$root/$a/$b/$c");
		$d = substr($sha, 8);    @mkdir("$root/$a/$b/$c/$d");
		                         @mkdir("$root/$a/$b/$c/$d/$sha");
		$name = $file['name'];

		move_uploaded_file(
			$file['tmp_name'],
			"$root/$a/$b/$c/$d/$sha/$name",
		);
		file_put_contents(
			"$root/$a/$b/$c/$d/$sha/manifest.json",
			json_encode([
				'name'  => $_POST['name'],
				'notes' => $_POST['notes'],
				'tags'  => preg_split('/\s*[,|;]\s*/', $_POST['tags']),
				'file' => [
					'uploaded' => time(),
					'size'     => $file['size'],
					'name'     => $file['name'],
					'type'     => $file['type'],
					'sha256'   => $sha,
				],
			], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),
		);
		$fh = fopen("$root/manifest", "a+");
		fwrite($fh, "$sha $a/$b/$c/$d/$sha/$name\n");
		fclose($fh);

		header('Location: /?action=view&f='.$sha);
		die();
		break;

	case 'view':
		$sha = $_GET['f'];

		$root = "/srv/files";
		$a = substr($sha, 0, 4);
		$b = substr($sha, 4, 4);
		$c = substr($sha, 8, 8);
		$d = substr($sha, 8);

		$manifest = json_decode(
			file_get_contents(
				"$root/$a/$b/$c/$d/$sha/manifest.json",
			),
			true,
		);
		include 'templates/view.php';
		break;

	case 'edit':
		$sha = $_GET['f'];

		$root = "/srv/files";
		$a = substr($sha, 0, 4);
		$b = substr($sha, 4, 4);
		$c = substr($sha, 8, 8);
		$d = substr($sha, 8);

		$manifest = json_decode(
			file_get_contents(
				"$root/$a/$b/$c/$d/$sha/manifest.json",
			),
			true,
		);
		include 'templates/edit.php';
		break;

	case 'update':
		$sha = $_GET['f'];

		$root = "/srv/files";
		$a = substr($sha, 0, 4);
		$b = substr($sha, 4, 4);
		$c = substr($sha, 8, 8);
		$d = substr($sha, 8);

		$manifest = json_decode(
			file_get_contents(
				"$root/$a/$b/$c/$d/$sha/manifest.json",
			),
			true,
		);
		$manifest['name']  = $_POST['name'];
		$manifest['notes'] = $_POST['notes'];
		$manifest['tags']  = preg_split('/\s*[,|;]\s*/', $_POST['tags']);
		file_put_contents(
			"$root/$a/$b/$c/$d/$sha/manifest.json",
			json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),
		);
		header('Location: /?action=view&f='.$sha);
		die();

	default:
		break;
}
