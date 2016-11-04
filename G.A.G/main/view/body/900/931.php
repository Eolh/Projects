<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-03
 * Time: 오전 10:11
 */

$category = $_SESSION['pcategory'];
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#imgInp").on('change', function () {
            readURL(this);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<H1>상품 등록</H1>
<hr>
<form id="form1" action="../controller/MainCTL.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="932">
<table width="93%" height="250px">
    <tr>
        <td colspan="2" height="30px"><select name="p_category" placehold="상품 카테고리">
                <option value="<?= $category[0]; ?>">Jacket</option>
                <option value="<?= $category[1]; ?>">Shirts</option>
                <option value="<?= $category[2]; ?>">Pants</option>
                <option value="<?= $category[3]; ?>">Shoes</option>
                <option value="<?= $category[4]; ?>">Bag</option>
                <option value="<?= $category[5]; ?>">Accessory</option>
            </select></td>
    </tr>
    <tr>
        <td align="center">
            <h3>대표이미지</h3>


                <img src="../../img/static_img/NOIMG.jpg" id="blah" width="400px" height="600px">
                <input type="file" id="imgInp" name="p_simage">
                <p>*없을 경우 자동으로 대표이미지가 만들어집니다.</p>

        </td>
        <td width="275px">
            <h3 align="center">제품 정보</h3>
            <table height="100%" align="center">
                <tr>
                    <td>
                        제품 이름 : <input type="text" name="p_name">
                        제품 가격 : <input type="text" name="p_price" placeholder="숫자만 입력하세요">
                        제품 수량 : <input type="text" name="p_stock" placeholder="숫자만 입력하세요">
                    </td>
                </tr>
                <tr>
                    <td height="100px">
                       제품 상세 이미지:
                        <input type="file" name="p_files[]" multiple=""/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            상품 내용
            <textarea style="width:820px; height: 220px;" name="p_content"></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" value="등록">
            <input type="reset" value="초기화">
            <input type="button" value="취소"onclick="location=history.back()">
        </td>
    </tr>
</table>
</form>