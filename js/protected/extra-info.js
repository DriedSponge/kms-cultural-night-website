function ExtraInfo(gid, url) {
    $.post(`${url}ajax/view-extra.php`, {
        gid: gid,
        view: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
