<div class="slideDiv ">
    <div id="slideE" class="sideTodo">

        <!--상단-->
        <p class="Todo-title fontdeco-inverse">Diary</p>
        <section id="task-form" class="form-group" style="margin: 0">
            <input id="create-task" class="form-control" type="text" name="task-insert"
                   placeholder="Insert your list items here" style="width: 80%; display: inline-block">
            <a class="btn btn-fab btn-fab-mini" id="insert-btn" onclick="inserttodo('<?= $curdate->date ?>')"
               style="height: 25px; width: 25px; min-width: 25px; display: inline-block; margin-left: 5px"><i
                    class="material-icons">send</i></a>
        </section>

        <!--하단-->
        <p class="Todo-title"><?php echo $curdate->date; ?></p>
        <section> <!-- id="task-container"-->
            <ul style="padding: 0">
                <div id="task-list" style="word-break: break-all;">
                    <?php foreach ($todolist as $todo) { ?>
                        <li id="workNumber<?= $todo->work_num ?>">
                            <div class="checkbox row" id="task-content<?= $todo->work_num ?>">
                                <label class="col-xs-8" id="check<?= $todo->work_num; ?>"
                                       style="margin-left: 15px;padding: 0">
                                    <?php if ($todo->confirm_check == 1) { ?>
                                        <input type='checkbox' onclick="checktodo('<?= $todo->work_num; ?>')" checked>
                                        <span class="completed_item" id="todo_contents"><?= $todo->work; ?></span>
                                        <?php if($todo->result){?>}
                                        <p>
                                        <h4 id="result<?=$todo->work_num?>" style="color: #00b3ee">結果 : <?= $todo->result ?></h4>
                                        </p>
                                        <?php }?>
                                    <?php } ?>
                                    <?php if ($todo->confirm_check == 0) { ?>
                                        <input type='checkbox' onclick="checktodo('<?= $todo->work_num; ?>')">
                                        <span id="todo_contents"><?= $todo->work; ?></span>
                                    <?php } ?>
                                </label>
                                <!--modify or delete-->
                                <p id='btnWithTodo<?= $todo->work_num ?>' class="col-xs-3"
                                   style="right: 15px; text-align: right; padding: 0">
                                    <a class="btn" style="padding: 1px 2px; margin: 0">
                                        <i class="material-icons" id="edit<?= $todo->work_num; ?>"
                                           onclick="edittodo(<?= $todo->work_num; ?>,'<?= $todo->work; ?>')">mode_edit</i></a>
                                    <a class="btn" style="padding: 0 2px; margin: 0">
                                        <i class="material-icons"
                                           onclick="deletetodo(<?= $todo->work_num; ?>)">delete</i></a>
                                </p>
                            </div>
                        </li>
                    <?php } ?>
                </div>
            </ul>
        </section>
    </div>
    <a id="TodoBtn"><i class="fa fa-chevron-right" aria-hidden="true"></i></a><!--닫는 버튼-->
</div>

<script>
    $('#create-task').keypress(function (e) {
        if (e.which == 13) {
            $("#insert-btn").click();
        }
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
                $("li#workNumber" + workNumber).remove();
                /*id가 workNumber:n를 가진 li 제거*/
            }
        });
    }

    function inserttodo(startday) {

        var uid = <?=$_SESSION['login']['UID']?>;

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
                console.log(data);
                $("#task-list").prepend(data);
                $("#create-task").val('');
            }
        });
    }

    function edittodo(workNumber, contents) { //수정폼
        $("#edit" + workNumber).replaceWith("<i id='edit-btn" + workNumber + "' class='material-icons' onclick='modifytodo(" + workNumber + ")'>done</i>");
        $("#check" + workNumber).after("<input id='edit-content" + workNumber + "' class='test' type='text' value='" + contents + "'>");
    }

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
                var contents = JSON.parse(data);
                $("#check" + workNumber + " #todo_contents").text(contents.work);
                $("#edit-content" + workNumber).remove();
                $("#edit-btn" + workNumber).replaceWith("<i id='edit" + workNumber + "' class='material-icons' onclick='edittodo(" + workNumber + ")'>mode_edit</i>");
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
                /*material bootstrap으로인해 span객체가 #todo_contents객체 앞에 생겨난다*/
                var resultN = $("#check" + workNumber + " #todo_contents").hasClass("completed_item");//hasClass : 클래스 유무판단

                if (resultN) { //completed_item class가 있으면
                    $("#check" + workNumber + " #todo_contents").removeClass("completed_item");
                    $("#result"+ workNumber).hide();

                } else {
                    $("#check" + workNumber + " #todo_contents").addClass("completed_item");
                    if(data == 0) {
                    $("#btnWithTodo" + workNumber).after("<input id='inputResult" + workNumber + "' class='test' type='text'>" +
                        "<i id='enrollResult" + workNumber + "' class='material-icons' onclick='enrollResult(" + workNumber + ")'>done</i>" +
                        "<i id='deleteResult" + workNumber + "' class='material-icons' onclick='deleteResult(" + workNumber + ")'>delete</i>");
                    }
                    $("#result" + workNumber).show();
                }
                /*window.alert(resultN);*/
            }
        });
    }
    function enrollResult(workNumber) {

        var content = $("#inputResult" + workNumber).val();

        $.ajax({
            type: 'POST',
            url: '/TodoList/enrollResult',
            data: {
                'work_num': workNumber,
                'result': content
            },
            success: function (data) {

                $("#inputResult" + workNumber).remove();
                $("#enrollResult" + workNumber).remove();
                $("#deleteResult" + workNumber).remove();
                $("#check" + workNumber).append("<p><h4 id = 'result"+workNumber+"'style='color: #00b3ee'>結果 : "+content+"</h4></p>");
            }

        });
    }
    $(document).on('keypress', 'li input[class="test"]', function (e) { /*ul안의 input의 class가 test일 경우*/

        if (e.which == 13) {
            $(this).next().click();
        }
    });

</script>