<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menu_items}}".
 *
 * @property int $id
 * @property string|null $data
 */
class MenuItems extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public $htmlData;
	public static function tableName() {
		return '{{%menu_items}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['data'], 'string'],
			['counter', 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'data' => 'Data',
		];
	}

	public function parseChild() {

	}

	public function makeHtml($value, $start, $end, $main, $first) {
		$this->htmlData .= "";
		$name = $value['name'];
		$slug = $value['slug'];
		$id = $value['id'];
		if ($start == 1 && $first == 0) {
			$this->htmlData .= '<ol class="dd-list">';
		}

		$this->htmlData .= '<li class="dd-item" data-id="new-' . $start . '" data-name="' . $name . '" data-slug="' . $slug . '" data-new="0" data-deleted="0" onclick="openUrl()">
                <div class="dd-handle">' . $name . '</div>
                <span class="button-delete btn btn-default btn-xs pull-right"
                      data-owner-id="new-' . $start . '">
                  <i class=" fa fa-remove tooltip" title="Delete" aria-hidden="true"></i>
                </span>
                <span class="button-edit btn btn-default btn-xs pull-right"
                      data-owner-id="new-' . $start . '">
                  <i class="fa fa-edit tooltip" title="Edit" aria-hidden="true"></i>
                </span>';

		if ($start != $end) {
			$this->htmlData .= '</li>';
		}
		if ($start == $end && $main == 0 && $first == 0) {
			$this->htmlData .= '</li></ol>';
		}

		/*$model = self::find()->one();
			$model->counter = $start;
		*/

	}
	public function jsonData() {
		$items = json_decode($this->data, true);
		return $this->parseItem($items, 1, 1);
	}
	public function parseItem($items, $main, $first) {
		if ($items) {
			$end = count($items);
			foreach ($items as $key => $value) {

				$start = $key + 1;

				$is_child = (isset($value['children'])) ? 1 : 0;
				$this->makeHtml($value, $start, $end, $main, $first);
				if (isset($value['children'])) {
					$this->parseItem($value['children'], 1, 0);
				}

			}
			$this->htmlData .= '</li></ol>';
			return $this->htmlData;
		}
	}
}
