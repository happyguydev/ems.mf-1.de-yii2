<?php

use app\models\ContractAttachment;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Customer */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$update_url = Yii::getAlias('@web') . '/customer/update?id=' . $model->id;
?>
<div class="grid grid-cols-12 gap-12 mt-5">
  <div class="intro-y col-span-12 lg:col-span-12">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Html::encode($this->title)?>
        </h2>
        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
          <a href="<?=$update_url?>" class="button text-white bg-theme-1 shadow-md mr-2"><?=Yii::t('app', 'Update')?></a>
        </div>
      </div>
      <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
        <div class="p-5" id="basic-table">
          <div class="preview">
            <div class="overflow-x-auto">
              <table class="table">
                <tr>
                  <th><?=Yii::t('app', 'Name')?></th><td><?=$model['first_name']?> <?=$model['last_name']?></td>
                  <th style="width: 150px"><?=Yii::t('app', 'Company Name')?></th><td><?=$model->company_name?></td>
                   <th><?=Yii::t('app', 'Customer Number')?></th><td><?=$model->customer_number?></td>


                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'DOB')?></th><td><?=Yii::$app->getTable->date_format($model->dob)?></td>
                  <th><?=Yii::t('app', 'Email')?></th><td><?=$model->email?></td>
                  <th><?=Yii::t('app', 'Phone')?></th><td><?=$model->phone?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Mobile')?></th><td><?=$model->mobile?></td>
                  <th><?=Yii::t('app', 'Fax')?></th><td><?=$model->fax?></td>
                  <th><?=Yii::t('app', 'Assistant Name')?></th><td><?=$model->assistant_name?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Assistant Phone')?></th><td><?=$model->assistant_phone?></td>
                  <th><?=Yii::t('app', 'Website')?></th><td><?=$model->website?></td>
                  <th><?=Yii::t('app', 'Xing')?></th><td><?=$model->xing?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Instagram')?></th><td><?=$model->instagram?></td>
                  <th><?=Yii::t('app', 'Linked In')?></th><td><?=$model->linkedin?></td>
                  <th><?=Yii::t('app', 'Facebook')?></th><td><?=$model->facebook?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Twitter')?></th><td><?=$model->twitter?></td>
                  <th><?=Yii::t('app', 'Status')?></th><td><?=Yii::$app->Utility->getStatus($model->status)?></td>
                  <th><?=Yii::t('app', 'Created At')?></th><td><?=Yii::$app->getTable->datetime_format($model['created_at'])?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Address')?></th><td colspan="5"><?=$model->getAddress($model)?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Other Address')?></th><td colspan="5"><?=$model->getOtherAddress($model)?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Description')?></th><td colspan="5"><?=$model->description?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

   <div class="intro-y col-span-12 lg:col-span-12 mt-5">
    <!-- BEGIN: Basic Table -->
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-3 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
        <?=Yii::t('app', 'Bank Details')?>
        </h2>
      </div>
      <div class="mt-2 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 dark:border-dark-5 pt-5 lg:pt-0">
        <div class="p-5" id="basic-table">
          <div class="preview">
            <div class="overflow-x-auto">
              <table class="table">
                <tr>
                  <th><?=Yii::t('app', 'Account Owner')?></th><td><?=$model['account_owner']?></td>

                  <th><?=Yii::t('app', 'Bank Name')?></th><td><?=$model['bank_name']?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'IBAN')?></th><td><?=$model['iban']?></td>

                  <th><?=Yii::t('app', 'Swift BIC')?></th><td><?=$model['swift_bic']?></td>

                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'Account Number')?></th><td><?=$model['account_number']?></td>

                  <th><?=Yii::t('app', 'BLZ')?></th><td><?=$model['blz']?></td>

                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'VAT Id Number')?></th><td><?=$model['vat_id_number']?></td>

                  <th><?=Yii::t('app', 'TAX Number')?></th><td><?=$model['tax_number']?></td>
                </tr>
                <tr class="border-grey">
                  <th><?=Yii::t('app', 'SEPA-Lastschriftmandat')?></th><td><?=$model['sepa_direct_debit_mandate']?></td>

                  <th><?=Yii::t('app', 'Datum des SEPA-Lastschriftmandats')?></th><td><?=Yii::$app->getTable->date_format($model['date_of_sepa_direct_debit_mandate'])?></td>
                </tr>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <?php if (Yii::$app->session->hasFlash('successStatus')): ?>
           <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white mt-5 successStatus">
            <?=Yii::$app->session->getFlash('successStatus');?>
            <i data-feather="x" class="w-4 h-4 ml-auto" onclick="hideAlert();"></i>
                </div>
            <?php endif;?>

<div class="intro-y box mt-5">
  <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
    <h2 class="font-medium text-base mr-auto">
    <?=Yii::t('app', 'Contract List')?>
    </h2>
    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
      <a href="javascript:void(0)" onclick="createContract('<?=$model['id']?>')" class="button text-white bg-theme-9 shadow-md mr-2 tooltip" data-toggle="modal" data-target="#create-contract-modal" title="Add New Contract"><?=Yii::t('app', 'Add New')?></a>
    </div>
  </div>
  <div class="p-5" id="responsive-table">
    <div class="preview">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Title')?></th>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Description')?></th>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Contract Amount')?></th>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Start Date')?></th>
              <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'End Date')?></th>

 <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Bill Number')?></th>

   <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Issue Date Of Bill')?></th>

 <th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Paid Date')?></th>

<th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Created At')?></th>
<th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Status')?></th>
<th class="border-b-2 dark:border-dark-5 whitespace-nowrap"><?=Yii::t('app', 'Actions')?></th>
</tr>
</thead>
<tbody>
<?php
if (count($contract) > 0) {
	foreach ($contract as $key => $value) {

		$count_attachments = ContractAttachment::find()->where(['contract_id' => $value['id']])->count();
		?>
<tr>
<td class="border-b whitespace-nowrap"><?=$key + 1?></td>
<td class="border-b whitespace-nowrap"><?=$value['title']?></td>
<td class="border-b whitespace-nowrap"><?=$value['description']?></td>
<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->currency_format($value['contract_amount'])?></td>
<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->date_format($value['start_date'])?></td>
<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->date_format($value['end_date'])?></td>
<td class="border-b whitespace-nowrap"><?=$value['bill_number']?></td>

<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->date_format($value['issue_date'])?></td>

<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->date_format($value['paid_date'])?></td>


<td class="border-b whitespace-nowrap"><?=Yii::$app->getTable->datetime_format($value['created_at'])?></td>
<td>
  <?php
if ($value['status'] == 1) {
			?>
                                        <div class="flex items-center justify-center text-theme-9"> <i data-feather="thumbs-up" class="w-4 h-4 mr-2"></i> <?=Yii::$app->Utility->getStatus($value['status']);?> </div>
                                        <?php
} else {
			?>
                                        <div class="flex items-center justify-center text-theme-6"> <i data-feather="thumbs-down" class="w-4 h-4 mr-2"></i> <?=Yii::$app->Utility->getStatus($value['status']);?> </div>
                                        <?php
}
		?>
  </td>

<td class="border-b whitespace-nowrap">
  <div class="flex justify-center items-center">
  <a class="flex items-center mr-3 text-theme-1 tooltip" href="javascript:void(0)" onclick="updateContract('<?=$value['id']?>');" title="<?=Yii::t('app', 'Edit')?>" data-toggle="modal" data-target="#update-contract-modal"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Edit')?> </a>
<?php
if ($count_attachments > 0) {
			?>
   <a class="flex items-center mr-3 text-theme-9 tooltip" href="javascript:void(0)" onclick="viewAttachment('<?=$value['id']?>');" title="<?=Yii::t('app', 'View Attachments')?>" data-toggle="modal" data-target="#view-contract-attachment-modal"> <i data-feather="file" class="w-4 h-4 mr-1"></i> Attachments </a>
   <?php
}
		?>
    <?php
if ($value['status'] == 1) {
			?>
                                                <a class="flex items-center mr-3 text-theme-6 tooltip" href="<?=Yii::getAlias('@web')?>/customer-contract/status?val=0&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Disable')?>"> <i data-feather="thumbs-down" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Disable')?> </a>
                                                <?php
} else {
			?>

                                                 <a class="flex items-center mr-3 text-theme-9 tooltip" href="<?=Yii::getAlias('@web')?>/customer-contract/status?val=1&id=<?=$value['id']?>" title="<?=Yii::t('app', 'Enable')?>"> <i data-feather="thumbs-up" class="w-4 h-4 mr-1"></i> <?=Yii::t('app', 'Enable')?> </a>

                                                <?php
}
		?>
  </div>
</td>
</tr>
<?php
}
} else {
	?>
<tr>
<td colspan="10" class="text-theme-6 text-center">
  <?=Yii::t('app', 'No Contract Added yet!')?>
</td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<!--BEGIN modal-->
<div class="modal" id="create-contract-modal">
     <div class="modal-content" id="create-contract-content" style="position: absolute;right: 20%;width:800px;top:5%"></div>
 </div>

 <div class="modal" id="update-contract-modal">
     <div class="modal-content" id="update-contract-content" style="position: absolute;right: 20%;width:800px;top:5%"></div>
 </div>

 <div class="modal" id="view-contract-modal">
     <div class="modal-content" id="view-contract-content" style="position: absolute;right: 20%;width:800px;top:5%"></div>
 </div>

 <div class="modal" id="view-contract-attachment-modal">
     <div class="modal-content" id="view-contract-attachment" style="position: absolute;right: 20%;width:800px;top:5%"></div>
 </div>

<!--END modal-->

<style type="text/css">
.border-grey {
border-top: 1px solid #8080801f;
}
.modal .modal__content {
  width:800px;
}
</style>

<script type="text/javascript">
  function createContract(id) {
    $('#create-contract-modal').show();
    $('#create-contract-content').load(`<?=Yii::getAlias('@web')?>/customer-contract/create?id=${id}`);
  }

   function updateContract(id) {
    $('#update-contract-modal').show();
    $('#update-contract-content').load(`<?=Yii::getAlias('@web')?>/customer-contract/update?id=${id}`);
  }

   function viewContract(id) {
    $('#view-contract-modal').show();
    $('#view-contract-content').load(`<?=Yii::getAlias('@web')?>/customer-contract/view?id=${id}`);
  }
 function viewAttachment(id) {
    $('#view-contract-attachment-modal').show();
    $('#view-contract-attachment').load(`<?=Yii::getAlias('@web')?>/customer-contract/view-attachment?contract_id=${id}`);
  }

  function hideAlert() {
    $('.successStatus').hide();
  }
</script>

