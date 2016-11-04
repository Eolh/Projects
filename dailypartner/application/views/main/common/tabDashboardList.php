<!-- 2016-07-26 getTabDashboardList -->
<?php $x=0;

foreach ($dashboardList as $dashboard) { ?>

    <div role="tabpanel" class="tab-pane fade" id="group<?=$dashboard->gnum?>">
        <!--그룹 대시보드-->
        <div class="row dashboard-base">
            <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-left item">
                <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box">
                    <div class="wiget panel">
                        <a href="/group/calendar/<?= $dashboard->gnum ?>" style="color: #e0e8e8;">
                            <p class="wiget-handle texture-img"> 1 週間 </p>
                        </a>

                        <div id="calendarDIV_<?=$dashboard->gnum?>"><!-- 2016-07-31 group's member list -->
                            <div id="jssorSlider<?= $dashboard->gnum; ?>" style="display: block; position: relative; border: 1px solid black;">
                                <div data-u="slides" style="cursor: default; width: 1600px; height: 150px; border: 1px solid purple; overflow: hidden;">
                                    <div id="create_<?=$dashboard->gnum?>_<?=$dashboard->create_by?>_events" style="display: none;">
                                        <img data-u="image" src="/public/img/defaultMaster.jpg">
                                    </div><!-- all the plan -->
                                    <?php foreach($dashboard->memberList as $member) {  ?>
                                    <div id="member_<?=$dashboard->gnum?>_<?=$member->gm_id?>_events" style="display: none;">
                                        <img data-u="image" src="/public/img/<?=$x;?>.png">
                                    </div>
                                          <?php $x++; } ?><!-- member's plan -->
                                          <!--<a data-u="add" href="http://www.jssor.com" style="display:none">Jssor Slider</a>-->
                                </div>
                            </div>

                            <div id="tabDashboardCalendar<?= $dashboard->gnum ?>" style="display: block; position: relative; margin-top: 150px"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sx-12">
                <div class="col-sx-12 col-sm-6 col-lg-6 dashboard-center item">
                    <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box" id="dashboard-box-n3">
                        <div class="wiget panel">
                            <a href="/group/group/<?=$dashboard->gnum;?>"><p class="wiget-handle texture-img">地図</p></a>

                            <div>
                                <div id="group_maps<?=$dashboard->gnum;?>" style="width: 100%; min-height: 230px"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sx-12 col-sm-6 col-lg-6 dashboard-right item">
                    <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box">
                        <div class="wiget panel">
                            <a href="/group/boardList/<?=$dashboard->gnum;?>"><p class="wiget-handle texture-img">Notice</p></a>

                            <div id = group_boardList>掲示板
                                <div></div><!--게시판-->
                            </div>
                        </div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box">
                        <div class="wiget panel">
                            <a href="/group/file/<?= $dashboard->gnum ?>"><p class="wiget-handle texture-img">ファイル</p></a>

                            <div>
                                <div class="list-group" id="group_fileList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sx-12 col-sm-12 dashboard-center item">
                <div class="col-sx-12 col-sm-6 dashboard-box">
                    <div class="wiget panel">
                        <a href="#"><p class="wiget-handle texture-img">graph</p></a>

                        <div>
                            <div id="importance_chart" style="min-width: 550px; height: 500px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sx-12 col-sm-6 dashboard-box">
                    <div class="wiget panel">
                        <a href="#"><p class="wiget-handle texture-img">graph</p></a>

                        <div>
                            <div id="date_chart" style="min-width: 550px; height: 500px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <!--통계3-->
<!--                <div class="col-sx-12 col-sm-6 dashboard-box">-->
<!--                    <div class="wiget panel">-->
<!--                        <a href="#"><p class="wiget-handle texture-img">graphy3</p></a>-->
<!---->
<!--                        <div>통계3(??)-->
<!--                            <div></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>
<?php } ?>