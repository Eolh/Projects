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
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-cog"></span></a></li>
                    <li><a href="javascript:void(0)"><span class="glyphicon glyphicon-question-sign"></span></a></li>
                </ul>

                <form class="navbar-form navbar-left nonemargin" role="form" onsubmit="return false">
                    <div class="form-group">
                        <input type="text" class="form-control col-sm-8" id="search" placeholder="/Command">
                        <button class="s-c-btn" type="button" onclick="command()"><i class="fa fa-search"></i></button>
                    </div>
                </form>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#" name="updateSchedule"><i class="fa fa-pencil"></i></a></li>
                    <li><a href="/calendar/"><i class="fa fa-calendar" aria-hidden="true"></i></a></li>
                    <li><a href="/file/"><i class="fa fa-files-o"></i></a></li>
                    <li class="dropdown">
                        <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle"
                            data-toggle="dropdown"><i class="fa fa-bell"></i>
                            <span class="badge">5</span></a>
                        <ul class="dropdown-menu">
                            <li class="active"><a href="javascript:void(0)">Active</a></li>
                            <li><a href="javascript:void(0)">Another action</a></li>
                            <li><a href="javascript:void(0)">under Driver</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Dropdown header</li>
                            <li><a href="javascript:void(0)">Separated link</a></li>
                            <li><a href="javascript:void(0)">Welcom</a></li>
                        </ul>
                    </li>
                    <li><a href="/login/logout"><i class="fa fa-sign-out"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

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
                    <label for="title">일정</label>
                    <input name="title" id="sch_title" type="text" class="form-control">
                    <label for="content">내용</label>
                    <textarea name="content" id="sch_content" rows="2" class="form-control"></textarea>
                    <ul>
                        <li><label class="form-control" for="sch_f_time">시간</label>
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
                            <label class="control-label" for="sch_place">장소</label>
                            <input name="location" type="text" class="controls" id="sch_place">
                            <button type="button" style="background-color: transparent; border: transparent;">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li class="search">
                            <label class="control-label" for="sch_file">파일</label>
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
                        <li>
                            <label class="control-label" for="sch_member">참여인원</label>
                            <input type="text" class="controls" id="sch_member">
                            <button type="button" style="background-color: transparent; border: transparent;">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <div id="sch_members">
                                <span>jeimy</span><span>jessica</span>
                                <!--<i class="fa fa-star" aria-hidden="true"></i>-->
                            </div>
                        </li>
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
                    <h3>그룹 등록</h3>
                </div>
                <div class="modal-body form-group">
                    <label for="groupName">그룹</label>
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