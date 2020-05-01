function PublicPost(pid, url,table) {
    if(table == null){
        var table = null
    }
    $.post(`${url}ajax/private-post.php`, {
        pid: pid,
        public: 1,
        table:table
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
