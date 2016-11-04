<div class="modal fade" id="gp_schedule">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <!--file view-->
                <div id="file"></div>
                <blockquote class="info" style="margin-bottom: 0">
                    <!--title-->
                    <p style="font-weight: 300">
                        <i class="fa fa-hashtag"></i> <span id="title" style="font-size: 25px;"></span>
                    </p>
                    <!--date info + ect-->
                    <footer style="letter-spacing: 1px; margin-left: 10px">
                        <i class="fa fa-calendar-check-o"></i>
                        <span id="endTime" data-toggle="tooltip" data-placement="bottom" title="End date"
                              data-original-title="STAT is 2016-06-01 00:00"></span>
                        (<cite id="staTime" title="Stat date"></cite>)
                    </footer>
                    <hr>
                    <!--contents-->
                    <div id="content" style="margin: 10px"></div>
                </blockquote>
            </div>
            <div class="modal-footer">
                <button id="scheduleAddBtn" type="button" class="btn btn-default" data-dismiss="modal">Modify</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>