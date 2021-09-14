<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Customer */
/* @var $form yii\widgets\ActiveForm */
$countryArray = Yii::$app->Utility->dropDownArray('tbl_country', 'name', 'name', ['status' => 1]);
?>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<div class="customer-form">

    <?php $form = ActiveForm::begin(['options'=>["autocomplete"=>"off"]]);?>

    <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading">
                                <div class="font-medium text-lg text-center"><?=Yii::t('app', 'Name / General')?></div>
                        </div>
    <div class="row mt-3">
<div class="col-md-6">
    <?=$form->field($model, 'first_name')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'First Name')])?>
</div>
<div class="col-md-6">

    <?=$form->field($model, 'last_name')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Last Name')])?>
</div>
</div>
<div class="row">
    <div class="col-md-4">

    <?=$form->field($model, 'customer_number')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Customer Number')])?>
</div>

<div class="col-md-4">

    <?=$form->field($model, 'company_name')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Company Name')])?>
</div>

<div class="col-md-4">


    <?=$form->field($model, 'dob')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Date Of Birth')])?>
</div>
</div>


    <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading mt-2">
                                <div class="font-medium text-lg text-center"><?=Yii::t('app', 'Contact Details')?></div>
                        </div>

<div class="row mt-3">
    <div class="col-md-4">


    <?=$form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Email')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Phone')])?>
</div>

<div class="col-md-4">


    <?=$form->field($model, 'mobile')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Mobile')])?>
</div>
</div>
<div class="row">
<div class="col-md-4">


    <?=$form->field($model, 'assistant_name')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Assistant Name')])?>
</div>


<div class="col-md-4">


    <?=$form->field($model, 'assistant_phone')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Assistant Phone')])?>
</div>



<div class="col-md-4">


    <?=$form->field($model, 'fax')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Fax')])?>
</div>

</div>
<div class="row">
    <div class="col-md-4">


    <?=$form->field($model, 'website')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Website')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'xing')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Xing')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'instagram')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Instagram')])?>
</div>
    </div>
<div class="row">

<div class="col-md-4">


    <?=$form->field($model, 'linkedin')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Linked In')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'facebook')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Facebook')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'twitter')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Twitter')])?>
</div>
</div>

    <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading mt-2">
        <div class="font-medium text-lg text-center"><?=Yii::t('app', 'Bank Details')?></div>
    </div>
    <div class="row">

      <div class="col-md-6">


    <?=$form->field($model, 'account_owner')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Account Owner')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'bank_name')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Bank Name')])?>
</div>
</div>

 <div class="row">

      <div class="col-md-6">


    <?=$form->field($model, 'iban')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'IBAN')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'swift_bic')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Swift BIC')])?>
</div>
</div>


 <div class="row">

      <div class="col-md-6">


    <?=$form->field($model, 'account_number')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Account Number')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'blz')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'BLZ')])?>
</div>
</div>

<div class="row">

      <div class="col-md-6">


    <?=$form->field($model, 'vat_id_number')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'VAT ID Number')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'tax_number')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'TAX Number')])?>
</div>
</div>

<div class="row">

      <div class="col-md-6">


    <?=$form->field($model, 'sepa_direct_debit_mandate')->textInput(['class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'SEPA-Lastschriftmandat')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'date_of_sepa_direct_debit_mandate')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Datum des SEPA-Lastschriftmandats')])?>
</div>
</div>
  <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading mt-2">
                                <div class="font-medium text-lg text-center"><?=Yii::t('app', 'Address Details')?></div>
                        </div>
                        <div class="row">
<div class="col-md-6">


    <?=$form->field($model, 'address')->textarea(['rows' => 2, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Address')])?>
</div>

<div class="col-md-6">


    <?=$form->field($model, 'other_address')->textarea(['rows' => 2, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Other Address'), 'autocomplete' => 'off'])?>
</div>
</div>
<div class="row">
<div class="col-md-6">


    <?=$form->field($model, 'city')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'City')])?>
</div>
<div class="col-md-6">


    <?=$form->field($model, 'other_city')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Other City')])?>
</div>
</div>
<div class="row">
<div class="col-md-6">


    <?=$form->field($model, 'state')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'State')])?>
</div>
<div class="col-md-4">


    <?=$form->field($model, 'other_state')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Other State')])?>
</div>
</div>
<div class="row">
<div class="col-md-6">


    <?=$form->field($model, 'postal_code')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Postal Code')])?>
</div>


<div class="col-md-6">


    <?=$form->field($model, 'other_postal_code')->textInput(['maxlength' => true, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Other Postal Code')])?>
</div>
</div>
<div class="row">
<div class="col-md-6">


    <?=$form->field($model, 'country')->dropDownList($countryArray, ['class' => 'input w-full border mt-2', 'prompt' => Yii::t('app', 'Select Country')])?>
</div>
<div class="col-md-6">

    <?=$form->field($model, 'other_country')->dropDownList($countryArray, ['class' => 'input w-full border mt-2', 'prompt' => Yii::t('app', 'Select Other Country')])?>
</div>
</div>

 <div class="rounded-md px-5 py-4 mb-2 border text-gray-700 dark:text-gray-300 dark:border-dark-5 text-center text-heading mt-2">
                                <div class="font-medium text-lg text-center">Description Information</div>
                        </div>

<div class="mt-3">


    <?=$form->field($model, 'description')->textarea(['rows' => 2, 'class' => 'input w-full border mt-2', 'placeholder' => Yii::t('app', 'Description')])?>
</div>
<div class="mt-3">
    <?=$form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['class' => "input w-full border mr-2"]);?>

    </div>

    <div class="submitButton">
        <?=Html::submitButton('Save', ['class' => 'button text-white bg-theme-1 xl:mr-3 align-top'])?>
    </div>

    <?php ActiveForm::end();?>

</div>
<style type="text/css">
    .text-heading {
        background: #8080802e;
    }
</style>

<script>
  const picker = new Litepicker({ 
    element: document.getElementById('customer-dob'),
     singleMode: true,
     format:'DD.MM.YYYY',
     dropdowns: {"minYear":1900 ,"maxYear":null,"months":true,"years":true}
  });
  
   const picker2 = new Litepicker({ 
    element: document.getElementById('customer-date_of_sepa_direct_debit_mandate'),
     singleMode: true,
     format:'DD.MM.YYYY',
     dropdowns: {"minYear":1900 ,"maxYear":null,"months":true,"years":true}
  });
</script>