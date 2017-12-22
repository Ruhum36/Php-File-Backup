<?php
	
	// Php Files Backup
	// Ruhum
	// 22.12.2017

	function zipData($source, $destination) {

		if (extension_loaded('zip')) {


			if (file_exists($source)) {

				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE)) {

					$source = realpath($source);
					if (is_dir($source)) {

						$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file) {

							$file = realpath($file);
							if (is_dir($file)) {

								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));

							} else if (is_file($file)) {

								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));

							}

						}

					} else if (is_file($source)) {

						$zip->addFromString(basename($source), file_get_contents($source));

					}
				}

				return $zip->close();

			}

		}

		return false;

	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Php Files Backup</title>
	<style type="text/css">
		
		*{
			font-family: arial;
		}

	</style>
</head>
<body>

<div>
	
	<center><h2>Php Files Backup</h2></center>

	<form action="" method="POST" style="width: 500px; margin: auto;">
			
		<input type="text" name="File" style="padding: 10px; width: 450px; border-radius: 3px; border: 1px solid #ddd;" placeholder="File Name" /><br />
		<label style="font-size: 12px; color: #999; float: left; margin: 5px auto auto 5px;">Example: * or Folder Path</label>
		<input type="submit" value="Backup" style="padding: 10px; width: 470px; border-radius: 3px; border: 1px solid #ddd; background-color: #f6f6f6; margin-top: 10px;" />

	</form>

</div>

<?php
	
	if(isset($_POST['File'])){

		$Folder = $_POST['File'];

		$Folder = $Folder=='*'?'./':$Folder;
		$BackupName = uniqid().'.zip';
		$Backup = zipData($Folder, './'.$BackupName);
		if($Backup){
			
			echo '<center><h2>Download Backup File: <a href="'.$BackupName.'">Download</a></h2></center>';
			echo '<center><h2>Backup Size: '.filesize($BackupName).' Byte</h2></center>';

		}else{

			echo '<center><h2>Backup Error</h2></center>';

		}

		

	}

?>

</body>
</html>