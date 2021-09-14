<?php

namespace app\models;

use app\models\AuthItemChild;
use Yii;
use yii\base\Model;

class AccessCheck extends Model {
	public $featureName; // blog_comment, post, media etc
	public $permissionName; // create,update,view,delete
	public $userRole;
	public $userId;
	public $ownPermission;
	public $otherPermission;

	public function __construct($featureName, $permissionName) {
		$this->getCurrentUser();
		$this->featureName = $featureName;
		$this->permissionName = $permissionName;
		$this->makeName();
		// return parent::init();
	}
	/*
		     *get role of user by userId
		     * return string;
	*/
	public function getRoleByUser() {
		return $this->userId;
	}

	/*
		     * get list of permission for current role
		     * return [];
	*/
	public function getPermissionsByRole() {
		$items = AuthItemChild::find()
			->select('child')
			->where(['parent' => $this->userRole])
			->asArray()
			->all();

		$perms = array_map(function ($d) {
			return $d['child'];
		}, $items);

		return $perms;

	}
	public function makeName() {
		$this->ownPermission = strtolower($this->permissionName) . '_' . strtolower($this->featureName);
		$this->otherPermission = strtolower($this->permissionName) . '_other_' . strtolower($this->featureName);

	}
	public function hasPermission() {
		if ($this->userRole == 'admin') {
			return true;
		} else {
			return ($this->hasOwnPermission() | $this->hasOthersPermission());
		}

	}

	/* check if user have specific permission for its own
		    return true | false;
	*/
	public function hasOwnPermission() {
		$getPerms = $this->getPermissionsByRole();
		if (in_array($this->ownPermission, $getPerms)) {
			return true;
		}
	}

	/* check if user have specific permission for its other
		    return true | false;
	*/
	public function hasOthersPermission() {
		$getPerms = $this->getPermissionsByRole();
		if (in_array($this->otherPermission, $getPerms)) {
			return true;
		}
	}

	/*
		     * get loggedin user from sessions
		     * return []
	*/
	public function getCurrentUser() {
		$this->userId = Yii::$app->user->identity->id;
		$this->userRole = Yii::$app->user->identity->user_role;

	}

}
