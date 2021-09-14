<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<script type="text/javascript">
var dt= new Date();
var month=dt.getMonth(); // read the current month
var year=dt.getFullYear(); // read the current year

dt=new Date(year, month, 01);//Year , month,date format

var first_day=dt.getDay(); //, first day of present month
document.write('<div class="flex"><div class="font-medium text-base mx-auto"><?=date('F Y')?></div></div>');

dt.setMonth(month+1,0); // Set to next month and one day backward.
var last_date=dt.getDate(); // Last date of present month


var dy=1; // day variable for adjustment of starting date.
document.write ('<div class="grid grid-cols-7 gap-4 mt-5 text-center"><div class="font-medium">Su</div><div class="font-medium">Mo</div><div class="font-medium">Tu</div><div class="font-medium">We</div><div class="font-medium">Th</div><div class="font-medium">Fr</div><div class="font-medium">Sa</div>');
for(i=0;i<=41;i++){
/*if((i%7)==0){document.write("</tr><tr>");} // if week is over then start a new line
*/if((i>= first_day) && (dy<= last_date)){
    if(jQuery.inArray(dy, [<?=$highlighted_dates?>]) === -1) {
    var date_class = (dy==<?=date('d')?>) ? 'py-1 bg-theme-1 dark:bg-theme-1 text-white rounded relative' : 'py-1 rounded relative';
document.write(`<div class="${date_class}">${dy}</div>`);
} else {
document.write(`<div class="py-1 bg-theme-18 dark:bg-theme-9 rounded relative">${dy}</div>`);
}
dy=dy+1;
}else {
document.write(`<div class="py-1 rounded relative text-gray-600"></div>`);} // Blank dates.
} // end of for loop

document.write("</div>")
</script>

