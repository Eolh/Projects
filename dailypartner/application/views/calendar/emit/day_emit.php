<script>
    function getschedule() {}
    $(function () {

        var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            $('#pac-input').val(place.name);
            document.getElementById('place-address').value = place.formatted_address;
            document.getElementById('place-lat').value = place.geometry.location.lat();
            document.getElementById('place-lng').value = place.geometry.location.lng();
        });
    });
    $("#setPlace").on("shown.bs.modal", function () {
//        timeset();
        google.maps.event.trigger(emitmap, "resize");
    });
</script>

<div class="modal-dialog row">
    <div class="modal-content col-sm-11">

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a> <!-- Close -->
            <span style="font-size: 25px; font-weight: 300">Regist Schedule</span>
        </div>

        <div class="modal-body">
            <form method="post" class="form-horizontal" id="registerScheduler" enctype="multipart/form-data">
                <fieldset>
                    <!--date&time-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label vertical-top">時間</label>

                        <div class="col-sm-10 block-menu">
                            <div class="input-group">
                                <input name="startTime" type="date" id="sch_f_time" style="border: none; width: 140px;">
                                <input name="startTime2" type="time" id="sch_f_time_min" style="border: none; ">
                                ~
                                <input name="endTime" type="date" id="sch_l_time" style="border: none; width: 140px">
                                <input name="endTime2" type="time" id="sch_l_time_min" style="border: none">

                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--title-->
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label vertical-top">スケジュール</label>

                        <div class="col-sm-10 block-menu">
                            <input name="title" id="title" type="text" class="form-control"
                                placeholder="Input your schedule title" autofocus>
                        </div>
                    </div>

                    <!--contents-->
                    <div class="form-group">
                        <label for="content" class="col-sm-2 control-label vertical-top">内容</label>

                        <div class="col-sm-10 block-menu">
                            <textarea name="content" id="content" class="form-control" rows="2"
                                placeholder="Input detail infomation of this Schedule">
                            </textarea>
                        </div>
                    </div>

                    <!-- schedule's color  -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label vertical-top">色</label>

                        <div class="col-sm-10 block-menu input-group">
                            <label for="colors">
                                <div style="border-radius: 100%; width: 50px; height: 50px; background-color: #dee7ea">
                                </div>
                                <input type="radio" name="color" value="0" checked>
                                <div style="border-radius: 100%; width: 50px; height: 50px; background-color: #c6ebfe">
                                </div>
                                <input type="radio" name="color" value="1">
                                <div style="border-radius: 100%; width: 50px; height: 50px; background-color: #FFBEBE">
                                </div>
                                <input type="radio" name="color" value="2">
                                <div style="border-radius: 100%; width: 50px; height: 50px; background-color: #f3dcf8">
                                </div>
                                <input type="radio" name="color" value="3">
                            </label>
                        </div>
                    </div>

                    <!-- 2016-07-31 -->
                    <div class="panel panel-default">
                        <a data-toggle="collapse" href="#collapse">
                            <div class="panel-heading" align="center">
                                <h4 class="panel-title">ファイルや場所</h4>
                            </div>
                        </a>

                        <div id="collapse" class="panel-collapse collapse">
                            <!--place-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label vertical-top" for="sch_place">場所</label>

                                <div class="col-sm-10 block-menu">
                                    <div class="input-group">
                                        <input name="place_name" id="pac-input" type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <a data-toggle="modal" href="#setPlace" style="background-color: transparent; border: transparent;">
                                                <span class="glyphicon glyphicon-map-marker"></span>
                                            </a>
                                        </span>
                                        <input name="place_address" type="hidden" id="place-address" class="form-control">
                                        <input name="latitude" type="hidden" id="place-lat" class="form-control">
                                        <input name="longitude" type="hidden" id="place-lng" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!--add file-->
                            <div class="form-group search">
                                <label for="sch_file" class="col-sm-2 control-label vertical-top">File</label>

                                <div class="col-sm-10"> <!--.search / .controls--><!--search_file-->
                                    <div class="input-group">
                                        <input type="text" readonly="" class="form-control" id="sch_file" aria-describedby="searchicon" placeholder="file search.."/ >
                                        <input type="file" name="userFile[]" accept="*/*" id="sch_file_pc" multiple>
                                                                                                                                                                   <!--file search popup-->
                                            <span id="searchicon" data-toggle="modal" data-target="#test" class="input-group-addon">
                                                <!--<span class="glyphicon glyphicon-search"></span>-->
                                                <label class="input-group-addon" for="sch_file_pc">
                                                    <i class="material-icons">attach_file</i>
                                                </label>
                                            </span>

                                    </div>
                                    <div class="input-group"><!--class="search"--><!--file_tag-->
                                        <input name="tag" type="text" class="form-control" id="tagName"
                                            placeholder="file's tag space : tag separation">
                                        <label class="input-group-addon" for="tagName">
                                            <span class="glyphicon glyphicon-tag"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--time_pie-->
<!--                    <div class="form-group">-->
<!--                        <label for="content" class="col-sm-2 control-label vertical-top">빈 시간</label>-->
<!---->
<!--                        <div class="col-sm-10 block-menu">-->
<!--                            <div id="time_analyst"-->
<!--                                style="width:400px; height:400px; float:left; border: 2px #e0e8e8 solid; font-size:3px; overflow: auto">-->
<!--                                <div id="chart" style="min-width:400px; height:400px;"></div>-->
<!--                                <!--width: 100%; height:100%;-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="form-group">
                        <label for="groupCalRegister" class="col-sm-2 control-label">Group</label>

                        <div class="col-sm-10">
                            <select name="groupCalendarRegister" id="groupCalRegister" class="form-control">
                                <?php
                                foreach ($groupInfo as $group)
                                {
                                    echo "<option id='{$group->UCNum}Register'>{$group->calName}</option>";
                                }
                                ?>
                                <!--<option value="1">+ add group(직접입력)</option>-->
                            </select>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <!--button-->
            <div class="form-group">
                <a id="scheduleAddBtn" class="btn btn-primary" data-dismiss="modal">登録</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">やめる</button>
            </div>
        </div>
    </div>
</div>