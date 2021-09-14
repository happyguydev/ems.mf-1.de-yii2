  <?php
use app\models\MenuItems;
use app\modules\admin\models\LogoSetting;
$user_role = Yii::$app->user->identity->user_role;
$menu_items = MenuItems::find()->one();
$getMenuHtml = $menu_items->showJsonData($menu_items['data'], 'desktop');
$getMenuHtml1 = $menu_items->showJsonData($menu_items['data'], 'mobile');

$logo_setting = LogoSetting::find()->where(['setting_name' => 'Logo'])->one();
$site_name = Yii::$app->getTable->settings('general', 'site_name');
//$chat_modal = new Chat;
//$unread_chat_count = $chat_modal->unreadCountAll();
$role = $user_role == 'admin' ? $user_role : '';
?>
   <div class="mobile-menu md:hidden">
            <div class="mobile-menu-bar">
                <a href="" class="flex mr-auto">
                    <img alt="<?=Yii::t('app', $site_name)?>" class="w-8" src="<?=Yii::getAlias('@web')?>/web/logo/<?=$logo_setting->setting_value?>?v=1" style="width:<?=$logo_setting->setting_size?>px">
                </a>
                <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
            </div>
            <ul class="border-t border-theme-2 py-5 hidden">
                <li>
                    <a href="<?=Yii::getAlias('@web');?>/<?=$role?>" class="menu menu--active">
                        <div class="menu__icon"> <i data-feather="home"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Dashboard')?> </div>
                    </a>
                </li>
                 <?php if ($user_role == 'admin') {?>


                 <li>
                    <a href="<?=Yii::getAlias('@web');?>/admin/user/index" class="menu">
                        <div class="menu__icon"> <i data-feather="users"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Users')?> </div>
                    </a>
                </li>

                <?php
}
?>

 <?php if (Yii::$app->Utility->hasAccess('customer', 'view')) {?>


                       <li>
                    <a href="<?=Yii::getAlias('@web');?>/customer/index" class="menu">
                        <div class="menu__icon"> <i data-feather="user"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Customers')?> </div>
                    </a>
                </li>
<?php
}
?>
<?php if (Yii::$app->Utility->hasAccess('mail_box', 'view')) {?>
                     <li>
                    <a href="<?=Yii::getAlias('@web');?>/inbox" class="menu">
                        <div class="menu__icon"> <i data-feather="inbox"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Inbox')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unseen_mail" style="display: none">0</div>
                    </a>
                </li>
<?php
}
?>


 <?php if (Yii::$app->Utility->hasAccess('media', 'view')) {?>
                     <li>
                    <a href="<?=Yii::getAlias('@web');?>/media" class="menu">
                        <div class="menu__icon"> <i data-feather="hard-drive"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'File Manager')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_media_count" style="display: none">0</div>
                    </a>
                </li>
                <?php
}
?>


             <?php if (Yii::$app->Utility->hasAccess('task', 'view')) {?>

                  <li>
                    <a href="<?=Yii::getAlias('@web');?>/task" class="menu">
                        <div class="menu__icon"> <i data-feather="check-square"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Task Manager')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_task_count" style="display: none">0</div>
                    </a>
                </li>
<?php
}
?>
 <?php if (Yii::$app->Utility->hasAccess('project', 'view')) {?>
                 <li>
                    <a href="<?=Yii::getAlias('@web');?>/project" class="menu">
                        <div class="menu__icon"> <i data-feather="grid"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Project Manager')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_project_count" style="display: none">0</div>
                    </a>
                </li>
                <?php
}
?>

<?php if (Yii::$app->Utility->hasAccess('leave', 'view')) {?>

                 <li>
                    <a href="<?=Yii::getAlias('@web');?>/leave" class="menu">
                        <div class="menu__icon"> <i data-feather="arrow-down-circle"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Leave')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_leave_count" style="display: none">0</div>

                    </a>
                </li>
                <?php
}
?>

 <?php if (Yii::$app->Utility->hasAccess('appointment', 'view')) {?>

                 <li>
                    <a href="<?=Yii::getAlias('@web');?>/calendar" class="menu">
                        <div class="menu__icon"> <i data-feather="calendar"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Calendar')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_calendar_count" style="display: none">0</div>
                    </a>
                </li>
                <?php
}
?>

             <?php if (Yii::$app->Utility->hasAccess('chat', 'view')) {?>
                 <li>
                    <a href="<?=Yii::getAlias('@web');?>/chat" class="menu">
                        <div class="menu__icon"> <i data-feather="message-circle"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Chat')?> </div>
                        <div class="py-1 px-2 rounded-full text-xs bg-theme-9 text-white cursor-pointer font-medium unread_chat_count" style="display: none">0</div>
                    </a>
                </li>
                <?php
}
?>



                    <li>
                        <a href="javascript:void(0)" class="menu parent-menu">
                            <div class="menu__icon"> <i data-feather="settings"></i> </div>
                            <div class="menu__title"><?=Yii::t('app', 'Settings')?><div class="menu__sub-icon"> <i data-feather="chevron-down"></i> </div>
                            </div>
                        </a>
                        <ul class="">
                             <?php if ($user_role == 'admin') {?>
                             <li>
                                <a href="<?=Yii::getAlias('@web');?>/admin/general" class="menu">
                                    <div class="menu__icon"> <i data-feather="slack"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'General')?></div>
                                </a>
                            </li>

                              <li>
                                <a href="<?=Yii::getAlias('@web');?>/admin/logo-setting" class="menu">
                                    <div class="menu__icon"> <i data-feather="image"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'Logo Setting')?></div>
                                </a>

 <li>
                    <a href="<?=Yii::getAlias('@web');?>/menu-items" class="menu">
                        <div class="menu__icon"> <i data-feather="menu"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Menu Manager')?> </div>
                    </a>
                </li>

                    <li>
                    <a href="<?=Yii::getAlias('@web');?>/admin/user-group" class="menu">
                        <div class="menu__icon"> <i data-feather="user-check"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'User Role Manager')?> </div>
                    </a>
                </li>
                  <li class="hidden">
                    <a href="<?=Yii::getAlias('@web');?>/mailbox/email-client" class="menu">
                       <div class="menu__icon"> <i data-feather="inbox"></i> </div>
                        <div class="menu__title"><?=Yii::t('app', 'Email Clients')?> </div>
                    </a>
                </li>

                            <?php
}
?>
                              <li>
                                <a href="<?=Yii::getAlias('@web');?>/user/update-profile" class="menu">
                                    <div class="menu__icon"> <i data-feather="edit-3"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'Edit Profile')?></div>
                                </a>
                            </li>

                              <li>
                                <a href="<?=Yii::getAlias('@web');?>/user/view" class="menu">
                                    <div class="menu__icon"> <i data-feather="eye"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'View Profile')?></div>
                                </a>
                            </li>
                            <li>
                                <a href="<?=Yii::getAlias('@web');?>/site/change-password" class="menu">
                                    <div class="menu__icon"> <i data-feather="key"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'Change Password')?></div>
                                </a>
                            </li>


                              <li>
                                <a href="javascript:void(0)" onclick="logout()" class="menu">
                                    <div class="menu__icon"> <i data-feather="log-out"></i> </div>
                                    <div class="menu__title"><?=Yii::t('app', 'Logout')?></div>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="javascript:void(0)" class="menu parent-menu">
                            <div class="menu__icon"> <i data-feather="external-link"></i> </div>
                            <div class="menu__title"><?=Yii::t('app', 'External Links')?><div class="menu__sub-icon"> <i data-feather="chevron-down"></i> </div>
                            </div>
                        </a>
                         <ul class="">
                    <?=$getMenuHtml1?>
                </ul>
            </li>

            </ul>
        </div>


            <script type="text/javascript">
                $(function(){

    var url = window.location.pathname,
        urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
        // now grab every link from the navigation
        $('.side-nav li a').each(function(){
            // and test its normalized href against the url pathname regexp
            if(urlRegExp.test(this.href.replace(/\/$/,''))){
                $(this).addClass('side-menu--active');
                console.log($('.side-menu--active').parents('li'));
                $(this).parents('li').find('.parent-menu').addClass('side-menu--open side-menu--active');

                $(this).parents('li').find('ul').addClass('side-menu__sub-open');
                $(this).parents('li').find('ul').css('display','block');
            }
        });

});
                updateCounter0();

                function updateCounter0() {
                    $.ajax({
                        url: '<?=Yii::getAlias('@web')?>/site/get-unread-count',
                        dataType: 'json',
                        success:function(response) {
                            $('.unread_chat_count').html(response.chat_count);
                            $('.unread_task_count').html(response.task_count);
                            $('.unread_project_count').html(response.project_count);
                            $('.unread_media_count').html(response.media_count);
                            $('.unread_calendar_count').html(response.calendar_count);
                            $('.unread_leave_count').html(response.leave_count);
                            $('.unseen_mail').html(response.inbox_count);
                            if(response.inbox_count=='0') {
                                $('.unseen_mail').css('display','none');
                            } else{
                                $('.unseen_mail').css('display','block');
                            }
                            if(response.chat_count=='0') {
                                $('.unread_chat_count').css('display','none');
                            } else {
                                $('.unread_chat_count').css('display','block');
                            }
                            if(response.media_count=='0') {
                                $('.unread_media_count').css('display','none');
                            } else {
                                 $('.unread_media_count').css('display','block');
                            }
                            if(response.leave_count=='0') {
                                $('.unread_leave_count').css('display','none');
                            } else {
                                 $('.unread_leave_count').css('display','block');
                            }
                            if(response.task_count=='0') {
                                $('.unread_task_count').css('display','none');
                            } else {
                                $('.unread_task_count').css('display','block');
                            }


                            if(response.project_count=='0') {
                                $('.unread_project_count').css('display','none');
                            } else {
                                $('.unread_project_count').css('display','block');
                            }
                             if(response.calendar_count=='0') {
                                $('.unread_calendar_count').css('display','none');
                            } else {
                                $('.unread_calendar_count').css('display','block');
                            }
                        }
                    })

}
setInterval('updateCounter0()', 10*1000); // refresh div after 30 secs
            </script>