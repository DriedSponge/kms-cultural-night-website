function ApprovePost(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/approve-post.php`, {
        pid: pid,
        approve: 1,
        table: table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
