function Addgroup() {

    var groupName = $("#Group_name").val();

    $.ajax({
        type: "POST",
        url: "/Friend/addgroup",
        data: {'gname': groupName},
        success: function (data) {

            $("#group_add").append(data);

            $("#Group_name").val('');

        }
    });
}

function grouplistView(groupNum) {

    $.ajax({
        type: "POST",
        url: "/Friend/grouplistView",
        data: {'gnum': groupNum},
        success: function (data) {

            $("#group_list").replaceWith(data);

            changeButton(groupNum);
        }
    });
}

function changeButton(groupNum) {
    $.ajax({
        type: "POST",
        url: "/Friend/friend_in_group_button",
        data: {},
        success: function (data) {

            var friendList = JSON.parse(data);

            for (var i = 0; i < friendList.length; i++) {

                $("#add_friend_in_group" + friendList[i].UID).replaceWith("<a id='add_friend_in_group" + friendList[i].UID + "' class='btn btn-fab' style='height: 40px; min-width: 40px; width: 40px; float: right;'" +
                    "onclick='add_friend_in_group(" + friendList[i].UID + "," + groupNum + ")'>" +
                    "<i class='material-icons' style='font-weight: bolder'>add</i></a>");
            }
        }
    });
}

function add_friend_in_group(friendUID, groupNum) {
    $.ajax({
        type: "POST",
        url: "/Friend/add_friend_in_group",
        data: {'UID' : friendUID,
            'gnum' : groupNum},
        success: function (data) {

            $("#group_list").append(data);
        }
    });
}

function friendaddlist_add(friendUID) {
    $.ajax({
        type: "POST",
        url: "/Friend/friendaddlistAdd",
        data: {'friend1': friendUID},
        success: function (data) {
            $("#FriendList").after(data);

            $("#AddList" + friendUID).remove();
        }
    });
}

function friendaddlist_delete(friendUID) {
    $.ajax({
        type: "POST",
        url: "/Command/friendaddlistDelete",
        data: {'friend1': friendUID},
        success: function (data) {

            $("#AddList" + friendUID).remove();
        }
    });
}