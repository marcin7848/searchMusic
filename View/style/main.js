var showedPanel = 0;

function getTrack(href, fromAlbum, panelTrack){
    $(".searchResults").html('');
    $(".searchResults").css('display','none');
    $(".trackInfo").css('display', 'none');
    $(".loading").css('display', 'block');


    if(panelTrack == 1){
        $(".backgroundPanel").css('display', 'none');
        $(".panelField").css('display', 'none');

        var positionValue = $("#searchValue").offset();
        if (positionValue.top != 2) {
            var calcPos = -2 * positionValue.top + 4;

            $(".searchFieldFull").animate({
                top: calcPos
            }, 1000, function () {
                $(".searchFieldFull").css('background', 'transparent');
                $(".searchText").css('display', 'none');
                $(".searchFieldFull").css('height', '70px');
            });
        }
    }

    if(fromAlbum == '1'){
        var albumName = $("#albumNameMain").text();
        var search = ['?', ',', '\'', ';', ':', '[', ']', '{', '}', '\\', '|', '@', '#', '$', '%', '^', '&', '=', '+', '`'];
        var replace = ['%3F', '%2C', '%27', '%3B', '%3A', '%5B', '%5D', '%7B', '%7D', '%5C', '%7C', '%40', '%23', '%24', '%25', '%5E', '%26', '%3D', '%2B', '%60'];

        for(var i=0; i<search.length;i++){
            albumName = albumName.replace('/'+search[i]+'/g', replace[i]);
        }

        albumName = albumName.replace(/ /g, '+');

        href = href.replace('\/'+albumName+'\/', '\/_\/');
    }

    $.ajax({
        type: 'GET',
        url: 'http://localhost/index.php?search&trackInfo='+href,
        complete: function (response) {
            $.ajax({
                type: 'GET',
                url: 'http://localhost/index.php?listTrack&checkListTracks&href='+href,
                complete: function (response) {
                    var jsonResponse = JSON.parse(response.responseText);

                    if(jsonResponse.stateTrack == -1){
                        $("#followTrack").text('Zaloguj się!');
                        $("#followTrack").attr('value', -1);
                    }
                    else if(jsonResponse.stateTrack == 0){
                        $("#followTrack").text('Dodaj do listy');
                        $("#followTrack").attr('value', 0);
                    }
                    else{
                        $("#followTrack").text('Usuń z listy');
                        $("#followTrack").attr('value', 1);
                    }

                    $("#trackHref").attr('value', href);
                }
            });

            var jsonResponse = JSON.parse(response.responseText);

            $("#trackImage").attr('src', jsonResponse.album.image);
            $("#trackArtist").text(jsonResponse.track.artist);
            $("#trackName").text(jsonResponse.track.name+' ['+jsonResponse.track.length+']');
            $("#albumName").text('From album: '+jsonResponse.album.name);
            $("#trackListeners").text('Słuchaczy: '+jsonResponse.track.listeners);


            if(jsonResponse.track.description.length > 0){
                $("#trackDescription").html('Opis:<br \/>'+jsonResponse.track.description);
            }
            else{
                $("#trackDescription").css('display', 'none');
            }

            if(jsonResponse.track.youtube.length > 0){
                $("#trackYoutube").attr('src', jsonResponse.track.youtube.replace("watch?v=", "embed\/"));
            }
            else{
                $("#trackYoutubeField").css('display', 'none');
            }

            $("#albumNameMain").text(jsonResponse.album.name);
            $("#albumRelease").text('Data wydania: '+jsonResponse.album.releaseDate);
            $("#albumListeners").text('Słuchaczy: '+jsonResponse.album.listeners);


            var html = '';

            for(var i=0; i < jsonResponse.album.listOfTrack.length; i++){
                html+='<li onclick="getTrack(\''+jsonResponse.album.listOfTrack[i].href+'\', \'1\', \'0\');">';
                html+=jsonResponse.album.listOfTrack[i].name + ' [' + jsonResponse.album.listOfTrack[i].length + ']';
                html+='</li>';
            }

            $(".albumTracks").html(html);

            setTimeout(function(){
                    $(".loading").css('display', 'none');
                    $(".trackInfo").css('display', 'flex');
                }, 2000);

        }
    });

}

function showPanelTracks(){
    $(".backgroundPanel").css('display', 'block');
    $(".panelField").css('width', '600px');
    $(".panelField").css('height', '0px');

    $.ajax({
        type: 'GET',
        url: 'http://localhost/index.php?listTrack&getListTracks',
        complete: function (response) {
            var jsonResponse = JSON.parse(response.responseText);

            var html = '';
            var i;
            for(i=0; i<jsonResponse.listTracks.length;i++){
                html+='<div class="panelList">\n' +
                    '    <div onclick="getTrack(\''+jsonResponse.listTracks[i].href+'\', \'0\', \'1\');" class="panelTrack">\n' +
                    '        <p class="panelText">'+jsonResponse.listTracks[i].name+'</p>\n' +
                    '        <p class="panelText">'+jsonResponse.listTracks[i].artist+'</p>\n' +
                    '        <p class="panelText">'+jsonResponse.listTracks[i].album+'</p>\n' +
                    '    </div>\n' +
                    '    <div onclick="deleteTrack(\''+jsonResponse.listTracks[i].href+'\');" class="panelDelete">\n' +
                    '        <img style="margin-top: 10px; max-width: 100%; max-height: 100%;" src="./View/images/delete.png" />' +
                    '    </div>\n' +
                    '</div>'
            }

            var showResultPanelTrack = function () {
                if (i < jsonResponse.listTracks.length) {
                    setTimeout(showResultPanelTrack, 10);
                }
                else {
                    $(".panelField").css('display', 'block');
                    $(".panelField").html('<p class="panelTitle">Twoja lista muzyczna</p>'+html);
                    $(".panelField").animate({
                        height: 60*(jsonResponse.listTracks.length+1)+'px'
                    }, 1000, function () {});
                }
            }
            showResultPanelTrack();

        }
    });
}

function deleteTrack(href){
    $.ajax({
        type: 'GET',
        url: 'http://localhost/index.php?listTrack&deleteTrack&href='+href,
        complete: function (response) {
            var jsonResponse = JSON.parse(response.responseText);

            if(jsonResponse.deleteTrack == '0'){
                alert('Problem z usunięciem tracku!');
            }
            else{
                showPanelTracks();
            }
        }
    });

}

$(function() {
   function work(){
        if($("#searchValue").val().length == 0){
            $("#errorSearch").text('Wpisz tytuł utworu!');
            $("#errorSearch").css('display','block');
            setTimeout(function(){
                $("#errorSearch").css('display','none');
            }, 2000);
        }
        else {
            $(".searchResults").html('');
            $(".searchResults").css('display','none');
            $(".trackInfo").css('display', 'none');

            $(".loading").css('display', 'block');

            $.ajax({
                type: 'GET',
                url: 'http://localhost/index.php?search&tracks='+$("#searchValue").val(),
                complete: function (response) {
                    var jsonResponse = JSON.parse(response.responseText);
                    $(".loading").css('display', 'none');

                    if(jsonResponse.length == 0){
                        $("#errorSearch").text('Brak znalezionych utworów!');
                        $("#errorSearch").css('display','block');
                        setTimeout(function(){
                            $("#errorSearch").css('display','none');
                        }, 2000);
                    }
                    else{
                        var done = 0;

                        var positionValue = $("#searchValue").offset();
                        if (positionValue.top != 2) {
                            var calcPos = -2 * positionValue.top + 4;

                            $(".searchFieldFull").animate({
                                top: calcPos
                            }, 1000, function () {
                                $(".searchFieldFull").css('background', 'transparent');
                                $(".searchText").css('display','none');
                                $(".searchFieldFull").css('height', '70px');
                                done = 1;
                            });
                        }
                        else{
                            done = 1;
                        }

                        var showResults = function() {
                            if (done == 0) {
                                setTimeout(showResults, 20);
                            }
                            else {

                                var html = '';
                                var i;

                                for (i = 0; i < jsonResponse.length; i++) {
                                    html += '<div onclick="getTrack(\'' + jsonResponse[i].href + '\', \'0\', \'0\');" class="result">';
                                    html += jsonResponse[i].name + ' - ' + jsonResponse[i].artist + '   ['+jsonResponse[i].length+']';
                                    html += '</div>';
                                }

                                var print = function () {
                                    if (i < jsonResponse.length) {
                                        setTimeout(showResults, 10);
                                    }
                                    else {
                                        $(".searchResults").html(html);
                                        $(".searchResults").css('display', 'block');
                                    }
                                }
                                print();
                            }
                        }

                        showResults();
                    }

                }
            });
        }
    }

    $("#searchSubmit").click(work);
    $('#searchValue').keyup(function(e){
        if(e.keyCode == 13)
        {
            work();
        }
    });


    $("#registerMenu").click(function(){
        $(".backgroundPanel").css('display', 'block');
        $(".panelField").css('width', '400px');
        $(".panelField").css('height', '0px');
        $(".panelField").css('display', 'block');

        $(".panelField").html('<p class="panelTitle">REJESTRACJA</p>\n' +
            '\n' +
            '<p class="panelText">Nazwa użytkownika</p>\n' +
            '<input type="text" id="username"  name="username" maxlength="20" size="20" autocomplete="off" /><p class="errorPanelText" id="errorUsername"></p>\n' +
            '\n' +
            '<p class="panelText">Email</p>\n' +
            '<input type="text" id="email" name="email" maxlength="40" size="20" autocomplete="off" /><p class="errorPanelText" id="errorEmail"></p>\n' +
            '\n' +
            '<p class="panelText">Hasło</p>\n' +
            '<input type="password" id="password" name="password" maxlength="30" size="20" autocomplete="off" /><p class="errorPanelText" id="errorPassword"></p>\n' +
            '\n' +
            '<p class="panelText">Powtórz hasło</p>\n' +
            '<input type="password" id="confirmPassword" name="confirmPassword" maxlength="30" size="20" autocomplete="off" /><p class="errorPanelText" id="errorConfirmPassword"></p>\n' +
            '\n' +
            '<p>\n' +
            '<input type="checkbox" id="termAccept"  name="termAccept" value="1" />\n' +
            'Akceptuję <a href="#reg">regulamin</a> używania portalu<p class="errorPanelText" id="errorTermAccept"></p>\n' +
            '</p>\n' +
            '\n' +
            '<input class="buttonPanel" type="submit" id="register" name="register" value="Zarejestruj!" /><br /><br />\n' +
            '<input class="buttonPanel" type="submit" id="close" name="close" value="Przerwij" />');

        $(".panelField").animate({
            height: '500px'
        }, 1000, function () {});

    });

    $(document).on("click", "#close", function() {
        $(".panelField").css('display', 'none');
        $(".backgroundPanel").css('display', 'none');
    });

    $(document).on("click", "#register", function() {
        $("#errorUsername").text('');
        $("#errorEmail").text('');
        $("#errorPassword").text('');
        $("#errorConfirmPassword").text('');
        $("#errorTermAccept").text('');

        var error = 0;
        if($("#username").val().length == 0){
            $("#errorUsername").text('Wpisz nazwę użytkownika!');
            error = 1;
        }
        if($("#email").val().length == 0){
            $("#errorEmail").text('Wpisz email!');
            error = 1;
        }
        if($("#password").val().length == 0){
            $("#errorPassword").text('Wpisz hasło!');
            error = 1;
        }
        if($("#confirmPassword").val().length == 0){
            $("#errorConfirmPassword").text('Powtórz hasło!');
            error = 1;
        }
        if(!$("#termAccept").is(':checked')){
            $("#errorTermAccept").text('Zaakceptuj regulamin!');
            error = 1;
        }

        if(error == 0){
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!re.test(String($("#email").val()).toLowerCase())){
                $("#errorEmail").text('Niepoprawny email!');
                error = 1;
            }

            if($("#password").val() != $("#confirmPassword").val()){
                $("#errorPassword").text('Hasła nie są identyczne!');
                $("#errorConfirmPassword").text('Hasła nie są identyczne!');
                error = 1;
            }

            if(error == 0){
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost/index.php?account&checkCreateAccount',
                    data: {'login': $("#username").val(), 'email': $("#email").val()},
                    complete: function (response) {
                        var jsonResponse = JSON.parse(response.responseText);

                        if(jsonResponse.login == '0'){
                            $("#errorUsername").text('Taka nazwa użytkownika już istnieje!');
                            error = 1;
                        }

                        if(jsonResponse.email == '0'){
                            $("#errorEmail").text('Taki email już istnieje!');
                            error = 1;
                        }

                        if(error == 0){
                            $.ajax({
                                type: 'POST',
                                url: 'http://localhost/index.php?account&createAccount',
                                data: {'login': $("#username").val(), 'email': $("#email").val(), 'password': $("#password").val(), 'confirmPassword': $("#confirmPassword").val(), 'termsAccept': '1'},
                                complete: function (response) {
                                    var jsonResponse = JSON.parse(response.responseText);

                                    if(jsonResponse.resultCreate == 0){
                                        $("#errorTermAccept").text('Nieznany błąd podczas rejestracji!');
                                    }
                                    else{
                                        $(".panelField").html('');

                                        $(".panelField").animate({
                                            height: '100px'
                                        }, 1000, function () {
                                            $(".panelField").html('<p class="panelTitle">REJESTRACJA</p>\n' +
                                                '<p style="text-align: center;">Poprawnie zarejestrowano! Zaloguj się!</p>');

                                            setTimeout(function () {
                                                $(".panelField").css('display', 'none');
                                                $(".backgroundPanel").css('display', 'none');
                                            }, 2000);

                                        });
                                    }
                                }
                            });
                        }
                    }
                });

            }

        }

    });

    $("#loginMenu").click(function(){
        $(".backgroundPanel").css('display', 'block');
        $(".panelField").css('width', '400px');
        $(".panelField").css('height', '0px');
        $(".panelField").css('display', 'block');

        $(".panelField").html('<p class="panelTitle">Logowanie</p>\n' +
            '\n' +
            '<p class="panelText">Nazwa użytkownika</p>\n' +
            '<input type="text" id="username"  name="username" maxlength="20" size="20" autocomplete="off" /><p class="errorPanelText" id="errorUsername"></p>\n' +
            '\n' +
            '<p class="panelText">Hasło</p>\n' +
            '<input type="password" id="password" name="password" maxlength="30" size="20" autocomplete="off" /><p class="errorPanelText" id="errorPassword"></p>\n' +
            '\n<br/><br/>' +
            '<p class="errorPanelText" id="errorLogin"></p><br/><br/>\n' +
            '<input class="buttonPanel" type="submit" id="login" name="login" value="Zaloguj się!" /><br /><br />\n' +
            '<input class="buttonPanel" type="submit" id="close" name="close" value="Przerwij" />');

        $(".panelField").animate({
            height: '320px'
        }, 1000, function () {});

    });

    $(document).on("click", "#login", function() {
        $("#errorUsername").text('');
        $("#errorPassword").text('');
        $("#errorLogin").text('');

        var error = 0;
        if($("#username").val().length == 0){
            $("#errorUsername").text('Wpisz nazwę użytkownika!');
            error = 1;
        }
        if($("#password").val().length == 0){
            $("#errorPassword").text('Wpisz hasło!');
            error = 1;
        }

        if(error == 0){
            $.ajax({
                type: 'POST',
                url: 'http://localhost/index.php?account&login',
                data: {'login': $("#username").val(), 'password': $("#password").val()},
                complete: function (response) {
                        var jsonResponse = JSON.parse(response.responseText);

                        if(jsonResponse.resultLogin == '0'){
                            $("#errorLogin").text('Nazwa użytkownika lub hasło są niepoprawne!');
                        }
                        else{
                            location.reload();
                        }
                    }
                });

            }
    });

    $(document).on("click", "#logout", function() {
        $.ajax({
            type: 'GET',
            url: 'http://localhost/index.php?account&logout',
            complete: function () {
                location.reload();
            }
        });
    });

    $(document).on("click", "#followTrack", function() {
        if($("#followTrack").val() == -1){
            alert('Zaloguj się, by móc dodać track do swojej listy!');
        }
        if($("#followTrack").val() == 0){
            $.ajax({
                type: 'POST',
                url: 'http://localhost/index.php?listTrack&addTrack&href='+$("#trackHref").val(),
                data: {'href': $("#trackHref").val(), 'name': $("#trackName").text(), 'artist': $("#trackArtist").text(), 'album': $("#albumNameMain").text()},
                complete: function (response) {
                    var jsonResponse = JSON.parse(response.responseText);

                    if(jsonResponse.addTrack == '0'){
                        alert('Problem z dodaniem tracku!');
                    }
                    else{
                        $("#followTrack").text('Usuń z listy');
                        $("#followTrack").attr('value', 1);
                    }
                }
            });
        }
        if($("#followTrack").val() == 1){
            $.ajax({
                type: 'GET',
                url: 'http://localhost/index.php?listTrack&deleteTrack&href='+$("#trackHref").val(),
                complete: function (response) {
                    var jsonResponse = JSON.parse(response.responseText);

                    if(jsonResponse.deleteTrack == '0'){
                        alert('Problem z usunięciem tracku!');
                    }
                    else{
                        $("#followTrack").text('Dodaj do listy');
                        $("#followTrack").attr('value', 0);
                    }
                }
            });
        }
    });


    $("#panelSoundTracksMenu").click(function(){
        if(showedPanel == 0){
            showedPanel = 1;
            showPanelTracks();
        }
        else{
            showedPanel = 0;
            $(".backgroundPanel").css('display', 'none');
            $(".panelField").css('display', 'none');
        }
    });


});