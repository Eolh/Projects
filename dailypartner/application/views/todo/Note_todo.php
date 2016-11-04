<?php
$check = 1;
$page = 2;
?>
<div id="TodoList">
    <div class="bb-custom-wrapper">
        <div id="bb-bookblock" class="bb-bookblock">
                <div id="dncalendar-container">

                </div>
            <div class="bb-item">
                <div class="bb-custom-firstpage">
                    <h1>Diary</h1>
                </div>
                <div class="bb-custom-side">
                    <p>Test space</p>
                </div>
            </div>

            <?php
            foreach ($dateintodo as $date) {
                /*시작 div 생성 (1)*/
                if ($check % 4 == 1) {
                    echo "<div class='bb-item' id='$page'>";
                    $check++; //1->2
                    $page++;
                }
                /*내용부분 (2:left, 3:right)*/
                echo "<div class='bb-custom-side' id='side$date->start_day' style= overflow:scroll;>";
                echo "<ul><span class='contents-title'>$date->start_day.</span>";
                foreach ($alltodolist as $todo) {
                    if ($date->start_day == $todo->start_day) {
                        echo "<li id='task-content$todo->work_num'>";
                        echo "<label id='check$todo->work_num'>";
                        if ($todo->confirm_check == 1) {
                            echo "<input type='checkbox' id= checkbox$todo->work_num onclick=checktodo($todo->work_num) checked>";
                            echo "<a class='completed_item'>$todo->work</a>";
                        }
                        if ($todo->confirm_check == 0) {
                            echo "<input type='checkbox' id= checkbox$todo->work_num onclick=checktodo($todo->work_num)>";
                            echo "<a>$todo->work</a>";
                        }
                        echo "<label>";
                        echo "<input id='edit-content$todo->work_num' class='test' type='text' value='$todo->work' style='visibility: hidden'>";
                        echo "<button class='fa fa-pencil' id='edit$todo->work_num' onclick='edittodo($todo->work_num)'></button>";
                        echo "<button class='fa fa-trash' id='edit$todo->work_num' onclick='deletetodo($todo->work_num)'></button>";
                        echo "</li>";
                    }
                }
                echo "</ul>";
                echo "</div>"; // 반페이지 출력완료
                if ($check % 4 != 0) { //2->3 , 3->4
                    $check++;
                }
                /*종료 div 생성 (4)*/
                if ($check % 4 == 0) {
                    echo "</div>";
                    $check++;
                }
            }
            //foreach문 끝
            /*종료 div가 닫기지 않았을 때 = 3번페이지 출력하려다 튕김(ckeck:3) (4)*/
            if ($check % 4 == 3) {
                echo "</div>";
            }
            ?>
        </div>
        <nav>
            <a id="bb-nav-first" href="#"><i class="fa fa-angle-double-left"></i></a>
            <a id="bb-nav-prev" href="#"><i class="fa fa-angle-left"></i></a>
            <a id="bb-nav-next" href="#"><i class="fa fa-angle-right"></i></a>
            <a id="bb-nav-last" href="#"><i class="fa fa-angle-double-right"></i></a>
        </nav>
    </div>
</div>
<script src="/public/js/TodoList.js"></script>
<script>
    $(document).ready(function () {

        var my_calendar = $("#dncalendar-container").dnCalendar({
            minDate: "2016-01-01",
            maxDate: "2020-12-31",
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'],
            dataTitles: {},
            notes: [],
            showNotes: true,
            startWeek: 'monday'
        });

        // init calendar
        my_calendar.build();


        paint();

        // update calendar
        // my_calendar.update({
        // 	minDate: "2016-01-05",
        // 	defaultDate: "2016-05-04"
        // });
        function paint()
        {
            var date = $(".contents-title").text();

            var paintId = date.split('.');

            for (var i = 0; i < paintId.length; i++) {

                $('#nav'+paintId[i]).css({
                    "border": "1px solid #577cbd",
                    "background": "rgba(87,124,189,0.3)",
                    "font-weight":"500"
                });
            }
        }
    });
</script>