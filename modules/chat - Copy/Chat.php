<?php

namespace app\modules\chat;

/**
 * chat module definition class
 */
class Chat extends \yii\base\Module {
	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'app\modules\chat\controllers';
	public $defaultRoute = 'index';
	public $layout = '@app/themes/admin/main';

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		// custom initialization code goes here
	}
}
