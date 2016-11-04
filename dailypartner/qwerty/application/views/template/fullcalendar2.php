<!DOCTYPE html>
<html>
<head>
    <!-- start: PAGE SETTINGS -->
    <title> it's a FullCalendar Test!!! </title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap/css/bootstrap.css">
    <!-- end:   CSS -->

    <!-- start: script -->
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lib/jquery-ui.custom.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lang/AAtest.js"></script>
    <script src="http://localhost:8088/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <script>

        $(document).ready(function () {

            var calendar = $('#calendar').fullCalendar({
                theme         : false,
                firstDay      : 0, /* 날짜의 기준일 0: sunday */
                //hiddenDays : [2,4], /* 선택한 날짜를 숨긴다. */
                customButtons : {
                    myCustomButton : {
                        text  : 'custom!',
                        click : function () {
                            alert('clicked the custom button!');
                        }
                    }
                },
                header        : {
                    left   : 'prev,next today myCustomButton',
                    center : 'title',
                    right  : 'month,agendaWeek,agendaDay'
                },
                editable      : true,
                /*windowResize  : function (view) {
                 //alert('The calendar has adjusted to a window resize');
                 },*/
                dayClick      : function (date, jsEvent, view) {

                    //alert('Clicked on: ' + date.format() + " | " + 'Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY + " | " + 'Current view: ' + view.name);

                    // change the day's background color just for fun
                    /*$(this).css('background-color', 'red');*/

                    /*$.ajax({

                     });*/
                },
                selectable    : true,
                selectHelper  : true,
                select        : function (start, end, allDay) {
                    $('#uploadSchedule').modal('show');
                    //var title = prompt('Sample Textbox: <textarea>ssibal</textarea>');
                    var title = false;
                    if (title) {
                        start = moment(start).format('YYYY-MM-DD HH:mm:ss');
                        end = moment(end).format('YYYY-MM-DD HH:mm:ss');
                        $.ajax({
                            url     : 'http://localhost:8088/ajaxHandler/calendar',
                            data    : 'title=' + title + '&start=' + start + '&end=' + end,
                            type    : "POST",
                            success : function (json) {
                                alert(json);
                            }
                        });
                        calendar.fullCalendar('renderEvent',
                            {
                                title  : title,
                                start  : start,
                                end    : end,
                                allDay : allDay
                            },
                            true // make the event "stick"
                        );
                    }
                    calendar.fullCalendar('unselect');
                },
                /* start: events  */
                events         :
                <?php echo json_encode($calendarList); ?>
                /* 넘겨준 데이터를 json 형식으로 변경하여서 출력한다. */
                ,
                eventMouseover : function () {
                    $(this).css("background-color", "purple")
                }, /* 마우스를 일정에 올리면 */
                eventMouseout  : function (event, jsEvent, view) {
                    $(this).css("background-color", "green")
                }, /* 마우스를 일정에서 때면 */
                /* end: events */
            });
        });
    </script>
    <!-- end:   script -->
</head>
<body>
<div id='calendar'></div>
<div class="modal fade" id="uploadSchedule">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    &times;
                </a>

                <h3>Modal-header</h3>
            </div>
            <div class="modal-body">
                <a type='button'>
                    <p>Modal-body</p>
                </a>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary">
                    Footer
                </a>
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>