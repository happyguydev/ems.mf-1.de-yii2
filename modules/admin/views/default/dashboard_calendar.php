<?php
use app\models\CalendarGroup;
use app\models\User;
$dir = Yii::getAlias('@web') . '/themes/admin/dist/full-calendar/';
$this->title = Yii::t('app', 'Appointments');
$this->params['breadcrumbs'][] = $this->title;
$group = CalendarGroup::find()->all();
$users = User::find()->where(['status' => 1])->andWhere(['!=', 'user_role', 'admin'])->all();

$groupId = isset($_GET['groupId']) ? $_GET['groupId'] : 1;

?>
<script type="text/javascript">
var url = "<?=$dir?>";
var appointment_url = '<?=Yii::getAlias('@web')?>/calendar/';
</script>

    <link href='<?=$dir;?>packages/core/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/daygrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/timegrid/main.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/list/main.css' rel='stylesheet' />
    <link href="<?=$dir;?>packages/jqueryui/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link href='<?=$dir;?>packages/datepicker/datepicker.css' rel='stylesheet' />
    <link href='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.css' rel='stylesheet' />
    <link href='<?=$dir;?>style.css' rel='stylesheet' />

    <script src='<?=$dir;?>packages/core/main.js'></script>
    <script src='<?=$dir;?>packages/daygrid/main.js'></script>
    <script src='<?=$dir;?>packages/timegrid/main.js'></script>
    <script src='<?=$dir;?>packages/list/main.js'></script>
    <script src='<?=$dir;?>packages/interaction/main.js'></script>
    <script src='<?=$dir;?>packages/jqueryui/jqueryui.min.js'></script>
    <script src='<?=$dir;?>packages/datepicker/datepicker.js'></script>
    <script src='<?=$dir;?>packages/colorpicker/bootstrap-colorpicker.min.js'></script>
    <script src='<?=$dir;?>calendar.js'></script>
  <!--  <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script> -->



                    <div class="col-span-12 xl:col-span-8 xxl:col-span-9">

<div class="box p-5">
<div class="full-calendar fc fc-media-screen fc-direction-ltr fc-theme-standard" id="calendar"></div>
</div>
</div>

<style type="text/css">
    .fc-content {
        font-size:12px;
        font-weight: bold;
        padding:3px;
        text-align: center;
    }
    #ui-datepicker-div {
        z-index: 50002!important;
    }

    @media (max-width: 1023px) {
    .full-calendar .fc-toolbar {
        flex-direction: column;
    }
}
#calendar{
    margin:auto;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:first-child {
    order: 3;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(2) {
    order: 1;
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) {
    order: 2;
}
@media (max-width: 1023px) {
    .full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) {
        margin-top: 0.75rem;
        margin-bottom: 0.75rem;
    }
}
.full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(3) .fc-button-group button {
    width: 5rem;
}
.full-calendar .fc-toolbar .fc-left h2{
    font-weight: 500;
    font-size: 1.125rem;
    line-height: 1.75rem;
}
.full-calendar .fc-toolbar-chunk .fc-button-primary:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}
.full-calendar .fc-toolbar-chunk .fc-button-primary:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
.full-calendar.fc-theme-standard .fc-list,
.full-calendar.fc-theme-standard .fc-scrollgrid,
.full-calendar.fc-theme-standard td,
.full-calendar.fc-theme-standard th {
    --tw-border-opacity: 1;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}
.dark .full-calendar.fc-theme-standard .fc-list,
.dark .full-calendar.fc-theme-standard .fc-scrollgrid,
.dark .full-calendar.fc-theme-standard td,
.dark .full-calendar.fc-theme-standard th {
    --tw-border-opacity: 1;
    border-color: rgba(63, 72, 101, var(--tw-border-opacity));
}
.full-calendar table {
    border-radius: 0.375rem;
}
.full-calendar table tr th {
    padding: 0.75rem 1.25rem;
}
.full-calendar .fc-daygrid-event-harness {
    margin-left: 1.25rem;
    margin-right: 1.25rem;
}
.full-calendar .fc-h-event {
    --tw-bg-opacity: 1;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity));
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
    border-radius: 0.375rem;
}
.full-calendar .fc-event-title-container {
    font-size: 0.75rem;
    line-height: 1rem;
    padding: 0.25rem 0.5rem;
}
.full-calendar .fc-daygrid-event {
    font-size: 0.75rem;
    line-height: 1rem;
}
.full-calendar .fc-daygrid-event-dot {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
    margin-right: 0.5rem;
}
.full-calendar .fc-col-header-cell-cushion,
.full-calendar .fc-daygrid-dot-event .fc-event-title {
    font-weight: 500;
}
.full-calendar .fc-daygrid-more-link {
    font-size: 0.875rem;
    line-height: 1.25rem;
}
.full-calendar .fc-daygrid-day-bottom {
    padding-top: 0.25rem;
}
.full-calendar .fc-day-other {
    --tw-bg-opacity: 1;
    background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-day-other {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.full-calendar .fc-button-primary {
    --tw-bg-opacity: 1;
    background-color: transparent !important;
    --tw-border-opacity: 1;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity)) !important;
    --tw-text-opacity: 1;
    color: rgba(113, 128, 150, var(--tw-text-opacity)) !important;
    text-transform: capitalize !important;
    border-radius: 0.375rem;
    border-width: 1px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    --tw-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    box-shadow: var(--tw-ring-offset-shadow, 0 0 transparent), var(--tw-ring-shadow, 0 0 transparent), var(--tw-shadow);
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 0.15s;
    transition-duration: 0.2s;
}
.full-calendar .fc-button-primary:hover {
    --tw-bg-opacity: 0.9;
    --tw-border-opacity: 0.9;
}
.full-calendar .fc-button-primary:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 transparent);
}
.full-calendar .fc-button-primary:not(button) {
    text-align: center;
}
.full-calendar .fc-button-primary .fc-icon {
    font-size: 1.2em;
}
.full-calendar .fc-button-primary:focus {
    box-shadow: none !important;
}
.full-calendar .fc-button-primary:not(:disabled).fc-button-active,
.full-calendar .fc-button-primary:not(:disabled):active {
    color: #fff !important;
    border-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
}
.full-calendar .fc-list-day-cushion {
    --tw-bg-opacity: 1;
    background-color: rgba(237, 242, 247, var(--tw-bg-opacity)) !important;
    padding: 0.75rem 1.25rem !important;
}
.full-calendar .fc-list-event td {
    padding: 0.75rem 1.25rem;
}
.full-calendar .fc-list-event-dot {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity)) !important;
}
.full-calendar .fc-v-event {
    --tw-bg-opacity: 1;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-v-event {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.full-calendar .fc-v-event {
    --tw-border-opacity: 1;
    border-color: rgba(28, 63, 170, var(--tw-border-opacity));
}
.dark .full-calendar .fc-v-event {
    --tw-border-opacity: 1;
    border-color: rgba(41, 49, 69, var(--tw-border-opacity));
    --tw-shadow: 0 0 transparent;
    box-shadow: var(--tw-ring-offset-shadow, 0 0 transparent), var(--tw-ring-shadow, 0 0 transparent), var(--tw-shadow);
}
.full-calendar .fc-event-time {
    font-size: 0.75rem !important;
    padding-left: 0.125rem;
    padding-right: 0.125rem;
}
.full-calendar .fc-daygrid-more-link {
    padding-left: 0.5rem;
}
.dark .full-calendar .fc-button-primary {
    border-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
    color: rgba(203, 213, 224, var(--tw-text-opacity)) !important;
}
.dark .full-calendar .fc-button-primary:not(:disabled).fc-button-active,
.dark .full-calendar .fc-button-primary:not(:disabled):active {
    border-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
    background-color: rgba(28, 63, 170, var(--tw-bg-opacity)) !important;
}
.dark .full-calendar .fc-day-today {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity));
}
.dark .full-calendar .fc-list-day-cushion {
    --tw-bg-opacity: 1;
    background-color: rgba(41, 49, 69, var(--tw-bg-opacity)) !important;
}
.dark .full-calendar .fc-event:hover td {
    --tw-bg-opacity: 1;
    background-color: rgba(49, 58, 85, var(--tw-bg-opacity));
    --tw-border-opacity: 1;
    border-color: rgba(49, 58, 85, var(--tw-border-opacity));
}

</style>
