document.addEventListener('DOMContentLoaded', function() {

    // $('body').on('click', '.datetimepicker', function() {
    //     $(this).not('.hasDateTimePicker').datetimepicker({
    //         controlType: 'select',
    //         changeMonth: true,
    //         changeYear: true,
    //         dateFormat: "dd-mm-yy",
    //         timeFormat: 'HH:mm:ss',
    //         yearRange: "1900:+10",
    //         showOn:'focus',
    //         firstDay: 1
    //     }).focus();
    // });

var startDateTextBox = $('.start-date-picker');
var endDateTextBox = $('.end-date-picker');
var selectedGroup = $('#selectedGroup').val();

$('body').on('click', '.datetimepicker', function() {
   if($(this).not('.hasDateTimePicker') ) {
$.timepicker.datetimeRange(
    startDateTextBox,
    endDateTextBox,
    {
        minInterval: (1000*60*60), // 1hr
        dateFormat: 'yy-mm-dd', 
        timeFormat: 'H:mm',
        start: {}, // start picker options
        end: {} // end picker options                   
    }
);
}
});

// startDateTextBox.datetimepicker({ 
//      dateFormat: "dd-mm-yy",
//      timeFormat: 'HH:mm:ss',
//      yearRange: "1900:+10",
//     onClose: function(dateText, inst) {
//         if (endDateTextBox.val() != '') {
//             var testStartDate = startDateTextBox.datetimepicker('getDate');
//             var testEndDate = endDateTextBox.datetimepicker('getDate');
//             if (testStartDate > testEndDate)
//                 endDateTextBox.datetimepicker('setDate', testStartDate);
//         }
//         else {
//             endDateTextBox.val(dateText);
//         }
//     },
//     onSelect: function (selectedDateTime){
//         endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
//     }
// });
// endDateTextBox.datetimepicker({ 
//     dateFormat: "dd-mm-yy",
//     timeFormat: 'HH:mm:ss',
//     yearRange: "1900:+10",
//     onClose: function(dateText, inst) {
//         if (startDateTextBox.val() != '') {
//             var testStartDate = startDateTextBox.datetimepicker('getDate');
//             var testEndDate = endDateTextBox.datetimepicker('getDate');
//             if (testStartDate > testEndDate)
//                 startDateTextBox.datetimepicker('setDate', testEndDate);
//         }
//         else {
//             startDateTextBox.val(dateText);
//         }
//     },
//     onSelect: function (selectedDateTime){
//         startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate') );
//     }
// });
    $(".colorpicker").colorpicker();
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        header: {
            left:'title',
            center: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            right:  'prev,next today'
        },
        eventTimeFormat: { // like '14:30:00'
    hour: '2-digit',
    minute: '2-digit',
    meridiem: true,
    hour12:false
  },
        navLinks: true, // can click day/week names to navigate views
        businessHours: false, // display business hours
        editable: true,
        timeFormat: 'H:mm',
        //uncomment to have a default date
        //defaultDate: '2020-04-07',
        events: appointment_url+'show-data?groupId='+selectedGroup,
        eventDrop: function(arg) {
            var start = arg.event.start.toDateString()+' '+arg.event.start.getHours()+':'+arg.event.start.getMinutes()+':'+arg.event.start.getSeconds();
            if (arg.event.end == null) {
                end = start;

            } else {
                var end = arg.event.end.toDateString()+' '+arg.event.end.getHours()+':'+arg.event.end.getMinutes()+':'+arg.event.end.getSeconds();
            }
            //  $('.js-example-basic-multiple').select2();

            $.ajax({
              url:appointment_url+"update-data?id="+arg.event.id,
              type:"POST",
              data:{id:arg.event.id, start:start, end:end},
            });
        },
        eventResize: function(arg) {
            var start = arg.event.start.toDateString()+' '+arg.event.start.getHours()+':'+arg.event.start.getMinutes()+':'+arg.event.start.getSeconds();
            var end = arg.event.end.toDateString()+' '+arg.event.end.getHours()+':'+arg.event.end.getMinutes()+':'+arg.event.end.getSeconds();

              //$('.js-example-basic-multiple').select2();

            $.ajax({
              url:appointment_url+"update-data?id="+arg.event.id,
              type:"POST",
              data:{id:arg.event.id, start:start, end:end},
            });
        },
        eventClick: function(arg) {
            var id = arg.event.id;

              $('.fc-event').attr('data-target','#editeventmodal');
            $('.fc-event').attr('data-toggle','modal');

            
            $('#deleteEvent').attr('data-id', id); 
            $('.edit-assignee-select .tail-select-container').html('');
           // $('.js-example-basic-multiple').select2();


            $.ajax({
              url:appointment_url+"get-data",
              type:"POST",
              dataType: 'json',
              data:{id:id},
              success: function(data) {

                       // 

                
                    $('#editEventTitle').val(data.title);
                    $('#editEventId').val(data.id);
                     $('#editGroup').val(data.group);
                    $('#editStartDate').val(data.start);
                    $('#editEndDate').val(data.end);
                    $('#editColor').val(data.color);
                    $('#editTextColor').val(data.textColor);
                    $('#editAssignee').val(data.assignee);
                    if(data.showDelete==1) {
                        $('#deleteEvent').show();
                    } else {
                        $('#deleteEvent').hide();
                    }
                      var arrayLength = data.assignee.length;
                      var handle_html = '';
                    $('#editAssignee option:selected').each(function() {
    $(this).attr('selected',true);

    /*$('.dropdown-optgroup .dropdown-option').each(function() {
        var data_key = $(this).data('key');
        console.log(data.assignee);
        if($.inArray(data_key, data.assignee) >= 0) {
            $(this).addClass('selected');
        }

    })*/
});
        $('.edit-assignee-select .select-dropdown .dropdown-inner .dropdown-optgroup .dropdown-option').each(function() {
            var data_key = $(this).data('key');
            var option_text = $(this).text();
         
            if($.inArray(data_key, data.assignee) >= 0) {
                handle_html += `<div class="select-handle" data-key="${data_key}" data-group="#" onclick="removeSelectedValue(${data_key})">${option_text}</div>`;
                $(this).addClass('selected');
        }

    })

                    $('.edit-assignee-select .tail-select-container').append(handle_html);
                    $('#editeventmodal').show();
                }
            });

            $('body').on('click', '#deleteEvent', function() {
                if(confirm("Are you sure you want to remove it?")) {
                    $.ajax({
                        url:appointment_url+"delete-data",
                        type:"POST",
                        async: true,
                        data:{id:arg.event.id},
                         success: function(data) {
                    //close model
                    $('#editeventmodal').hide();
                    $('#editeventmodal').css('cssText','display:none!important');
                    $('body').removeClass('overflow-y-hidden');
                    $('body').css('padding-right','');
                    console.log(data);

                    //refresh calendar
                    // calendar.refetchEvents(); 
                     location.reload();
                }
                     });         
                }
            });
            
            calendar.refetchEvents();
        }
    });

    calendar.render();



     $('.closemodal').click(function() {
        $('body').removeClass('overflow-y-hidden');
        $('body').css('padding-right','');
        $('.edit-assignee-select .tail-select-container').html('');
        location.reload();
     })

     $(document).ready(function() {

    $('.group_detail').click(function(event) {
        var data_id = $(this).data('id');
        $('#selectedGroup').val(data_id);
        window.location = appointment_url+"index?groupId="+data_id;
    });
})


    $('#createEvent').submit(function(event) {

        // stop the form refreshing the page
        event.preventDefault();

        $('.form-group').removeClass('has-error'); // remove the error class
        $('.help-block').remove(); // remove the error text

        // process the form
        $.ajax({
            type        : "POST",
            url         : appointment_url+'save-data',
            data        : $(this).serialize(),
            dataType    : 'json',
            encode      : true
        }).done(function(data) {

            // insert worked
            if (data.success) {
                
                //remove any form data
                $('#createEvent').trigger("reset");

                //close model
                $('#addeventmodal').removeClass('show');
                // $("#addeventmodal").css("cssText", "display: none !important;");
                $('body').removeClass('overflow-y-hidden');
                $('body').css('padding-right','');

               // alert(data.message);

                //refresh calendar
                location.reload();

            } else {

                alert(data.message);

            }

        });
    });

    $('#editEvent').submit(function(event) {

        // stop the form refreshing the page
        event.preventDefault();

        $('.form-group').removeClass('has-error'); // remove the error class
        $('.help-block').remove(); // remove the error text

        //form data
        var id = $('#editEventId').val();
        var group = $('#editGroup').val();
        var title = $('#editEventTitle').val();
        var start = $('#editStartDate').val();
        var end = $('#editEndDate').val();
        var color = $('#editColor').val();
        var textColor = $('#editTextColor').val();
        var assignee = $('#editAssignee').val();

        // process the form
        $.ajax({
            type        : "POST",
            url         : appointment_url+'update-data?id='+id,
            data        : {
                id:id,
                group:group, 
                title:title, 
                start:start,
                end:end,
                color:color,
                text_color:textColor,
                assignee:assignee
            },
            dataType    : 'json',
            encode      : true
        }).done(function(data) {

            // insert worked
            if (data.success) {
                
                //remove any form data
                $('#editEvent').trigger("reset");

                //close model
                $('#editeventmodal').hide();

                $('#editeventmodal').css("cssText", "display: none !important;");
                $('body').removeClass('overflow-y-hidden');
                $('body').css('padding-right','');

                //refresh calendar
                // calendar.refetchEvents();
                 location.reload();

            } else {

                //if error exists update html
                if (data.errors.date) {
                    $('#date-group').addClass('has-error');
                    $('#date-group').append('<div class="help-block">' + data.errors.date + '</div>');
                }

                if (data.errors.title) {
                    $('#title-group').addClass('has-error');
                    $('#title-group').append('<div class="help-block">' + data.errors.title + '</div>');
                }

            }

        });
    });
});
