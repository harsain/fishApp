/**
 * Created by Harsain on 19/06/2016.
 */
$(function () {
    //alert("testing");
    getLocation();
});

function getLocation() {
    if (navigator.geolocation) {
        var optn = {
            enableHighAccuracy: true,
            timeout: Infinity,
            maximumAge: 0
        };
        navigator.geolocation.getCurrentPosition(getWeatherFoLocation, handleError, optn);
    } else {
        alert("Geolocation is not supported by your browser");
    }
}

function getWeatherFoLocation(position) {
    alert(position);
}

function handleError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert("User denied permission");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable");
            break;
        case error.TIMEOUT:
            alert("The request to get User's location timedout");
            break;
        case error.UNKNOWN_ERR:
            alert("Unknown error occurred");
            break;
    }
}