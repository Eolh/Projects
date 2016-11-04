/**
 * Created by SAMSUNG on 2016-05-16.
 Accuweather API : start
 http://developer.accuweather.com/apis
 /*
 typeN :
 0(weatherWidget.php에 존재하는 속성 값),
 1(dashboard.php - brifing)
 (location, weathername, temp만 가져오는 요약 정보)
 */
function accuweather(lat, lon, typeN) {
    //NJY7SpeAgyCVwvUR4Qu51gs1VM5AAbJr
    var key = '00OP01KwQFbZfo9aonkvpZcRKQpqSpxI'; //00OP01KwQFbZfo9aonkvpZcRKQpqSpxI다됨
    var lang = 'ja'; //ko,ko-KR, japan : ja,ja-jp

    locationInfo(lat, lon, key, lang, typeN); //[2332052, '대구 북구 복현동'];
}

function locationInfo(lat, lon, key, lang, typeN) { //현 위치,

    var resourceURL = 'https://dataservice.accuweather.com/locations/v1/cities/geoposition/search?apikey=' + key + '&q=' + lat + '%2C' + lon + '&language=' + lang + '&details=false&toplevel=false';

    console.log(resourceURL,lat, lon);

    $.ajax({
        type: "GET",
        dataType: "jsonp",        //jsonp: "callback", jsonpCallback: "callback",
        url: resourceURL,
        cache: false,
        async: false,
        success: function (data) {
            var l = data; //weatherInfo
            var locationKey = l.Key;
            var locationName = l.AdministrativeArea.LocalizedName + ' ' + l.SupplementalAdminAreas[0].LocalizedName + ' ' + l.LocalizedName; //대구 북구 복현동
            locationValue = {"locationKey": locationKey, "locationName": locationName};

        },
        error: function (error, data) {
            window.alert(" " + data);
            locationValue = error;
        },
        complete: function (complete) {
            var accuweatherCurrent = 'https://dataservice.accuweather.com/currentconditions/v1/' + locationValue.locationKey + '?apikey=' + key
                + '&language=' + lang + '&details=true';
            var accuweatherForecasts = 'https://dataservice.accuweather.com/forecasts/v1/daily/5day/' + locationValue.locationKey + '?apikey=' + key
                + '&language=' + lang + '&details=false&metric=true';

//https://developer.accuweather.com/accuweather-current-conditions-api/apis/get/currentconditions/v1/%7BlocationKey%7D
            $.ajax({
                type: "GET",
                dataType: "jsonp",
                url: accuweatherCurrent,
                cache: false,
                success: function (data) {
                    var w = data; //weatherInfo
                    var location = locationValue.locationName;
                    var weather_name = w[0].WeatherText; //날씨 이름
                    var weather_icon = w[0].WeatherIcon; //아이콘 번호
                    var temp = w[0].Temperature.Metric.Value; //현재온도
                    var windy_speed = w[0].Wind.Speed.Metric.Value + w[0].Wind.Speed.Metric.Unit;
                    var humidity = w[0].RelativeHumidity;

//'https://apidev.accuweather.com/developers/Media/Default/WeatherIcons/"+ weather_icon +"-s.png'
                    if (typeN) {
                        $("#location").text(location);
                        $("#weather_name").text(weather_name);
                        $("#temp").text(temp);
                        if (typeN == 0 || typeN == 1) {
                            /*weatherWidget*/
                            $("#humidity").text(humidity);
                            if (weather_icon > 32) { //night:33
                                $("#weather_widget_bg").css({"background":"url('/public/img/w_night.png')", "background-size":"cover"});
                            }
                            $("#weather_icon").html("<img class='weather_icon' src='/public/img/AccuWeatherHD/" + weather_icon + ".PNG' style='width: 50%; height: auto; float: right; z-index: 5'>");

                            $("#windy_speed").text(windy_speed);
                            $("#temp_feel").text(temp + 2.5);
                            if (typeN == 1) {
                                /*dashboard - breafing*/
                                $("#b_location").text(location);
                                $("#b_weather_name").text(weather_name);
                                $("#b_temp").text(temp);
                            }
                        }
                    } else {
                        window.alert(typeN + "is not setting typeNumber");
                    }
                }
            });

//https://developer.accuweather.com/accuweather-forecast-api/apis/get/forecasts/v1/daily/5day/%7BlocationKey%7D
            $.ajax({
                type: "GET",
                dataType: "jsonp",
                url: accuweatherForecasts,
                cache: false,
                success: function (data) {
                    var w = data; //weatherInfo
                    //if 현재날짜 24[0], 28일을 알고싶다면, 알고싶은 날짜-현재날짜 = 인덱스 값; [4]
                    var temp_min = w.DailyForecasts[0].Temperature.Minimum.Value; //0:today, 최저기온
                    var temp_max = w.DailyForecasts[0].Temperature.Maximum.Value; //0:today, 최고기온
                    //console.log(data);
                    if (typeN == 0 || typeN == 1) {
                        $("#temp_max").text(temp_max);
                        $("#temp_min").text(temp_min);
                    }
                }
            });
        }
    })
    ;
}