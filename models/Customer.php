<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $company_name
 * @property string $email
 * @property string $phone
 * @property string $dob
 * @property string $fax
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string|null $postal_code
 * @property string $country
 * @property string|null $linkedin
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string $created_at
 * @property string|null $updated_at
 */
class Customer extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%customer}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['first_name', 'email', 'phone', 'address', 'city', 'state', 'country', 'status', 'postal_code', 'customer_number'], 'required'],
			[['dob', 'created_at', 'updated_at', 'status', 'other_address', 'other_city', 'other_state', 'other_country', 'other_postal_code', 'assistant_name', 'assistant_phone', 'mobile', 'country', 'xing', 'website', 'instagram', 'customer_number', 'account_owner', 'bank_name', 'iban', 'vat_id_number', 'tax_number', 'sepa_direct_debit_mandate', 'date_of_sepa_direct_debit_mandate', 'swift_bic', 'account_number', 'blz', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'trash'], 'safe'],
			[['address', 'description'], 'string'],
			[['first_name', 'last_name', 'company_name', 'email', 'linkedin', 'facebook', 'twitter'], 'string', 'max' => 255],
			[['phone', 'postal_code'], 'string', 'max' => 15],
			[['fax'], 'string', 'max' => 30],
			[['city', 'state'], 'string', 'max' => 50],
			['email','email']
		];
	}

	public function behaviors() {
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'first_name' => Yii::t('app', 'First Name'),
			'last_name' => Yii::t('app', 'Last Name'),
			'company_name' => Yii::t('app', 'Company Name'),
			'customer_number' => Yii::t('app', 'Customer Number'),
			'website' => Yii::t('app', 'Website'),
			'xing' => Yii::t('app', 'Xing'),
			'instagram' => Yii::t('app', 'Instagram'),
			'email' => Yii::t('app', 'Email'),
			'phone' => Yii::t('app', 'Phone'),
			'dob' => Yii::t('app', 'Dob'),
			'fax' => Yii::t('app', 'Fax'),
			'address' => Yii::t('app', 'Address'),
			'city' => Yii::t('app', 'City'),
			'state' => Yii::t('app', 'State'),
			'postal_code' => Yii::t('app', 'Postal Code'),
			'country' => Yii::t('app', 'Country'),
			'linkedin' => Yii::t('app', 'Linkedin'),
			'facebook' => Yii::t('app', 'Facebook'),
			'twitter' => Yii::t('app', 'Twitter'),
			'account_number' => Yii::t('app', 'Account Number'),
			'bank_name' => Yii::t('app', 'Bank Name'),
			'iban' => Yii::t('app', 'IBAN'),
			'swift_bic' => Yii::t('app', 'Swift BIC'),
			'account_owner' => Yii::t('app', 'Account Owner'),
			'bank_name' => Yii::t('app', 'Bank Name'),
			'vat_id_number' => Yii::t('app', 'VAT Id Number'),
			'tax_number' => Yii::t('app', 'Tax Number'),
			'sepa_direct_debit_mandate' => Yii::t('app', 'SEPA-Lastschriftmandat'),
			'date_of_sepa_direct_debit_mandate' => Yii::t('app', 'Datum des SEPA-Lastschriftmandats'),
			'blz' => Yii::t('app', 'BLZ'),
			'created_by' => Yii::t('app', 'Created By'),
			'updated_by' => Yii::t('app', 'Updated By'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'deleted_by' => Yii::t('app', 'Deleted By'),
			'deleted_at' => Yii::t('app', 'Deleted At'),
			'trash' => Yii::t('app', 'Trash'),
		];
	}
	public function getAddress($value) {
		return $value['address'] . ', ' . $value['city'] . ', ' . $value['state'] . ', ' . $value['postal_code'] . ', ' . $value['country'];
	}

	public function getOtherAddress($value) {
		if ($value['other_address'] != '') {
			return $value['other_address'] . ', ' . $value['other_city'] . ', ' . $value['other_state'] . ', ' . $value['other_postal_code'] . ', ' . $value['other_country'];
		}
	}

	public function getCreatedBy() {
		$user = User::find()->where(['id' => $this->created_by])->one();

		return $user->first_name . ' ' . $user->last_name;
	}

	public function getUpdatedBy() {
		$user = User::find()->where(['id' => $this->updated_by])->one();
		if ($user) {

			return $user->first_name . ' ' . $user->last_name;
		}
	}

	public function getDeletedBy() {
		$user = User::find()->where(['id' => $this->deleted_by])->one();

		if ($user) {
			return $user->first_name . ' ' . $user->last_name;

		}

	}

}
