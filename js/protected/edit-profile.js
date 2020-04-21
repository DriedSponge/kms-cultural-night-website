function EditProfile(gid, url) {
    $.post(`${url}ajax/admin-edit-profile.php`, {
        gid: gid,
        edit: 1
    })
    .done(function(data){
        $("#modal").html(data);
    })
}   
