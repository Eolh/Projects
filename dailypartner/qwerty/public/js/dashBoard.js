$(function() {

    $(".block").draggable({
        revert : true,
        /*cursorAt : {top : 0.5, left: 0.5}*/
    });
    $(".block").droppable({
        drop : function (event, ui) {
            var tempText  = $(this).html();
            var tempText2 = $(ui.helper).html();
            //window.alert(tempText + " " + tempText2);

            var temper  = $(this).attr("id");
            /* after */
            var temper2 = $(ui.helper).attr("id");
            /* before */
            //window.alert(temper + " " + temper2);

            $.ajax({
                type    : "POST",
                url     : "http://127.0.0.1:8088/ajaxHandler/test",
                data    : {
                    "after"  : temper,
                    "before" : temper2
                },
                success : function (data) {
                    //window.alert(data);
                }
            });

            $(this).html(tempText2);
            $(ui.helper).html(tempText);
        }
    });
});

function foo()
{
    var form = $("#registerScheduler")[0];
    var formData = new FormData(form);

    $.ajax({
        type       : 'POST',
        url        : 'http://localhost:8088/ajaxHandler/test',
        data       : formData,
        async      : false,
        cache      : false,
        contentType: false,
        processData: false,
        success    : function (msg) {
            window.alert(msg);
            $("#result_IO").append(msg);
        },
        error      : function (error) {
            console.log(error);
        }
    });
}