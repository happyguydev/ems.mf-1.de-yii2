<?php
namespace app\components;

use app\models\ProjectAssignee;
use app\models\TaskAssignee;
use app\modules\admin\models\General;
use app\modules\admin\models\Media;
use Yii;
use yii\base\Component;

class getTable extends Component {

	public function settings($type, $name) {

		$General = General::find()->where(['type_name' => $type])->andwhere(['setting_name' => $name])->one();

		return $General['setting_value'];

	}

	public function make_dir($dir) {

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

	}

	public function number_format($value = '', $place = 2) {

		return number_format($value, $place);
	}

	public function number_of_days($startDate, $endDate) {

		$now = $startDate != '' ? strtotime($startDate) : strtotime(date('Y-m-d')); // or your date as well
		$timeDiff = abs($now - strtotime($endDate));

		$numberDays = $timeDiff / 86400; // 86400 seconds in one day

// and you might want to convert to integer
		$numberDays = intval($numberDays);

		if ($numberDays < 0) {
			$numberDays = 0;
		}

		return $numberDays;
	}

	public function date_format($date, $type = 0) {
		// type=0 means datetime and type=1 means integer(timestamp)
		if ($date == '' || $date == "0000-00-00") {
			return "";
		}
		$st = Yii::$app->getTable;

		$df = $st->settings('local', 'date_format');
		setlocale(LC_TIME, 'en_US');
		if ($type == 1) {
			$result = date("$df", $date); // date($df,strtotime($date));
		} else {
			$result = date("$df", strtotime($date)); // date($df,strtotime($date));

		}

		return $result;
	}

	public function time_format($time, $type = 0) {

		if ($time == '' || $time == "00:00:00") {
			return "";
		}
		$st = Yii::$app->getTable;

		$tf = $st->settings('local', 'time_format');

		if ($type == 1) {
			$result = date($tf, $time);
		} else {
			$result = date($tf, strtotime($time));

		}

		return $result;
	}

	public function datetime_format($datetime, $type = 0) {
		// type=0 means datetime and type=1 means integer(timestamp)
		if ($datetime == '' || $datetime == "0000-00-00 00:00:00" || $datetime == '1970-01-01 05:30:00') {
			return "";
		}
		$st = Yii::$app->getTable;

		$df = $st->settings('local', 'date_format');
		setlocale(LC_TIME, 'en_US');
		if ($type == 1) {
			$result1 = date("$df", $datetime); // date($df,strtotime($date));
		} else {
			$result1 = date("$df", strtotime($datetime)); // date($df,strtotime($date));

		}

		$tf = $st->settings('local', 'time_format');
		if ($type == 1) {
			$result2 = date($tf, $datetime);
		} else {
			$result2 = date($tf, strtotime($datetime));

		}
		$result = $result1 . " " . $result2;

		return $result;
	}
	public function currency_format($value) {
		$st = Yii::$app->getTable;

		$cc = $st->settings('local', 'currency_code');
		$cs = $st->settings('local', 'currency_symbol');

		//setlocale(LC_MONETARY,"de_DE");
		$v = number_format($value, 2, ".", ",");

		//$result = $cs . $v . ' ' . $cc;
		$result = $cs . ' ' . $v;

		return $result;
	}

	public function thumbnail_size($filepath, $filename, $size, $maxisheight = 0, $thumbType) {
		$fileFull = $filepath . '/' . $filename;
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
		case "thumb":$savePath = str_replace("." . $ext, "_thumb." . $ext, $filename);
			break;
		case "large":$savePath = str_replace("." . $ext, "_large." . $ext, $filename);
			break;
		}

		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y)) {
			if (imagejpeg($im, $filepath . '/' . $savePath, 100)) {
				return true;
			} else {
				return false;
			}
		}

	}

	public function create_thumb($filename, $ext, $id) {

		$model = Media::find()->where(['id' => $id])->one();
		if ($model) {
			if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

				$model->thumb = $filename;

			} elseif ($ext == 'mp3' || $ext == 'wav' || $ext == 'ogg' || $ext == 'acc') {
				$model->thumb = 'audio.png';

			} elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' || $ext == 'webm' || $ext == 'mov') {
				$model->thumb = 'movie.png';

			} elseif ($ext == 'jar' || $ext == 'jad') {
				$model->thumb = 'java.png';

			} elseif ($ext == 'apk' || $ext == 'obb') {
				$model->thumb = 'apk.png';

			} elseif ($ext == 'zip' || $ext == '7z' || $ext == 'rar') {
				$model->thumb = 'zip.ico';

			} elseif ($ext == 'xlsx' || $ext == 'xl' || $ext == 'xls' || $ext == 'xltx' || $ext == 'xlt') {
				$model->thumb = 'exel.png';

			} elseif ($ext == 'docs' || $ext == 'doc' || $ext == 'dotx' || $ext == 'docm' || $ext == 'docx') {
				$model->thumb = 'word.ico';

			} elseif ($ext == 'pptx' || $ext == 'ppt') {
				$model->thumb = 'ppt.ico';

			} elseif ($ext == 'pdf') {
				$model->thumb = 'pdf.png';

			} else {
				$model->thumb = 'file.png';
			}

			$model->save();

		}

		return false;
	}

	public function getCountryByIp($ip) {
		$address = '';
		$data = (file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		if ($data) {
			$data = json_decode($data, true);
			$country = ($data['geoplugin_countryName']) ? $data['geoplugin_countryName'] : '';
			$city = ($data['geoplugin_city']) ? $data['geoplugin_city'] : '';
			// $state = ($data['geoplugin_region']) ? $data['geoplugin_region'] : '';
			//$address = $city . " " . $country;
			$address = $country;
			// $address .= ($data['geoplugin_areaCode']) ? " - " . $data['geoplugin_areaCode'] : '';
		}

		return $address;
	}

	public function getCurrentLocation($latitude, $longitude) {

		if ($latitude != '' && $longitude != '') {
			//send request and receive json data by latitude and longitude
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD88tN6dhHB2nkn6mARIfExT7z7rfOqc1c&latlng=' . trim($latitude) . ',' . trim($longitude) . '&sensor=false';
			$json = file_get_contents($url);
			$data = json_decode($json);

			$status = $data->status;

			//if request status is successful
			if ($status == "OK") {
				//get address from json data
				$location = $data->results[0]->formatted_address;
			} else {
				$location = '';
			}

			//return address to ajax
			return $location;
		}

	}

	public function get_logged_in_user_project() {
		$project_ids = [];
		$model = ProjectAssignee::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
		if (count($model) > 0) {
			foreach ($model as $key => $value) {
				$project_ids[] = $value['project_id'];
			}
		}

		return $project_ids;
	}

	public function get_logged_in_user_task() {
		$task_ids = [];
		$model = TaskAssignee::find()->where(['assign_to' => Yii::$app->user->identity->id])->all();
		if (count($model) > 0) {
			foreach ($model as $key => $value) {
				$task_ids[] = $value['task_id'];
			}
		}

		return $task_ids;
	}
}
?>