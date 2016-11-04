<script>
    $(function () {
        $('#myCarousel').carousel({interval: false});
        $('#myCarousel').carousel({keyboard: true});
    });
</script>
<style>
    .onoffswitch {
        position: relative; width: 90px;
        -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
    }
    .onoffswitch-checkbox {
        display: none;
    }
    .onoffswitch-label {
        display: block; overflow: hidden; cursor: pointer;
        border: 2px solid #999999; border-radius: 20px;
    }
    .onoffswitch-inner {
        display: block; width: 200%; margin-left: -100%;
        transition: margin 0.3s ease-in 0s;
    }
    .onoffswitch-inner:before, .onoffswitch-inner:after {
        display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
        font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
        box-sizing: border-box;
    }
    .onoffswitch-inner:before {
        content: "";
        padding-right: 10px;
        background-color: #EEEEEE; color: #999999;
        text-align: right;
    }
    .onoffswitch-inner:after {
        content: "完了";
        padding-left: 30px;
        background-color: #34A7C1; color: #FFFFFF;
    }
    .onoffswitch-switch {
        display: block; width: 18px; margin: 6px;
        background: #FFFFFF;
        position: absolute; top: 0; bottom: 0;
        right: 56px;
        border: 2px solid #999999; border-radius: 20px;
        transition: all 0.3s ease-in 0s;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
        margin-left: 0;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
        right: 0px;
    }4
</style>
<div class="modal-dialog">
    <div class="modal-content"><!-- id="modalContent"-->
        <div class="modal-body">

            <!--file view-->
            <div class="text-center" style="height: 300px; margin: 2px; background-color: rgba(87, 124, 189, 0.2)">
<!--<div id = mymap style="height: 100%;"></div>-->
                <div id="myCarousel" class="carousel slide" data-ride="carousel" data-slide-to="1" style="width: 100%; height: 100%">

                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner center-block" role="listbox" style="width: 80%">
                        <!--값없으면 안보여주기-->
                        <div class="item active">
                            <div id="mymap" style="width: 100%;height: 300px;"></div><!-- id="schedulemap" -->
<!---->
<!--                            <div class="carousel-caption">-->
<!--                                <h3>Maps</h3>-->
<!--                                <p>위치 정보 : local name</p>-->
<!--                            </div>-->
                        </div>
                        <div class="item">
                            <div id="schedulefile" style="width: 100%;height: 300px;"></div><!-- id="schedulefile" -->
<!---->
<!--                            <div class="carousel-caption">-->
<!--                                <h3>file preview</h3>-->
<!--                                <p>연관 파일 : ??</p>-->
<!--                            </div>-->
                        </div>
                        <div class="item">
                            <div id="freediv" style="width: 100%;height: 300px;"></div>
<!---->
<!--                            <div class="carousel-caption" style="bottom: 0; background-color: rgba(39, 40, 44, 0.3)">-->
<!--                                <h3>할일 퍼센트</h3>-->
<!--                                <p>그래프 : ??</p>-->
<!--                            </div>-->
                        </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                </div>
            </div>

            <!--내용-->
            <blockquote class="info" style="margin-bottom: 0">
                <!--title-->
                <p style="font-weight: 300">
                    <i class="fa fa-hashtag"></i><span id="scheduletitle" style="font-size: 25px;"><div class="onoffswitch">
                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" >
                            <label class="onoffswitch-label" for="myonoffswitch">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div></span>

                    <span id="locationinfo" style="float: right">
                        <span id="location"></span>&nbsp; <!--나중에 DB 값으로 보여주기:place_name-->

                        <span id="weather_name"></span>&nbsp;
                        <span id="temp" style="font-weight: 600;"></span>℃
                    </span>
                </p>
                <!--date info + ect-->
                <footer style="letter-spacing: 1px; margin-left: 10px">
                    <i class="fa fa-calendar-check-o"></i>
                        <span id="schedule_end" data-toggle="tooltip" data-placement="bottom" title="End date"
                              data-original-title="START is 2016-06-01 00:00"></span>
                    (<cite id="schedule_start" title="Stat date"></cite>)
                </footer>
                <hr>
                <!--contents일정-->
                <div id="schedulecontent" style="margin: 10px; word-break: break-all"></div>

            </blockquote>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Modify</button>
            <button type="submit" id="" class="btn btn-primary" data-dismiss="modal">
                Close
            </button>
        </div>
    </div>
</div>