function Ban(gid, url) {
    $.post(`${url}ajax/ban-user.php`, {
        gid: gid,
        ban: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
