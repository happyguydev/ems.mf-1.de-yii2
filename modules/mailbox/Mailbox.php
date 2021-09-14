<?php

namespace app\modules\mailbox;

/**
 * mailbox module definition class
 */
class Mailbox extends \yii\base\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'app\modules\mailbox\controllers';
	public $defaultRoute = 'inbox';
	public $layout = '@app/themes/admin/main';

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();

		// custom initialization code goes here
	}
}
