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
	public $sidebarMenuHtml;
	public $sidebarMenuHtmlMobile;
	public static function tableName() {
		return '{{%menu_items}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['data'], 'string'],
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

	public function makeHtml1($value, $start, $end, $main, $first) {
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
	public function parseItem1($items, $main, $first) {
		if ($items) {
			$end = count($items);
			foreach ($items as $key => $value) {

				//$start = $key + 1;
				$start = rand(0, 100);

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
	public function parseItem($items, $main, $first) {
		if ($items) {
			return $this->makeHtml($items);

		}
	}

	public function makeHtml($menu_array) {
		$this->htmlData .= '';

		if ($menu_array) {
			//go through each top level menu item
			foreach ($menu_array as $key => $menu) {

				if ($menu['deleted'] != 1) {
					$random = rand(101, 200);
					//echo "<li><a href='{$menu['slug']}'>{$menu['name']}</a>";

					$this->htmlData .= '<li class="dd-item" data-id="new-' . $random . '" data-name="' . $menu['name'] . '" data-slug="' . $menu['slug'] . '" data-new="' . $menu['new'] . '" data-deleted="' . $menu['deleted'] . '">
                <div class="dd-handle">' . $menu['name'] . '</div>
                <span class="button-delete btn btn-default btn-xs pull-right"
                      data-owner-id="new-' . $random . '">
                  <i class=" fa fa-remove tooltip" title="Delete" aria-hidden="true"></i>
                </span>
                <span class="button-edit btn btn-default btn-xs pull-right"
                      data-owner-id="new-' . $random . '">
                  <i class="fa fa-edit tooltip" title="Edit" aria-hidden="true"></i>
                </span>';
					//see if this menu has children
					if (array_key_exists('children', $menu)) {
						$this->htmlData .= '<ol class="dd-list">';
						//echo the child menu
						$this->makeHtml($menu['children']);
						$this->htmlData .= '</ol>';
					}
					$this->htmlData .= '</li>';
				}
			}
		}

//		$this->htmlData .= '';

		return $this->htmlData;
	}

	public function showJsonData($data, $type) {
		$items = json_decode($data, true);
		return $this->parseShowItem($items, $type);
	}

	public function parseShowItem($items, $type) {
		if ($items) {
			if ($type == 'desktop') {
				return $this->getSidebarMenuHtml($items);
			} else {
				return $this->getSidebarMenuHtmlForMobile($items);
			}

		}
	}

	public function getSidebarMenuHtml($item) {
		$this->sidebarMenuHtml .= '';
		if ($item) {
			//go through each top level menu item
			foreach ($item as $key => $menu) {
				$slug = isset($menu['slug']) && $menu['slug'] != '' ? $menu['slug'] : 'javascript:void(0)';
				$target_blank = isset($menu['slug']) && $menu['slug'] != '' ? 'target="_blank"' : '';
				$this->sidebarMenuHtml .= '<li>
                    <a href="' . $slug . '" class="side-menu" ' . $target_blank . '>

                        <div class="side-menu__title">' . $menu['name'];
				if (isset($menu['children'])) {
					$this->sidebarMenuHtml .= '<i data-feather="chevron-down" class="side-menu__sub-icon"></i>';
				}
				$this->sidebarMenuHtml .= '</div></a>';
				if (array_key_exists('children', $menu)) {
					$this->sidebarMenuHtml .= '<ul class="">';
					//echo the child menu
					$this->getSidebarMenuHtml($menu['children']);
					$this->sidebarMenuHtml .= '</ul>';
				}
				$this->sidebarMenuHtml .= '</li>';

			}

			$this->sidebarMenuHtml .= '';
			return $this->sidebarMenuHtml;
		}
	}

	public function getSidebarMenuHtmlForMobile($item) {
		$this->sidebarMenuHtmlMobile .= '';
		if ($item) {
			//go through each top level menu item
			foreach ($item as $key => $menu) {
				$slug = isset($menu['slug']) && $menu['slug'] != '' ? $menu['slug'] : 'javascript:void(0)';
				$target_blank = isset($menu['slug']) && $menu['slug'] != '' ? 'target="_blank"' : '';
				$this->sidebarMenuHtmlMobile .= '<li>
                    <a href="' . $slug . '" class="menu" ' . $target_blank . '>

                        <div class="menu__title">' . $menu['name'];
				if (isset($menu['children'])) {
					$this->sidebarMenuHtmlMobile .= '<i data-feather="chevron-down" class="menu__sub-icon"></i>';
				}
				$this->sidebarMenuHtmlMobile .= '</div></a>';
				if (array_key_exists('children', $menu)) {
					$this->sidebarMenuHtmlMobile .= '<ul class="">';
					//echo the child menu
					$this->getSidebarMenuHtmlForMobile($menu['children']);
					$this->sidebarMenuHtmlMobile .= '</ul>';
				}
				$this->sidebarMenuHtmlMobile .= '</li>';

			}

			$this->sidebarMenuHtmlMobile .= '';
			return $this->sidebarMenuHtmlMobile;
		}
	}

}
