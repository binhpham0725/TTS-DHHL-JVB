const weatherAPI = "https://hanoimoi.vn/api/getweather";

let weatherData = [];
let index = 0;

$(document).ready(function(){
    $.get(weatherAPI, function(data){
        weatherData = JSON.parse(data);
        showWeather();
        setInterval(function(){
            index++;
            if(index >= weatherData.length){
                index = 0;
            }
            showWeather();
        },15000);
    });
});

function showWeather(){
    let city = weatherData[index].CityName;
    let temp = weatherData[index].Currtent.TempC;
    $("#city").text(city);
    $("#temp").text(temp + "°C");
}

function updateDate(){
    const now = new Date();
    const options = {
        weekday:"long",
        day:"2-digit",
        month:"2-digit",
        year:"numeric"
    };

    $("#date").text(now.toLocaleDateString("vi-VN", options));
}

updateDate();