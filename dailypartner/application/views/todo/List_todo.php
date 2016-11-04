<div class="dashboardtodo">
<h3 class="Todo-title"><?php echo $curdate->date; ?></h3>
<section id="task-container">
    <div id="task-list" style="overflow:auto">
        <?php
        foreach ($todolist as $todo) {
            ?>
            <ul class="task-one" id="workNumber<?= $todo->work_num ?>">
                <li id="task-content<?= $todo->work_num ?>">
                    <label id="check<?= $todo->work_num ?>">
                        <?php if ($todo->confirm_check == 1) { ?>
                            <input type='checkbox' id="checkbox<?= $todo->work_num ?>"
                                   onclick="checktodo('<?= $todo->work_num ?>')" checked>
                            <a class="completed_item"><?php echo $todo->work ?></a>
                        <?php } ?>
                        <?php if ($todo->confirm_check == 0) { ?>
                            <input type='checkbox' id="checkbox<?= $todo->work_num ?>"
                                   onclick="checktodo('<?= $todo->work_num ?>')">
                            <a><?php echo $todo->work ?></a>
                        <?php } ?>
                    </label>
                    <i class="fa fa-pencil" id="edit<?= $todo->work_num ?>" onclick="edittodo(<?= $todo->work_num ?>)"></i>
                    <i class="fa fa-trash" onclick="deletetodo(<?= $todo->work_num ?>)"></i>
                </li>
            </ul>
            <?php
        } ?>
    </div>
</div>
<script>


    $('#create-task').keypress(function (e) {
        if (e.which == 13) {
            $("#insert-btn").click();
        }
    });

    $(function () {
        $("#lastday").datepicker({
            showOn: "button",
            buttonImage: "/public/img/calendar.png",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "yy-mm-dd"
        });
    });

    $(function () {
        $("#TodoBtn").click(function () {
            $("#slideE").toggle('slide', {direction: "right"}, 'easeOutExpo', 500);
        });
    });


    function deletetodo(workNumber) {
        $.ajax({
            type: 'POST',
            url: '/TodoList/deletetodo',
            data: {'work_num': workNumber},
            success: function (data) {
                $("ul#workNumber" + workNumber).remove();
            }
        });
    }

    function inserttodo(startday) {

        var uid =
        <?=$_SESSION['login']['UID']?>

        var text = $("#create-task").val();

        $.ajax({
            type: 'POST',
            url: '/TodoList/inserttodo',
            data: {
                'UID': uid,
                'work': text,
                'start_day': startday
            },
            success: function (data) {
                $("#task-list").prepend(data);
            }
        });
    }
    function edittodo(workNumber) {

        $("#edit" + workNumber).replaceWith("<i id='edit-btn" + workNumber + "'class='fa fa-check' onclick='modifytodo(" + workNumber + ")'></i>");
        $("#check" + workNumber).after("<input id= 'edit-content"+workNumber+"'class='test' type='text'>");
    }
    /*("#edit-btn" + workNumber).replaceWith("<button id='edit<?=$todolist[0]->work_num?>' class='fa fa-pencil' onclick='edittodo(<?=$todolist[0]->work_num?>)'></button>");*/
    function modifytodo(workNumber) {

        var text = $("#edit-content" + workNumber).val();

        $.ajax({
            type: 'POST',
            url: '/TodoList/modifytodo',
            data: {
                'work': text,
                'work_num': workNumber
            },
            success: function (data) {
                var a = JSON.parse(data);
                $("#check" + workNumber + " > a").text(a.work);
                $("#edit-content" + workNumber).remove();
                $("#edit-btn" + workNumber).replaceWith("<i id='edit" + workNumber + "' class='fa fa-pencil' onclick='edittodo(" + workNumber + ")'></i>");
            }
        });
    }

    function checktodo(workNumber) {

        $.ajax({
            type: 'POST',
            url: '/TodoList/checktodo',
            data: {
                'work_num': workNumber
            },
            success: function (data) {
            }
        });
    }

    $('input[type=checkbox]').click(function () {

        if ($(this).next().attr("class") == "completed_item")
            $(this).next().removeAttr("class");
        else
            $(this).next().attr("class", "completed_item");

    });

    $(document).on('keypress', 'ul input[class="test"]', function (e) {

        if (e.which == 13) {
            $(this).next().click();
        }
    });

</script>