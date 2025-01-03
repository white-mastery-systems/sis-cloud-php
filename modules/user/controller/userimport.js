$(document).ready(function () {
    $('#files').change(function () {
        $('#upload_csv').submit();
    });
    $('#upload_csv').on("submit", function (e) {
        $('#preloader').show();
        e.preventDefault(); //form will not submitted  
        $.ajax({
            url: "modules/user/userimport.php",
            method: "POST",
            data: new FormData(this),
            contentType: false, //The content type used when sending data to the server.  
            cache: false, //To unable request pages to be cached  
            processData: false, //To send DOMDocument or non processed data file it is set to false  
            success: function (data) {
                setTimeout(function() { $('#preloader').fadeOut("slow"); }, 200);
                $("#excel_resp").html(data);
                setTimeout(function() { $('#excel_resp').fadeOut("slow"); }, 3000);
            }
        })
    });
});