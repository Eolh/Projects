<?php


function pageNavigator($pageInfo, $targetAction, $pageNumParaName){


    echo "<table width='300' align='center'><tr>";

    // 처음페이지 이동
    echo "<td width='30'>";
    if($pageInfo['firstPage'] == 0){
        echo "△";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$pageInfo['firstPage']}'>▲</a>";
    }
    echo "</td>";


    // 이전 블럭으로 이동
    echo "<td width='30'>";
    if($pageInfo['preBlock'] == 0){
        echo "□";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$pageInfo['preBlock']}'>■</a>";
    }
    echo "</td>";

    // 이전 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['firstPage'] == false){
        echo "◁";
    }else{
        $prePageNum = $pageInfo['currentPageNum']-1;
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$prePageNum}'>◀</a>";

    }
    echo "</td>";

    //현재 블럭의 페이지 표시
    for( $cnt = 0; $cnt < $pageInfo['countPageInBlock']; $cnt++ ){
        echo "<td width='30'>";
        $currentBlockPageNum = $pageInfo['startPageNumInBlock']+$cnt;
        if( $currentBlockPageNum == $pageInfo['currentPageNum'])
            echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$currentBlockPageNum}'>[{$currentBlockPageNum}]</a>";
        else
            echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$currentBlockPageNum}'>{$currentBlockPageNum}</a>";
        echo "</td>";
    }

    // 다음 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['lastPage'] == false){
        echo "▷";
    }else{
        $nextPageNum = $pageInfo['currentPageNum']+1;
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$nextPageNum}'>▶</a>";
    }
    echo "</td>";

    // 다음 블럭으로 이동
    echo "<td width='30'>";
    if($pageInfo['nextBlock'] == 0){
        echo "□";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$pageInfo['nextBlock']}'>■</a>";
    }
    echo "</td>";

    // 마지막 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['lastPage'] == 0){
        echo "▽";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&{$pageNumParaName}={$pageInfo['countWholePage']}'>▼</a>";
    }
    echo "</td>";

    echo "</tr>";
    echo "</table>";

}

function pageNavigate($pageInfo, $targetAction, $pageNumParaName,$category){


    echo "<table width='300' align='center'><tr>";

    // 처음페이지 이동
    echo "<td width='30'>";
    if($pageInfo['firstPage'] == 0){
        echo "△";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$pageInfo['firstPage']}'>▲</a>";
    }
    echo "</td>";


    // 이전 블럭으로 이동
    echo "<td width='30'>";
    if($pageInfo['preBlock'] == 0){
        echo "□";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$pageInfo['preBlock']}'>■</a>";
    }
    echo "</td>";

    // 이전 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['firstPage'] == false){
        echo "◁";
    }else{
        $prePageNum = $pageInfo['currentPageNum']-1;
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$prePageNum}'>◀</a>";
    }
    echo "</td>";

    //현재 블럭의 페이지 표시
    for( $cnt = 0; $cnt < $pageInfo['countPageInBlock']; $cnt++ ){
        echo "<td width='30'>";
        $currentBlockPageNum = $pageInfo['startPageNumInBlock']+$cnt;
        if( $currentBlockPageNum == $pageInfo['currentPageNum'])
            echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$currentBlockPageNum}'>[{$currentBlockPageNum}]</a>";
        else
            echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$currentBlockPageNum}'>{$currentBlockPageNum}</a>";
        echo "</td>";
    }

    // 다음 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['lastPage'] == false){
        echo "▷";
    }else{
        $nextPageNum = $pageInfo['currentPageNum']+1;
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$nextPageNum}'>▶</a>";
    }
    echo "</td>";

    // 다음 블럭으로 이동
    echo "<td width='30'>";
    if($pageInfo['nextBlock'] == 0){
        echo "□";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$pageInfo['nextBlock']}'>■</a>";
    }
    echo "</td>";

    // 마지막 페이지 이동
    echo "<td width='30'>";
    if($pageInfo['lastPage'] == 0){
        echo "▽";
    }else{
        echo "<a href='../controller/MainCTL.php?action={$targetAction}&category={$category}&{$pageNumParaName}={$pageInfo['countWholePage']}'>▼</a>";
    }
    echo "</td>";

    echo "</tr>";
    echo "</table>";

}
?>