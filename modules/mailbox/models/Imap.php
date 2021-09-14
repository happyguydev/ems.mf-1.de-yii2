<?php
namespace app\modules\mailbox\models;

use app\modules\mailbox\models\EmailFolder;
use app\modules\mailbox\models\Mailbox;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Imap extends Model {
	public $username;
	public $password;

	public $hostname;
	public $email_client_id;
	public $user_email_id;
	public $_imap_stream;

	public function setUser() {
		$session = Yii::$app->session;
		$user_email_id = $session['UserEmail'];

		$user_email = UserEmail::find()->where(['id' => $user_email_id])->one();
		if ($user_email) {
			$this->hostname = '{' . $user_email->emailClient->imap_host_url . '/imap/ssl/novalidate-cert}';
			$this->username = $user_email->email;
			$this->password = base64_decode($user_email->password);
			$this->email_client_id = $user_email->emailClient->id;
			$this->user_email_id = $user_email_id;
		}
	}
	public function saveFolder($name, $path) {
		// $EmailFolderOld = EmailFolder::find()->where(['email_client_id' => $this->email_client_id])->all();
		// foreach ($EmailFolderOld as $key => $value) {
		// 	$value->status = 2;
		// 	$value->save(false);
		// }
		$EmailFolder = EmailFolder::find()->where(['user_email_id' => $this->user_email_id])->andWhere(['name' => $name])->one();
		if ($EmailFolder) {
			$model = $EmailFolder;
		} else {
			$model = new EmailFolder();
		}
		$model->name = strtolower($name);
		$model->path = $path;
		$model->user_email_id = $this->user_email_id;
		$model->status = 1;
		$model->save();

	}
	public function readFolder() {
		$this->setUser();
		if (!$this->hostname) {
			return false;
		}
		$inbox = imap_open($this->hostname, $this->username, $this->password);
		if (!$inbox) {
			return false;
		}

		$list = imap_list($inbox, $this->hostname, "*");
		if (is_array($list)) {

			//loop through rach array index
			foreach ($list as $val) {

				//remove  any } charactors from the folder
				if (preg_match("/}/i", $val)) {
					$arr = explode('}', $val);
				}

				//also remove the ] if it exists, normally Gmail have them
				if (preg_match("/]/i", $val)) {
					$arr = explode(']/', $val);
				}

				//remove any slashes
				$folder = trim(stripslashes($arr[1]));

				//remove inbox. from the folderName its not needed for displaying purposes
				$folderName = str_replace('INBOX.', '', $folder);
				$folderName = imap_utf7_decode($folderName);
				$this->saveFolder($folderName, $val);

			}

		}
	}
	public function getFolderId($name = '') {
		$session = Yii::$app->session;
		$user_email_id = $session['UserEmail'];
		$query = EmailFolder::find()->where(['user_email_id' => $user_email_id])->andWhere(['status' => 1]);
		if ($name != '') {
			$name = strtolower($name);
			$query->andWhere(['name' => $name]);
		}
		$model = $query->one();
		if ($model) {
			return $model->id;
		} else {
			return 0;
		}
	}
	public function getMailboxes($name = '') {
		$session = Yii::$app->session;
		$user_email_id = $session['UserEmail'];
		$query = EmailFolder::find()->where(['user_email_id' => $user_email_id])->andWhere(['status' => 1]);
		if ($name != '') {
			$name = strtolower($name);
			$query->andWhere(['name' => $name]);
		}
		$EmailFolder = $query->all();

		foreach ($EmailFolder as $key => $value) {
			$this->readInbox($value->path, $value->id);
		}
	}

	public function listMessages($page = 1, $per_page = 25, $sort = null) {
		$limit = ($per_page * $page);
		$start = ($limit - $per_page) + 1;
		$start = ($start < 1) ? 1 : $start;
		$limit = (($limit - $start) != ($per_page - 1)) ? ($start + ($per_page - 1)) : $limit;
		$info = imap_check($this->_imap_stream);
		$limit = ($info->Nmsgs < $limit) ? $info->Nmsgs : $limit;

		if (true === is_array($sort)) {
			$sorting = array(
				'direction' => array('asc' => 0,
					'desc' => 1),

				'by' => array('date' => SORTDATE,
					'arrival' => SORTARRIVAL,
					'from' => SORTFROM,
					'subject' => SORTSUBJECT,
					'size' => SORTSIZE));
			$by = (true === is_int($by = $sorting['by'][$sort[0]]))
			? $by
			: $sorting['by']['date'];
			$direction = (true === is_int($direction = $sorting['direction'][$sort[1]]))
			? $direction
			: $sorting['direction']['desc'];

			$sorted = imap_sort($this->_imap_stream, $by, $direction);

			$msgs = array_chunk($sorted, $per_page);
			$msgs = $msgs[$page - 1];
		} else {
			$msgs = range($start, $limit);
		}
		//just to keep it consistent

		$result = imap_fetch_overview($this->_imap_stream, implode($msgs, ','), 0);
		if (false === is_array($result)) {
			return false;
		}

		//sorting!
		if (true === is_array($sort)) {
			$tmp_result = array();
			foreach ($result as $r) {
				$tmp_result[$r->msgno] = $r;
			}

			$result = array();
			foreach ($msgs as $msgno) {
				$result[] = $tmp_result[$msgno];
			}
		}

		$return = array('res' => $result,
			'start' => $start,
			'limit' => $limit,
			'sorting' => array('by' => $sort[0], 'direction' => $sort[1]),
			'total' => imap_num_msg($this->_imap_stream));
		$return['pages'] = ceil($return['total'] / $per_page);
		return $return;
	}
	public function readInbox($path, $folderId) {
		$this->setUser();
		/* connect to gmail */
		//	try {
		/* try to connect */

		$inbox = imap_open($path, $this->username, $this->password) or die('Cannot connect to Imap server: ' . imap_last_error());
		$this->_imap_stream = $inbox;
		//$b = $this->listMessages();
		//print_r($b);
		//die;
		// get information about the current mailbox (INBOX in this case)
		$mboxCheck = imap_check($inbox);

// get the total amount of messages
		$totalMessages = $mboxCheck->Nmsgs;

// select how many messages you want to see
		$showMessages = 20;
		// if ($totalMessages < $showMessages) {
		// 	$showMessages = $totalMessages;
		// }
		if ($totalMessages <= 0) {
			return false;
		}

// get those messages
		//$emails = array_reverse(imap_fetch_overview($inbox, ($totalMessages - $showMessages + 1) . ":" . $totalMessages));

/* grab emails */
		$emails = imap_search($inbox, 'ALL', SE_FREE, "UTF-8");
		//$emails = imap_search($inbox, $type, SE_FREE, "UTF-8");
		/* if emails are returned, cycle through each... */
		if ($emails) {

			/* begin output var */
			$output = '';

			/* put the newest emails on top */
			rsort($emails);
			//print_r($emails);

			/* for every email... */
			foreach ($emails as $email_number) {

				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox, $email_number, 0);
				// $body = imap_fetchtext($inbox, $email_number, 0);
				$body = $this->getBody($overview[0]->uid, $inbox); //(imap_fetchbody($inbox, $email_number, "1.2"));
				imap_undelete($inbox, $overview[0]->uid, FT_UID);
				$this->updateData($overview[0], $body, $folderId);
				// $output .= 'Name:  ' . $overview[0]->from . '</br>';
				// $output .= 'Email:  ' . $overview[0]->message_id . '</br>';
				//imap_setflag_full($inbox, $overview->uid, "\\Saved \\Flagged", ST_UID);
				//echo '<pre>' . print_r($overview) . '</pre>';
			}

		}

/* close the connection */
		imap_close($inbox);
		// } catch (\Exception $e) {
		// 	return false;
		// }
	}
	public function getBody($uid, $imap) {
		$body = $this->get_part($imap, $uid, "TEXT/HTML");
		// if HTML body is empty, try getting text body
		if ($body == "") {
			$body = $this->get_part($imap, $uid, "TEXT/PLAIN");
		}
		return $body;
	}

	public function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
		if (!$structure) {
			$structure = imap_fetchstructure($imap, $uid, FT_UID);
		}
		if ($structure) {
			if ($mimetype == $this->get_mime_type($structure)) {
				if (!$partNumber) {
					$partNumber = 1;
				}
				$text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
				switch ($structure->encoding) {
				case 3:
					return imap_base64($text);
				case 4:
					return imap_qprint($text);
				default:
					return $text;
				}
			}

			// multipart
			if ($structure->type == 1) {
				foreach ($structure->parts as $index => $subStruct) {
					$prefix = "";
					if ($partNumber) {
						$prefix = $partNumber . ".";
					}
					$data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
					if ($data) {
						return $data;
					}
				}
			}
		}
		return false;
	}

	public function get_mime_type($structure) {
		$primaryMimetype = ["TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER"];

		if ($structure->subtype) {
			return $primaryMimetype[(int) $structure->type] . "/" . $structure->subtype;
		}
		return "TEXT/PLAIN";
	}
	public function updateData($overview, $body, $folderId) {
		$message_id = isset($overview->message_id) ? $overview->message_id : '<>';
		$old = Mailbox::find()
			->where(['message_id' => $message_id])
			->andWhere(['email_client_id' => $this->email_client_id])
			->andWhere(['user_email_id' => $this->user_email_id])
			->andWhere(['folder_id' => $folderId])
			->one();
		if ($old) {
			$model = $old;
		} else {
			$model = new Mailbox();

			$model->created_at = date('Y-m-d H:i:s');
		}
		$model->email_client_id = $this->email_client_id;
		$model->subject = imap_utf8($overview->subject);
		$model->email_from = imap_utf8($overview->from);

		$model->email_to = imap_utf8($overview->to);
		$model->email_date = $overview->date;
		$model->message_id = $overview->message_id;
		$model->body = $body;
		$model->email_size = $overview->size;
		$model->uid = $overview->uid;
		$model->msgno = $overview->msgno;
		$model->recent = $overview->recent;
		$model->flagged = $overview->flagged;
		$model->answered = $overview->answered;
		$model->trashed = $model->deleted = $overview->deleted;
		$model->seen = $overview->seen;
		$model->draft = $overview->draft;
		$model->udate = $overview->udate;
		$model->user_email_id = $this->user_email_id;
		$model->status = 1;
		$model->folder_id = $folderId;

		return $model->save(false);

	}
}
