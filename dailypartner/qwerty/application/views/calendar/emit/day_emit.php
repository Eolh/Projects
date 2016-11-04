<form method="post" id="registerScheduler" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    &times;
                </a> <!-- Close -->
                <h3>일정 등록</h3>
                     <!--<p>Date: <input type="text" id="datepicker"></p>-->
            </div>
            <div class="modal-body form-group">
                <label for="title">일정</label>
                <input name="title" id="title" type="text" class="form-control">
                <label for="content">내용</label>
                <textarea name="content" id="content" rows="2" class="form-control"></textarea>
                <ul>
                    <li><label class="control-label" for="sch_f_time">시간</label>
                        <input name="startTime" type="time" class="controls" id="sch_f_time">
                        <label class="control-label" for="sch_l_time"> ~ </label>
                        <input name="endTime"   type="time" class="controls" id="sch_l_time">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                    </li>
                    <li>
                        <select name="groupCalendarRegister" id="groupCalRegister">
                            <?php
                            foreach ($groupInfo as $group)
                            {
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
                        <input type="text" class="controls" id="sch_file" aria-describedby="searchicon" placeholder="file search..">
                        <input name="tag" type="text" class="controls" id="tagName" placeholder="file's tag space : tag separation">
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
            <a id="scheduleAddBtn" class="btn btn-primary" data-dismiss="modal">
                Footer
            </a>
            <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
</form>