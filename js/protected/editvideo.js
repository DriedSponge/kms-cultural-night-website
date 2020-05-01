function EditVideo(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/edit-videoid.php`, {
        pid: pid,
        edit: 1,
        table: table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
