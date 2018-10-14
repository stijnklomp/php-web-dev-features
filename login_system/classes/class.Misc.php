<?php
final class Misc {
	private static $instance;
	protected $db;

	private function __construct() {
		$this->db = Database::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new Misc();
		}
		return self::$instance;
	}

	// Create rand ID
	public function getGUID() {
		$m = microtime(true);
		return sprintf('%X%X', floor($m), ($m - floor($m)) * 1000000);
	}

	// Retreive date
	public function getDate($format = 'l jS \of F Y h:i:s A', $GUID = null) {
		if(empty($GUID)) {
			$GUID = sprintf('%X%X', floor(microtime(true)), (microtime(true) - floor(microtime(true))) * 1000000);
		}
		return date($format, hexdec(substr($GUID, 0, 8)));
	}

	// Upload image
	function saveUploadedFile($imgName, $varName, $directoryImagePath) {
		if(isset($_FILES[$varName])) {
			$file = $_FILES[$varName];
			/* first test only to block more than one extension file */
			if(count(explode('.', $file['name'])) > 2) {
				return -2;
			} elseif(preg_match("`\.([^.]+)$`", $file['name'], $match)) {
				/* here file has just one extension */
				$ext = strtolower($match[1]);
				if($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
					return -3;
				} else {
					/**
					* extension is ok
					* third test with fileinfo to get the mime type with magic bytes
					*/
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$fileType = finfo_file($finfo, $file['tmp_name']);
					/* fourth test depends on the extension, imagecreate with gd, to check if the image is valid and remove all exif data to avoid code injection */
					switch($fileType) {
						case 'image/gif':
							$logoRecreated = @imagecreatefromgif($file['tmp_name']);
							/* fix for transparency */
							imageAlphaBlending($logoRecreated, true);
							imageSaveAlpha($logoRecreated, true);
							$extSafe = 'gif';
							break;
						case 'image/jpeg':
							$logoRecreated = @imagecreatefromjpeg($file['tmp_name']);
							$extSafe = 'jpg';
							break;
						case 'image/png':
							$logoRecreated = @imagecreatefrompng($file['tmp_name']);
							/* fix for transparency */
							imageAlphaBlending($logoRecreated, true);
							imageSaveAlpha($logoRecreated, true);
							$extSafe = 'png';
							break;
						default:
							return -4;
					}
					if(!$logoRecreated) {
						/* imagecreate* failed, the image is not good */
						return -5;
					} else {
						/** 
						* valid image, good mime type
						* destination is writable ?
						*/
						/* generate random failename */
						$picturePath = $directoryImagePath.$imgName;

						if(is_writable($directoryImagePath)) {
							/* usage of move_uploaded_file to check on more time if the file is good (is it too much ?) */
							$moveUploadReturn = move_uploaded_file($file['tmp_name'], $picturePath);
							if(!$moveUploadReturn) {
								return -7;
							} else {
								/* move_uploaded_file return is ok, I delete the file, and use the GD created exif free file instead */
								$unlinkReturn = unlink($picturePath);
								if(!$unlinkReturn) {
									return -8;
								} else {
									/* the file is deleted, saving the new image */
									switch($extSafe) {
										case 'gif':
											$retourSaveImage = imagegif($logoRecreated, $picturePath);
											break;
										case 'jpg':
											$retourSaveImage = imagejpeg($logoRecreated, $picturePath);
											break;
										case 'png':
											$retourSaveImage = imagepng($logoRecreated, $picturePath);
											break;
									}
									$retourDestroy = imagedestroy($logoRecreated);
									if(!$retourSaveImage || !$retourDestroy) {
										return -9;
									}
								}
							}
						} else {
							return -6;
						}
					}
				}
			}
		} else {
			return -1;
		}
	}
}
?>