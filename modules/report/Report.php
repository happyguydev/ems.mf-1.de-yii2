<?php

namespace app\modules\report;

/**
 * report module definition class
 */
class Report extends \yii\base\Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'app\modules\report\controllers';

	public $layout = '@app/themes/admin/main';

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();

		// custom initialization code goes here
	}
}
