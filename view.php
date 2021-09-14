<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Order */
$this->title = 'Order Details (#' . $model->order_id . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status_label = $model->status == 1 ? 'success' : 'dark';
$status_name = $model->status == 1 ? 'Completed' : 'Pending';
?>
<div class="panel panel-bordered">
    <div class="panel-heading">
        <h6 class="panel-title text-center" ><i class="icon-paragraph-justify2"></i> <?=Html::encode($this->title)?></h6>
        <div class="ribbon ribbon-badge ribbon-<?=$status_label?>">
            <span class="ribbon-inner"><?=$status_name?></span>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered order">
                <tr>
                    <th>Shop Name</th>
                    <td><a href="<?=Yii::getAlias('@web')?>/admin/shop/view?id=<?=$model['shop_id']?>"><?=$model->shop['title']?></a></td>
                    <th>Final Amount</th>
                    <td><?=$model['final_amount']?></td>
                </tr>
                <tr>
                    <th>Ordered By</th>
                    <td><?=$model->user['first_name'] . ' ' . $model->user['last_name']?></td>
                    <th>Ordered At</th>
                    <td><?=$model['created_at']?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="panel panel-bordered">
    <div class="panel-heading">
        <h6 class="panel-title" ><i class="icon-paragraph-justify2"></i>Shipping Details</h6>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered order">
                <tr>
                    <th>Name</th>
                    <td><?=$model['first_name'] . ' ' . $model['last_name']?></td>
                    <th>State</th>
                    <td><?=$model['state']?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$model['email']?></td>
                    <th>Zipcode</th>
                    <td><?=$model['zip_code']?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?=$model['phone']?></td>
                    <th>Order Amount</th>
                    <td><?=$model['amount']?></td>
                </tr>
                <tr>
                    <th>Address 1</th>
                    <td><?=$model['address1']?></td>
                    <th>Shipping Date</th>
                    <td><?=$model['shipping_date']?></td>
                </tr>
                <tr>
                    <th>Address 2</th>
                    <td><?=$model['address2']?></td>
                    <th>Shipping Time</th>
                    <td><?=$model['shipping_time']?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?=$model['city']?></td>
                    <th>Shipping Charge</th>
                    <td><?=$model['shipping_charge']?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="panel panel-bordered">
    <div class="panel-heading">
        <h6 class="panel-title">Order Item Details</h6>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered order">
                <thead >
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Product Name</th>
                     <th class="text-center">Quantity</th>
                      <th class="text-center">Amount</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php

if (count($orderItems) > 0) {
	foreach ($orderItems as $key => $value) {

		?>
                <tr>
                    <td><?=$key + 1?></td>
                 <td><?=$value->product['title']?></td>
                  <td><?=$value['quantity']?></td>
                   <td><?=$value['amount']?></td>
               </tr>
               <?php
}
}
?>

            </tbody>
            </table>
        </div>
    </div>
</div>
<style type="text/css">
.order th {
font-weight: bold!important;
}
</style>