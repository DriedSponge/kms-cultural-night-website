<?php
$post = SQLWrapper()->prepare("SELECT Title,Private,Category,Culture,gid,Content,Approved,UNIX_TIMESTAMP(Date) AS Date FROM TextPost WHERE PostID = :pid");
$post->execute([":pid" => $postid]);
$data = $post->fetch();
$showpost = false;
if ($data == null) {
    $showpost = false;
} else {
    $author = UserInfo($data['gid']);
    $approval = json_decode($data['Approved'], true);
    if ($approval['Status'] == 0 or $approval['Status'] == 2 or $data['Private'] == 1) {
        if (isset($_SESSION['UserName'])) {
            if (IsAdmin($_SESSION['gid'])['admin'] or $_SESSION['gid'] == $data['gid']) {
                $showpost = true;
            }
        } else {
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
                                        The user who posted this is banned.
                                    </div>
                                    <?php }
                                if (isset($_SESSION['gid']) && $_SESSION['gid'] == $data['gid'] or isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) {
                                    if ($approval['Status'] == 0) { ?>
                                        <div class="alert alert-warning" role="alert">
                                            Status: <?= v($approval['Message']); ?>
                                        </div>
                                    <?php }
                                    if ($approval['Status'] == 2) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            Post Blocked: <?= v($approval['Message']); ?>
                                        </div>
                                    <?php }
                                    if ($approval['Status'] == 1) { ?>
                                        <div class="alert alert-success" role="alert">
                                            Your post has been approved and is now visible to all users!
                                        </div>
                                    <?php }
                                    if ($data['Private'] == 1) { ?>
                                        <div class="alert alert-warning" role="alert">
                                            This post is private, and is only visible to you.
                                        </div>
                                <?php }
                                }  ?>
                            </div>
                            <h1 style="margin: 0px" class="text-left"><?= v($data['Title']); ?></h1>
                            <p >Posted By <a href="/profile/<?= v($author['UserName']) ?>"><?= v($author['UserName']); ?></a> &bull; Added On <?= v(FormatDate($data['Date'])); ?> &bull; <?= v($data['Category']); ?> &bull; <?= v($data['Culture']); ?></h2>
                                <div class="container">
                                    
                                </div>
                                <br>
                                <p ><?= v($data['Content']) ?></p>

                                <?php if (isset($_SESSION['gid']) && $_SESSION['gid'] == $data['gid']) { ?>
                                    <h2 class="text-left">Owner Actions</h2>
                                    <div class="row" style="justify-content: left">
                                        <?php if($data['Private']==0){ ?>
                                        <script src="<?= v($dir); ?>js/privatepost.js"></script>
                                        <button onclick="PrivatePost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-warning">Private Post</button>
                                        <?php }else{ ?>
                                        <script src="<?= v($dir); ?>js/publicpost.js"></script>
                                        <button onclick="PublicPost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-success">Make Post Public</button>
                                        <?php } ?>
                                        <script src="<?= v($dir); ?>js/deletepost.js"></script>
                                        <button onclick="DeletePost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-danger">Permanently Delete Post</button>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) { ?>
                                    <h2 class="text-left">Admin Actions</h2>
                                    <div class="row" style="justify-content: left">
                                        <?php if ($approval['Status'] != 2) { ?>
                                            <script src="<?= v($dir); ?>admin-scripts/denypost.js"></script>
                                            <button onclick="BlockPost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-danger">Block Post</button>
                                        <?php }
                                        if ($approval['Status'] != 1) { ?>
                                            <script src="<?= v($dir); ?>admin-scripts/approvepost.js"></script>
                                            <button onclick="ApprovePost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-success">Approve Post</button>
                                        <?php } ?>
                                        <?php if($data['Private']==0){ ?>
                                        <script src="<?= v($dir); ?>js/privatepost.js"></script>
                                        <button onclick="PrivatePost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-warning">Private Post</button>
                                        <?php }else{ ?>
                                        <script src="<?= v($dir); ?>js/publicpost.js"></script>
                                        <button onclick="PublicPost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-success">Make Post Public</button>
                                        <?php } ?>
                                        <script src="<?= v($dir); ?>js/deletepost.js"></script>
                                        <button onclick="DeletePost('<?= v($postid); ?>','<?= v($dir); ?>')" class="btn btn-danger">Permanently Delete Post</button>
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
        navitem = document.getElementById('textlink').classList.add('active')
    </script>

</body>

</html>