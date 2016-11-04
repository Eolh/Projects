<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-10
 * Time: 오후 11:35
 */
//View 부분입니다
?>
<div class="signUp_box">
    <h2>회원가입</h2>

    <p style="font-size:15px">회원 정보 입력</p>

    <form action="../controller/MainCTL.php">
        <div class="sign_form">
            <input type="hidden" name="action" value="14">

            <div><label>이름</label><input type="text" name="user_name"></div>
            <div><label>아이디</label><input type="text" name="user_id"></div>
            <div><label>비밀번호</label><input type="password" name="user_pwd"></div>
            <div><label>전화번호</label><input type="text" name="user_tel"></div>
            <span id="join_btn"><input type="submit" id="signUp" value="signup"></span>
            <span id="cc_btn"><input type="button" id="cancel" value="취소" onclick="location=../index.html"></span>
        </div>
    </form>

</div>

