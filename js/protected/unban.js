function UnBan(gid, url) {
    $.post(`${url}ajax/unban-user.php`, {
        gid: gid,
        unban: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
