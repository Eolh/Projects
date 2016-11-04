<?php if($url[0] != "group") {
?>
<header>
    <div class="navbar navbar-inverse navbar-fixed-top texture-img">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/main"><i class="fa fa-calendar-o" aria-hidden="true"></i>
                    Balance</a>
                <form class="navbar-form navbar-left nonemargin col-sm-6" role="form" onsubmit="return false" style="@media (max-width: 767px) {.navbar-form{margin-top: 0 !important;}}">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search" placeholder="/Command">
                        <a class="s-c-btn" onclick="command()"><i class="material-icons">search</i></a>
                    </div>
                </form>
            </div>

            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/friend/"><i class="fa fa-users" aria-hidden="true"></i></a></li>
                    <li><a href="/calendar/"><i class="fa fa-calendar" aria-hidden="true"></i></a></li>
                    <li><a href="/file/"><i class="fa fa-files-o"></i></a></li>
                    <li><a href="/maps/"><i class="fa fa-map"></i></a></li>

                    <li class="dropdown">
                        <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-cog"></span>
                            <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a onclick="autoRegister()" href="#" name="updateSchedule">AUTO&nbsp;<i class="material-icons">perm_device_information</i></a></li>
                            <li><a href="javascript:void(0)">Helper&nbsp;<i class="material-icons">help_outline</i></a></li>
                            <li class="divider"></li>
                            <li><a href="/login/logout">EXIT&nbsp;<i class="material-icons">exit_to_app</i></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div id="displaySearch">

            </div>
        </div>
    </div>
</header>
<?php } else {?>

    <header>
        <div class="navbar navbar-inverse navbar-fixed-top texture-img">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-responsive-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/main"><i class="fa fa-calendar-o" aria-hidden="true"></i>
                        Balance</a>
                    <form class="navbar-form navbar-left nonemargin col-sm-6" role="form" onsubmit="return false" style="@media (max-width: 767px) {.navbar-form{margin-top: 0 !important;}}">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search" placeholder="/Command">
                            <a class="s-c-btn" onclick="command()"><i class="material-icons">search</i></a>
                        </div>
                    </form>
                </div>

                <div class="navbar-collapse collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/group/boardList/<?=$url[2]?>"><i class="fa fa-server" aria-hidden="true"></i></a></li>
                        <li><a href="/group/calendar/<?=$url[2]?>"><i class="fa fa-calendar" aria-hidden="true"></i></a></li>
                        <li><a href="/group/file/<?=$url[2]?>"><i class="fa fa-files-o"></i></a></li>
                        <li><a href="/group/group/<?=$url[2]?>"><i class="fa fa-map"></i></a></li>

                        <li class="dropdown">
                            <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-cog"></span>
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a onclick="autoRegister()" href="#" name="updateSchedule">AUTO&nbsp;<i class="material-icons">perm_device_information</i></a></li>
                                <li><a href="javascript:void(0)">Helper&nbsp;<i class="material-icons">help_outline</i></a></li>
                                <li class="divider"></li>
                                <li><a href="/login/logout">EXIT&nbsp;<i class="material-icons">exit_to_app</i></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div id="displaySearch">

                </div>
            </div>
        </div>
    </header>

<?php } ?>

<!--command open window-->
<div class="modal fade" id="uploadSchedule2">
    <form method="post" id="registerScheduler2" enctype="multipart/form-data">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">
                        &times;
                    </a> <!-- Close -->
                    <h3>일정 등록</h3>
                    <!--<p>Date: <input type="text" id="datepicker"></p>-->
                </div>
                <div class="modal-body">
                    <label for="title">日程</label>
                    <input name="title" id="sch_title" type="text" class="form-control">
                    <label for="content">内容</label>
                    <textarea name="content" id="sch_content" rows="2" class="form-control"></textarea>
                    <ul>
                        <li><label class="form-control" for="sch_f_time">時間</label>
                            <input type="text" id="datepicker1">
                            <input name="startTime" type="time" class="control" id="sch_f_time">
                            <label class="control" for="sch_l_time"> ~ </label>
                            <input type="text" id="datepicker2">
                            <input name="endTime" type="time" class="control" id="sch_l_time">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                        </li>
                        <li>
                            <select name="groupCalendarRegister" id="groupCalRegister">
                                <?php
                                foreach ($groupInfo as $group) {
                                    echo "<option id='{$group->UCNum}Register'>{$group->calName}</option>";
                                }
                                ?>
                            </select>
                        </li>
                        <li>
                            <label class="control-label" for="sch_place">場所</label>
                            <input name="location" type="text" class="controls" id="sch_place">
                            <button type="button" style="background-color: transparent; border: transparent;">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li class="search">
                            <label class="control-label" for="sch_file">ファイル</label>
                            <input type="text" class="controls" id="sch_file" aria-describedby="searchicon"
                                   placeholder="file search..">
                            <input name="tag" type="text" class="controls" id="tagName"
                                   placeholder="file's tag space : tag separation">
                                <span id="searchicon" data-toggle="modal" data-target="#test"><!--file search popup-->
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                            <label for="sch_file_pc">
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                            </label>
                            <input type="file" name="userFile[]" accept="*/*" id="sch_file_pc" multiple>

                            <div><!--드래그해서 파일을 첨부하세요--></div>
                        </li>
<!--                        <li>-->
<!--                            <label class="control-label" for="sch_member">참여인원</label>-->
<!--                            <input type="text" class="controls" id="sch_member">-->
<!--                            <button type="button" style="background-color: transparent; border: transparent;">-->
<!--                                <i class="fa fa-search" aria-hidden="true"></i>-->
<!--                            </button>-->
<!--                            <div id="sch_members">-->
<!--                                <span>jeimy</span><span>jessica</span>-->
<!--                                <!--<i class="fa fa-star" aria-hidden="true"></i>-->-->
<!--                            </div>-->
<!--                        </li>-->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" data-dismiss="modal" onclick="regist()">
                    Footer
                </a>
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="uploadGroup2">
    <form method="post" id="registerGroup2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">
                        &times;
                    </a> <!-- Close -->
                    <h3>グループ登録</h3>
                </div>
                <div class="modal-body form-group">
                    <label for="groupName">グループ</label>
                    <input name="groupName" id="groupName" type="text" class="form-control">
                </div>
                <div class="modal-footer">
                    <a id="groupAddBtn" class="btn btn-primary" data-dismiss="modal">
                        Footer
                    </a>
                    <a href="#" class="btn" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- 자동 등록 -->
<div class="modal fade" id="autoList">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h2>自動登録</h2></div>
            <hr>
            <div id="autoLister" class="modal-body form-group">

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>携帯番号</th>
                        <th>カレンダー</th>
                        <th>キーワード</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <h3> 設定登録 </h3>

                <form method="post" id="insertAuto">
                    <h4>
                        <label for="phoneNumber">番号</label>
                        <input type="tel" name="phoneNumber" id="phoneNumber" size="11" required>
                        <hr>
                        <label for="keyWord">キーワード</label>
                        <input type="text" name="keyWord" id="keyWord" required>
                        <hr>
                        <label for="multiCalendar">カレンダー</label>
                        <select name="multiCalendar" id="multiCalendar"></select>
                        <button onclick="autoRegisterAndAppend(); return false;">登録</button>
                    </h4>

                </form>
            </div>
            <hr>
            <div class="modal-footer"><a href="#" class="btn btn-primary" data-dismiss="modal">Close</a></div>
        </div>
    </div>
</div>