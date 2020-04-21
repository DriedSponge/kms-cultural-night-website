function EditRestrictions(gid, url) {
    $.post(`${url}ajax/restrictions.php`, {
        gid: gid,
        restrict: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
