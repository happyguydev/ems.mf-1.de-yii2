<div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
        <?php if (Yii::$app->session->hasFlash('InvalidVarification')): ?>
        <div class="rounded-md px-5 py-4 mb-2 bg-theme-6 text-white">
          <?=Yii::t('app', 'Something Went wrong')?>.
          <?=Yii::t('app', 'Either Your key is not valid or  already used')?>.
          <?=Yii::t('app', 'Contact us for further help')?>.
        </div>
        <?php endif;?>
        <?php if (Yii::$app->session->hasFlash('keyExpire')): ?>
         <div class="rounded-md px-5 py-4 mb-2 bg-theme-12 text-white">

          <?=Yii::t('app', 'Your key is expire please generate a new key for to reset your password varification')?>
        </div>
        <?php endif;?>
        <?php if (Yii::$app->session->hasFlash('VarificationNotComplete')): ?>
         <div class="rounded-md px-5 py-4 mb-2 bg-theme-6 text-white">
          <?=Yii::t('app', 'Something Went wrong')?>.
          <?=Yii::t('app', 'Either Your key is not valid or  already used')?>.
          <?=Yii::t('app', 'Contact us for further help')?>.<br/>
          <?=Yii::t('app', 'Thank You')?>
        </div>
        <?php endif;?>
        <?php if (Yii::$app->session->hasFlash('VarificationComplete')): ?>
         <div class="rounded-md px-5 py-4 mb-2 bg-theme-9 text-white">

          <?=Yii::t('app', 'Success')?>.
          <?=Yii::t('app', 'Your Verification of email is successful')?>.<br/>
          <?=Yii::t('app', 'Thank You')?>
        </div>
        <?php endif;?>

            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-right">

            <a class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top forget1"  href="javascript:void(0)" onclick="getLoginForm()"><?=Yii::t('app', 'Go Login ?')?></a>
          </div>
      </div>

</div>
<?php if (!Yii::$app->user->isGuest) {?>
<script type="text/javascript">
function getDash() {
window.location = "<?=Yii::getAlias('@web');?>/<?=\Yii::$app->user->identity->user_role;?>/";
}

</script>
<?php }?>
<script type="text/javascript">
  function getLoginForm () {
     window.location.href="<?=Yii::getAlias('@web')?>/site/login";
    }
</script>