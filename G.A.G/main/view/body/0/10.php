<div class="loginChk"><!--View 부분 -->
    <p>WELCOME TO G.AG</p>

    <p style="font-size: small">회원로그인</p>

    <form action="../controller/MainCTL.php">
        <div class="log_form">
            <input type="hidden" name="action" value="11">

            <div><label>아이디</label><input type="text" name="user_id"></div>

            <div><label>비밀번호</label><input type="password" name="user_pwd"></div>

            <span id="member_btn"><input type="submit" id="login" value="LOGIN"></span>
        </div>
    </form>
</div>


