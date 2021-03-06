<?php

namespace app\modules\admin\models\search;

use app\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['id', 'status', 'created_at', 'updated_at', 'email_verified', 'phone_verified'], 'integer'],
			[['name', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone', 'user_role'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $cond) {
		$query = User::find()->where($cond);

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'email_verified' => $this->email_verified,
			'phone_verified' => $this->phone_verified,
		]);

		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'auth_key', $this->auth_key])
			->andFilterWhere(['like', 'password_hash', $this->password_hash])
			->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'user_role', $this->user_role]);

		return $dataProvider;
	}
}
