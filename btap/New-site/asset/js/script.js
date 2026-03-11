
const weatherAPI = "https://hanoimoi.vn/api/getweather";

fetch(weatherAPI)
.then(function(response){
    return response.json();
})
.then(function(dataWeather){
    var dataWeather = JSON.parse(dataWeather)
    var firstData = dataWeather[0]
    console.log(firstData)
    var firstCityName = dataWeather[0].CityName;
    console.log(firstCityName)
    var firstTempC = dataWeather[0].Currtent.TempC;

    document.getElementById("city").innerText = firstCityName;
    document.getElementById("temp").innerText = firstTempC + "°C";

});

function updateDate(){

    const now = new Date();

    const options = {
        weekday:"long",
        day:"2-digit",
        month:"2-digit",
        year:"numeric"
    };

    document.getElementById("date").innerText =
    now.toLocaleDateString("vi-VN", options);
}

updateDate();