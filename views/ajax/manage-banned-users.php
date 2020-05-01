<?php
if (isset($_POST['load'])) {
    if (isset($_SESSION['UserName'])) {
        if(IsAdmin($_SESSION['gid'])['admin']){
        if (isset($_POST['page'])) {
            $page = intval($_POST['page']);
        } else {
            $page = 1;
        }
        $col = $_POST['col'];
        $ord = $_POST['order'];
        $records_per_page = 75;
        $starting_limit_number = ($page - 1) * $records_per_page;
        $limit = $starting_limit_number . "," . $records_per_page;
        $query = SQLWrapper()->prepare("SELECT gid,AdminID,Reason,Date,UserInfo,UNIX_TIMESTAMP(Date) AS Date FROM Bans ORDER BY $col $ord LIMIT :start,:end");
        $query->bindParam(':start', $starting_limit_number, PDO::PARAM_INT);
        $query->bindParam(':end', $records_per_page, PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetchAll();
        foreach ($data as $ban) {
            $Admin = UserInfo($ban['AdminID']);
            $User = json_decode($ban['UserInfo'],true);
?>
             <tr class="search ">
                <td><a href="/profile-id/<?= v($ban['gid']); ?>" target="_blank"><?= v($User['UserName']); ?></a></td>
                <td><a href="/profile-id/<?= v($ban['AdminID']); ?>" target="_blank"><?= v($Admin['UserName']); ?></a></td>
                <td><?= v($ban['Reason']); ?></td>
                <td><?= v(FormatDate($ban['Date'])); ?></td>
               
            </tr>

<?php
        }
    }else{
        AlertError("Unauthorized");

    }
    } else {
        AlertError("Not logged in");
    }
}
