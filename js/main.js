function swaploading(){
    document.getElementById("submit-arrow-pwin-img").src = "/img/loading.gif";
    var http = new XMLHttpRequest();
    var url = '/api/';
    var paramslogin = 'action=login&';
    var params = paramslogin.concat($('form').serialize());
    http.open('POST', url, true);

//Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            document.getElementById("submit-arrow-pwin-img").src = "/img/ok.png";
            var delayInMilliseconds = 3000; //1 second

            setTimeout(function() {
                window.location.href = "/dashboard/";
            }, delayInMilliseconds);
        }
        if(http.readyState == 4 && http.status == 403) {
            document.getElementById("submit-arrow-pwin-img").src = "/img/error.png";
            var delayInMilliseconds = 1500; //1 second

            setTimeout(function() {
                document.getElementById("submit-arrow-pwin-img").src = "/img/RightArrow.png";
            }, delayInMilliseconds);
        }
    }
    http.send(params);
}

function onpagloadcheck(){
    var http = new XMLHttpRequest();
    var url = '/api/';
    var params = 'action=login';
    http.open('POST', url, true);

//Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            document.getElementById("submit-arrow-pwin-img").src = "/img/ok.png";
            var delayInMilliseconds = 1000; //1 second

            setTimeout(function() {
                window.location.href = "/dashboard/";
            }, delayInMilliseconds);
        }
    }
    http.send(params);
}