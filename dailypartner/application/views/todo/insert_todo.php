<ul class="task-one"  id="workNumber<?= $todoList->work_num ?>">
    <li id="task-content<?= $todoList->work_num ?>">
        <label id="check<?=$todoList->work_num?>">
            <?php if($todoList->confirm_check==1) {?>
                <input type='checkbox' id= "checkbox<?=$todoList->work_num?>"onclick="checktodo('<?=$todoList->work_num?>')"checked>
                <a class="completed_item"><?php echo $todoList->work ?></a>
            <?php }?>
            <?php if($todoList->confirm_check==0) {?>
                <input type='checkbox' id= "checkbox<?=$todoList->work_num?>"  onclick="checktodo('<?=$todoList->work_num?>')">
                <a><?php echo $todoList->work ?></a>
            <?php }?>
        </label>
        <i class="fa fa-pencil" id="edit<?= $todoList->work_num?>" onclick="edittodo(<?=$todoList->work_num ?>)">
        </i>
        <i class="fa fa-trash" onclick="deletetodo(<?= $todoList->work_num ?>)"></i>
    </li>
</ul>
<script>
    $("#checkbox<?=$todoList->work_num?>").click(function(){

        if($(this).next().attr("class") == "completed_item")
            $(this).next().removeAttr("class");
        else
            $(this).next().attr("class" ,"completed_item");

    });
</script>