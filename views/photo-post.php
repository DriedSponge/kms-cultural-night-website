<?php
$post = SQLWrapper()->prepare("SELECT Title,Category,Culture,gid,Images,Caption,Approved,UNIX_TIMESTAMP(Date) AS Date FROM ImagePost WHERE PostID = :pid AND Deleted = 0");
$post->execute([":pid" => $postid]);
$data = $post->fetch();
$author = UserInfo($data['gid']);
$showpost = false;
if ($data == null) {
    $showpost = false;
} else {
    $approval = json_decode($data['Approved'], true);
    if ($approval['Status'] == 0 or $approval['Status'] == 2) {
        if (isset($_SESSION['UserName'])) {
            if (IsAdmin($_SESSION['gid']) or $_SESSION['gid'] == $data['gid']) {
                $showpost = true;
            }
        }else{
            $showpost = false;
        }
    } else {
        $showpost = true;
    }
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <?php include("views/includes/head.php"); ?>
    <?php if ($showpost) { ?>
        <title><?= v($data['Title']); ?> - KMS Cultural Night</title>
        <meta name="description" content="<?= v($data['Caption']); ?>">
    <?php } else { ?>
        <title>Post Not Found - KMS Cultural Night</title>
        <meta name="description" content="The post does not exist!">
    <?php } ?>
</head>

<body>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php

                if (!$showpost) {
                ?>
                    <div class="content-box">
                        <h1>Post does not exist!</h1>
                        <h2>The post does not exist!</h2>
                    </div>
                    <?php
                } else {
                    $ban = IsBanned($data['gid']);
                    if (!$ban['banned'] or isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) {
                    ?>
                        <div class="content-box">
                            <div class="text-center">
                                <?php if ($ban['banned']) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        This user is banned! Reason: <?= htmlspecialchars($ban['Reason']); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <h1 style="margin: 0px"><?= v($data['Title']); ?></h1>
                            <p class="text-center">Posted By <?=v($author['UserName']);?> &bull; Added On <?=v(FormatDate($data['Date']));?></h2>
                            <?php if (isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) { ?>
                                <h2>Admin Actions</h2>
                                <div class="row" style="justify-content: center">
                                    <?php if (!$ban['banned']) { ?>
                                        <script src="<?= htmlspecialchars($dir); ?>admin-scripts/ban.js"></script>
                                        <button onclick="Ban('<?= htmlspecialchars($data['gid']); ?>','<?= htmlspecialchars($dir); ?>')" class="btn btn-danger">Ban</button>
                                    <?php } else { ?>
                                        <script src="<?= htmlspecialchars($dir); ?>admin-scripts/unban.js"></script>
                                        <button onclick="UnBan('<?= htmlspecialchars($data['gid']); ?>','<?= htmlspecialchars($dir); ?>')" class="btn btn-danger">Unban</button>
                                    <?php } ?>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/restrictions.js"></script>
                                    <button onclick="EditRestrictions('<?= htmlspecialchars($data['gid']); ?>','<?= htmlspecialchars($dir); ?>')" class="btn btn-warning">Edit Restrictions</button>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/edit-profile.js"></script>
                                    <button onclick="EditProfile('<?= htmlspecialchars($data['gid']); ?>','<?= htmlspecialchars($dir); ?>')" class="btn btn-success">Edit Profile</button>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/extra-info.js"></script>
                                    <button onclick="ExtraInfo('<?= htmlspecialchars($data['gid']); ?>','<?= htmlspecialchars($dir); ?>')" class="btn btn-info">View Extra Information</button>
                                </div>
                            <?php } ?>
                        </div>
                        

                    <?php } else { ?>
                        <div class="content-box">
                            <h1>The user who made this post is banned!</h1>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>
    <br>

    <?php include("views/includes/footer.php"); ?>


    <script>
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
        navitem = document.getElementById('photoslink').classList.add('active')
    </script>

</body>

</html>