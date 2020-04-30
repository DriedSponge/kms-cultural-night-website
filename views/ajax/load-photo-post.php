<?php
if (isset($_POST['load'])) {
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
    $query = SQLWrapper()->prepare("SELECT Title,Category,Culture,gid,Approved,PostID,UNIX_TIMESTAMP(Date) AS Date FROM ImagePost WHERE Private=0 AND Title IS NOT NULL AND Category IS NOT NULL ORDER BY $col $ord LIMIT :start,:end");
    $query->bindParam(':start', $starting_limit_number, PDO::PARAM_INT);
    $query->bindParam(':end', $records_per_page, PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetchAll();
    foreach ($data as $post) {
        $author = UserInfo($post['gid']);
        $approval = json_decode($post['Approved'],true);
        if($approval['Status'] == 1 or isset($_SESSION['gid']) && $_SESSION['gid'] == $post['gid'] or isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']){
            if($approval['Status'] == 2){
                $class = "table-danger";
                $title = "Blocked Post";
            }else if($approval['Status'] == 0){
                $class = "table-warning";
                $title = "Awaiting approval";
            }else{
                $class = null;
                $title = null;
            }
        ?>
            <tr class="search <?php echo $class; ?>" title=" <?php echo $title; ?>">
                <td><a href="/profile-id/<?= v($post['gid']); ?>" target="_blank"><?= v($author['UserName']); ?></a></td>
                <td><?= v($post['Title']); ?></td>
                <td><?= v($post['Category']); ?></td>
                <td><?= v($post['Culture']); ?></td>
                <td><?= v(FormatDate($post['Date'])); ?></td>
                <td class="td-actions">
                    <a style="color:white" href="/photos/<?= v($post['PostID']); ?>" title="Open" rel="tooltip" class="btn btn-info btn-sm">
                        Open
                    </a>
                </td>
            </tr>

<?php
    }
    }
}