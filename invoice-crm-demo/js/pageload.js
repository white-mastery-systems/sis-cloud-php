var default_content = "";

$(document).ready(function () {

    checkURL();
    $('ul li a').click(function (e) {

        checkURL(this.hash);

    });

    //filling in the default content
    default_content = $('#content1').html();


    setInterval("checkURL()", 250);

});

var lasturl = "";

function checkURL(hash) {
    if (!hash) hash = window.location.hash;

    if (hash != lasturl) {
        lasturl = hash;

        // FIX - if we've used the history buttons to return to the homepage,
        // fill the pageContent with the default_content

        if (hash == "")
            $('#content1').html(default_content);

        else
            loadPage(hash);
    }
}


function loadPage(url) {
    alert(url);
    url = url.replace('#page', '');

    $('#loading').css('visibility', 'visible');

    $.ajax({
        type: "POST",
        url: "load_page.php",
        data: 'page=' + url,
        dataType: "html",
        success: function (msg) {

            if (parseInt(msg) != 0) {
                $('#content1').html(msg);
                $('#loading').css('visibility', 'hidden');
            }
        }

    });

}