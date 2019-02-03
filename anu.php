<?php
if (isset($_POST['lat']) && isset($_POST['lng'])) {
    $data = json_encode(
        array(
            "location" => array(
                "lat" => $_POST['lat'],
                "lng" => $_POST['lng']
            )
        ), JSON_PRETTY_PRINT
    );
    file_put_contents("lokasi.txt", $data . "\n", FILE_APPEND);
    echo $data;
}
else {
?>

<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
} else {
    alert('Anda tidak mengizinkan kami, Muat ulang dan izinkan untuk memulai');
}

function successFunction(position) {
    var lat = position.coords.latitude;
    var long = position.coords.longitude;
    // console.log('Your latitude is :'+lat+' and longitude is '+long);
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/api/anu.php', true);

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // Request finished. Do processing here.
            alert(xhr.responseText);
        }
    }
    xhr.send("lat="+ lat +"&lng=" + long); 
}

function errorFunction(err) {
    console.log(err);
    alert(err);
}
</script>
<?php
}
?>