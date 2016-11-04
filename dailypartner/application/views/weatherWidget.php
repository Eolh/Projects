<div class="weatherWeget row">

    <div style="height: 15%; text-align: center; vertical-align: middle; font-size: 20px">
        <span id="location"></span>
    </div>
    <div style="height: 43%;">
        <div class="col-xs-6" style="padding: 0;">
            <div id="weather_icon"></div>
        </div>
        <div class="col-xs-6" style="font-family:'Source Sans Pro', Arial, sans-serif;font-weight: 300;font-size: 36px; padding-left: 0;height: 100%; padding-top: 10px;">
            <span id="temp" style="font-family:'Source Sans Pro', Arial, sans-serif;font-weight: 300; font-size: 40px; float: left;"></span>℃
        </div>
    </div>
    <div style="height: 26%">
        <div style="font-size: 18px;text-align: center;">
            <p id="weather_name" style="font-size: 24px; line-height: 10px; margin-bottom: 20px"></p>
            <p>
                <span id="temp_max" style="font-size: 20px"></span>˚/
                <span id="temp_min"></span>˚
                &nbsp;体感温度<span id="temp_feel"></span>˚<!-- +체감온도 -->
            </p>
        </div>
    </div>
    <div style="height: 16%;">
        <div class="col-sm-8">
            <img src="/public/img/w_notice.png"><span style="vertical-align: bottom">傘準備</span>
        </div>
        <div class="col-sm-4" style="text-align:right;font-size: 16px; padding: 0; line-height: 10px">
            <i class="fa fa-tint" aria-hidden="true"></i><span id="humidity"></span>%
            <br><i class="fa fa-flag" aria-hidden="true"></i><span id="windy_speed"></span>
        </div>
    </div>

</div>
<!--
location : 현 위치 이름
weather_icon : 아이콘
weather_name : 날씨 이름
temp : 현재온도
temp_max : 최고온도
temp_min : 최저온도
humidity : 습도
windy_speed : 풍속
℃˚
-->