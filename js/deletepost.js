function DeletePost(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/delete-post.php`, {
        pid: pid,
        delete: 1,
        table:table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
