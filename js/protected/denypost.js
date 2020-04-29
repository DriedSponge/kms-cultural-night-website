function BlockPost(pid, url) {
    $.post(`${url}ajax/block-post.php`, {
        pid: pid,
        block: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
