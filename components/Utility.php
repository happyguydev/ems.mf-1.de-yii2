<?php
namespace app\components;

use Yii;
use yii\base\Component;

class Utility extends Component {

	public function updateStatus($tablename, $id, $value) {

		$db = Yii::$app->db;

		$update_query = "UPDATE $tablename SET `status` = '$value' WHERE `id`=$id";
		$update_command = $db->createCommand($update_query)->execute();

		return true;

	}

	public function dropDownArray($tablename, $key, $value, $cond = []) {

		$db = Yii::$app->db;
		$match_b = ['phone', 'mobile'];

		$condition = "";
		if (empty($cond)) {
			$condition = 1;
		} else {

			$counter = 1;
			foreach ($cond as $k => $v) {

				$condition .= "`$k` = '$v'";
				if ($counter < count($cond)) {

					$condition .= " AND ";

				}
				$counter++;

			}
		}

		$select_query = "SELECT * FROM $tablename  WHERE $condition";
		$select_command = $db->createCommand($select_query)->queryAll();

		$result_array = [];

		foreach ($select_command as $vv) {

			$f_val = null;

			if (is_array($value)) {

				foreach ($value as $dd_v) {

					if (in_array($dd_v, $match_b)) {

						$f_val .= " (" . $vv[$dd_v] . ") ";

					} else {
						$f_val .= " " . $vv[$dd_v];
					}

				}

			} else {
				$f_val = $vv[$value];
			}

			$result_array[$vv[$key]] = $f_val;

		}

		return $result_array;

	}

	public function getStatus($status) {

		if ($status == 1) {

			$result = "<b style='color:green'>" . Yii::t('app', 'Enabled') . "</b>";

		} elseif ($status == 0) {
			$result = "<b style='color:red'>" . Yii::t('app', 'Disabled') . "</b>";
		} else {
			$result = "<b>not-set</b>";
		}

		return $result;
	}

	public function makeDir($dir) {

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

	}

	/**
	usage

	Yii::$app->Utility->imgResize($uploadPath,$name , 640 , 0, 1,100);

	 **/

	public function imgResize($uploadPath, $file, $size, $maxisheight = 0, $thumbType, $q = 75) {
		$fileFull = $uploadPath . "/" . $file;
		$gis = getimagesize($fileFull);
		$type = $gis[2];
		$ext = pathinfo($fileFull, PATHINFO_EXTENSION);
		switch ($type) {
		case "1":$imorig = imagecreatefromgif($fileFull);
			break;
		case "2":$imorig = imagecreatefromjpeg($fileFull);
			break;
		case "3":$imorig = imagecreatefrompng($fileFull);
			break;
		default:$imorig = imagecreatefromjpeg($fileFull);
		}

		$x = imagesx($imorig);
		$y = imagesy($imorig);

		$woh = (!$maxisheight) ? $gis[0] : $gis[1];

		if ($woh <= $size) {
			$aw = $x;
			$ah = $y;
		} else {
			if (!$maxisheight) {
				$aw = $size;
				$ah = $size * $y / $x;
			} else {
				$aw = $size * $x / $y;
				$ah = $size;
			}
		}
		$im = imagecreatetruecolor($aw, $ah);
		switch ($thumbType) {
		case "thumb":$savePath = str_replace("." . $ext, "_thumb." . $ext, $file);
			break;
		case "large":$savePath = str_replace("." . $ext, "_large." . $ext, $file);
			break;
		case "low":$savePath = str_replace("." . $ext, "_low." . $ext, $file);
			break;
		case "2":$savePath = 'favicon.png';
			break;
		case "1":$savePath = str_replace("." . $ext, "." . $ext, $file);
			break;
		}

		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y)) {
			if (imagejpeg($im, $uploadPath . "/" . $savePath, $q)) {
				return true;
			} else {
				return false;
			}
		}

	}

	public function get_thumb($filename, $filepath) {

		$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
		$ext = strtolower($file_ext);
		$thumb_path = '/uploads/media/thumb/';

		if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

			$thumb = $filename;
			$thumb_path = $filepath;

		} elseif ($ext == 'mp3' || $ext == 'wav' || $ext == 'ogg' || $ext == 'acc') {
			$thumb = 'audio.png';

		} elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' || $ext == 'webm' || $ext == 'mov') {
			$thumb = 'movie.png';

		} elseif ($ext == 'jar' || $ext == 'jad') {
			$thumb = 'java.png';

		} elseif ($ext == 'apk' || $ext == 'obb') {
			$thumb = 'apk.png';

		} elseif ($ext == 'zip' || $ext == '7z' || $ext == 'rar') {
			$thumb = 'zip.ico';

		} elseif ($ext == 'xlsx' || $ext == 'xl' || $ext == 'xls' || $ext == 'xltx' || $ext == 'xlt') {
			$thumb = 'exel.png';

		} elseif ($ext == 'docs' || $ext == 'doc' || $ext == 'dotx' || $ext == 'docm' || $ext == 'docx') {
			$thumb = 'word.ico';

		} elseif ($ext == 'pptx' || $ext == 'ppt') {
			$thumb = 'ppt.ico';

		} elseif ($ext == 'pdf') {
			$thumb = 'pdf.png';

		} else {
			$thumb = 'file.png';
		}

		$url = Yii::$app->urlManager->createAbsoluteUrl($thumb_path . $thumb);

		return $url;
	}

	/*
		 * array of every new feature or modules that need to show in permission UI
		 * it will create all 8 permission for every new feature so it can be assigned to any user role
		 * return array
	*/
	public function getFeatureLists() {
		return ['customer' => 'Customers', 'media' => 'File Manager', 'chat' => 'Chat', 'project' => 'Project Manager', 'task' => 'Task Manager', 'mail_box' => 'Mail Box Checker', 'appointment' => 'Appointment Management', 'leave' => 'Leave Management', 'attendance_report' => 'Attendance Report', 'leave_report' => 'Leave Report', 'customer_report' => 'Customer Report', 'project_report' => 'Project Report', 'task_report' => 'Task Report'];
	}

	/*
		     * function that will check if user have specific permission
		     * usefull for views and internal calls
		     * return true | false
	*/
	public function hasAccess($featureName, $permissionName) {
		$model = new \app\models\AccessCheck($featureName, $permissionName);
		// $model->featureName = $featureName;
		// $model->permissionName = $permissionName;
		return $model->hasPermission();
	}

	/*
		     * function that will check if user have specific permission
		     * * usefull for controllers or modals calls
		     * return true | forbidden exception
	*/

	public function checkAccess($featureName, $permissionName) {

		if ($this->hasAccess($featureName, $permissionName)) {
			return true;
		} else {
			throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
		}
	}

	public function permissionQuery($featureName, $permissionName) {
		$user_id = Yii::$app->user->identity->id;
		$model = new \app\models\AccessCheck($featureName, $permissionName);
		//$model->featureName = $featureName;
		//$model->permissionName = $permissionName;
		$cond = ' 1 ';
		if (Yii::$app->user->identity->user_role == 'admin') {
			return $cond;
		}
		if (!$model->hasOwnPermission() && !$model->hasOthersPermission()) {
			$cond = '`access_by` = -1 ';

		}

		if ($model->hasOwnPermission()) {
			if ($model->hasOthersPermission()) {
				$cond = ' 1 ';
			} else {
				$cond = '`access_by` =' . $user_id;
			}
		}
		if ($model->hasOthersPermission()) {
			if ($model->hasOwnPermission()) {
				$cond = ' 1 ';
			} else {
				$cond = '`access_by` !=' . $user_id;
			}

		}
		return $cond;

		/*else {
			return 'access_by=-1';
			//throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
		}*/
	}

}
