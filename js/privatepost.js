function PrivatePost(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/private-post.php`, {
        pid: pid,
        private: 1,
        table:table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
