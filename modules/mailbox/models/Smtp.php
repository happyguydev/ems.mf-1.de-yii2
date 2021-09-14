<?php
namespace app\modules\mailbox\models;

use app\modules\mailbox\models\Mailbox;
use app\modules\mailbox\models\UserEmail;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Smtp extends Model {
	public $username;
	public $password;

	public $hostname;
	public $port;
	public $encryption;
	public $transport;
	public $email_client_id;
	public $user_email_id;

	public function setUser() {
		$session = Yii::$app->session;
		$user_email_id = $session['UserEmail'];
		$user_email = UserEmail::find()->where(['id' => $user_email_id])->one();
		if ($user_email) {
			$this->hostname = $user_email->emailClient->smtp_host;
			$this->port = $user_email->emailClient->smtp_port;
			$this->encryption = $user_email->emailClient->smtp_encryption;
			$this->username = $user_email->email;
			$this->password = base64_decode($user_email->password);

			$this->email_client_id = $user_email->emailClient->id;
			$this->user_email_id = $user_email_id;
		}
	}
	public function setTransport() {

		$this->transport = [
			'class' => 'Swift_SmtpTransport',
			'host' => $this->hostname,
			'username' => $this->username,
			'password' => $this->password,
			'port' => $this->port,
			'encryption' => $this->encryption,
		];

	}
	public function send($to, $subject, $body) {
		//$this->setTransport();
		Yii::$app->mailer->setTransport([
			'class' => 'Swift_SmtpTransport',
			'host' => $this->hostname,
			'username' => $this->username,
			'password' => $this->password,
			'port' => $this->port,
			'encryption' => $this->encryption,
		]);
		Yii::$app->mailer->compose('template', ['body' => $body])
			->setFrom($this->username)
			->setTo($to)
			->setSubject($subject)
			->send();
	}

	public function updateData($data, $id = 0) {
		$this->setUser();
		$old = Mailbox::find()->where(['id' => $id])->one();
		if ($old) {
			$model = $old;
		} else {
			$model = new Mailbox();
			$model->email_client_id = $this->email_client_id;
			$model->subject = $data['subject'];
			$model->email_from = $this->username;
			$model->user_email_id = $this->user_email_id;
			$model->email_to = $data['to'];
			$model->email_date = date('r');
			$model->message_id = uniqid();
			$model->body = $data['body'];
			$model->email_size = 0;
			$model->uid = 0;
			$model->msgno = 0;
			$model->recent = 1;
			$model->flagged = 0;
			$model->answered = 0;
			$model->deleted = 0;
			$model->seen = 1;
			$model->draft = 0;
			$model->udate = time();
			$model->created_at = date('Y-m-d H:i:s');

		}
		if ($data['sent'] == 1) {
			$model->status = Mailbox::STATUS_SENT;
		} else {
			$model->status = Mailbox::STATUS_DRAFT;
		}

		if ($model->save(false)) {
			if ($model->status == Mailbox::STATUS_SENT) {
				$this->send($model->email_to, $model->subject, $model->body);
				Yii::$app->session->setFlash('mailSuccess', 'Your email has been sent successfully!');
			} else {
				Yii::$app->session->setFlash('mailSuccess', 'Your email has been saved successfully!');
			}
		} else {
			Yii::$app->session->setFlash('mailError', 'Something went wrong, please check your email account and try again.');
		}
		return true;

	}
}
