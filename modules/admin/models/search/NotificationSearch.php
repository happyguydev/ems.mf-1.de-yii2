<?php

namespace app\modules\admin\models\search;

use app\modules\admin\models\Notification;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationSearch represents the model behind the search form of `app\modules\admin\models\Notification`.
 */
class NotificationSearch extends Notification {
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['id', 'status', 'created_by'], 'integer'],
			[['notification_name', 'send_to', 'content', 'sent_time', 'created_at'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
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
	public function search($params) {
		$query = Notification::find();

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
			'sent_time' => $this->sent_time,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'created_by' => $this->created_by,
		]);

		$query->andFilterWhere(['like', 'notification_name', $this->notification_name])
			->andFilterWhere(['like', 'send_to', $this->send_to])
			->andFilterWhere(['like', 'content', $this->content]);

		return $dataProvider;
	}
}
