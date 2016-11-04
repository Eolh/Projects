<head>
    <!-- start: PAGE SETTINGS -->
    <title> it's a FullCalendar Test!!! </title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/fullcalendar/fullcalendar.css">
    <!--<link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap-fullcalendar.css">-->
    <!-- end:   CSS -->

    <!-- start: script -->
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lang/AAtest.js"></script>
    <!-- end:   script -->

    <script>
        /*
        *
        ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday']
         */
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                lang: "AAtest",
                /*titleFormat: 'YYYY MM DD',
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                    'August', 'September', 'October', 'November', 'December'],
                dayNamesShort: ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
                    'Thursday', 'Friday', 'Saturday'],*/
                windowResize: function(view) {
                    //alert('The calendar has adjusted to a window resize');
                },
                dayClick: function (data) {
                    alert('a day has been clicked!' + data);
                },
                eventSources: [
                    {
                        url: "/test.php",
                        color: "red",
                        textColor : "white"
                    }
                ],
                events: [
                    {
                        title: 'All Day Event',
                        start: '2016-04-01'
                    }
                ],
                eventMouseover:function () {
                    $(this).css ("background-color", "red")
                },
                eventMouseout:function (event, jsEvent, view) {
                    $(this).css ("background-color", "green")
                }
            });
        });
    </script>
</head>
<body>
<div id='calendar'></div>
</body>