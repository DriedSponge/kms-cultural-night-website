function BlockPost(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/block-post.php`, {
        pid: pid,
        block: 1,
        table:table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
