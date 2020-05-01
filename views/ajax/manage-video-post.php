<?php
if (isset($_POST['load'])) {
    if (isset($_SESSION['UserName'])) {
        if (IsAdmin($_SESSION['gid'])['admin']) {
            $query = SQLWrapper()->prepare("SELECT Title,Category,Culture,gid,Approved,PostID,UNIX_TIMESTAMP(Date) AS Date FROM VideoPost");
            $query->execute();
            $data = $query->fetchAll();
            foreach ($data as $post) {
                $app = json_decode($post['Approved'], true);
                if ($app['Status'] == 0) {
                    $author = UserInfo($post['gid']);
?>
                    <tr>
                        <td><a href="/profile-id/<?= v($post['gid']);?>" target="_blank"><?= v($author['UserName']); ?></a></td>
                        <td><?= v($post['Title']); ?></td>
                        <td><?= v($post['Category']); ?></td>
                        <td><?= v($post['Culture']); ?></td>
                        <td><?= v(FormatDate($post['Date'])); ?></td>
                        <td class="td-actions">
                            <a style="color:white" href="/videos/<?=v($post['PostID']); ?>" target="_blank" title="Preview" rel="tooltip" class="btn btn-info btn-icon btn-sm ">
                                <i class="far fa-eye"></i>
                            </a>
                            <button title="Approve" onclick="ApprovePost('<?=v($post['PostID']);?>','<?=v($dir);?>','#video-post')" type="button" rel="tooltip" class="btn btn-success btn-icon btn-sm " data-original-title="" title="">
                                <i class="fas fa-check"></i>
                            </button>
                            <button title="Block" onclick="BlockPost('<?=v($post['PostID']);?>','<?=v($dir);?>','#video-post')" type="button" rel="tooltip" class="btn btn-danger btn-icon btn-sm " data-original-title="" title="">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
<?php
                }
            }
        } else {
            AlertError("You are not an admin");
        }
    } else {
        AlertError("Not Logged In");
    }
}
