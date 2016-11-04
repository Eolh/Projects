<!--글 작성 뷰-->

<form action="/group/boardWriteSave/<?= $groupNum; ?>" method="post">
    <table>
        <?php
        if ($create_by->create_by == $UID) { ?>
            <tr>
            <td><input class="checkbox" type = "checkbox" name="allcheck">공지사항</td>
            </tr>
       <?php } ?>
        <tr>
            <td><input type="text" name="title" id="editor" size="68" autofocus placeholder="제목을 입력하세요"></td>
        </tr>
        <tr>
            <td><textarea name="content" id="editor" cols="70" rows="20" placeholder="내용을 입력하세요."></textarea></td>
        </tr>
        <tr>
            <td align="center">
                <a href="/group/boardList/<?= $groupNum; ?>">←CANCLE </a>
                <button class='btn'> SUBMIT</button>
                <button type='reset' class='btn'> RESET</button>
            </td>
        </tr>
    </table>
</form>