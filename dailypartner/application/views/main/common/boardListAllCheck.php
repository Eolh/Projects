<?php
if (!$boardList) {
    echo "<tr><td align='center' colspan='5'>작성한 게시글이 없습니다.</td></tr>";
} else {
    foreach ($boardList as $item) {
        echo "<tr>";
        echo "<td id = 'list_subject' colspan='2'><a href='/group/boardDetailView/$item->bnum'>$item->title</a></td>";
        echo "<td id = 'center'>$item->create_by</td>";
        echo "<td id = 'list_s' width='70'>$item->create_date</td>";
        echo "<td id = 'list_s'>$item->hit</td>";
        echo "</tr>";
    }
}
?>
