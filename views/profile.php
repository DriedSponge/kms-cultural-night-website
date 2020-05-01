<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,gid,RealName,UNIX_TIMESTAMP(CreationDate) AS CreationDate FROM Users WHERE Name = :name");
$user->execute([":name" => $name]);
$data = $user->fetch();

?>
<!DOCTYPE HTML>
<html>

<head>
    <?php include("views/includes/head.php"); ?>
    <?php if (!$data == null) { ?>
        <title><?= htmlspecialchars($name); ?>'s Profile - KMS Cultural Night</title>
        <meta name="description" content="<?= htmlspecialchars($data['Bio']); ?>">
    <?php } else { ?>
        <title>User Not Found - KMS Cultural Night</title>
        <meta name="description" content="The user you're looking for does not exist or they changed their username!">
    <?php } ?>
</head>

<body>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php

                if ($data == null) {
                ?>
                    <div class="content-box">
                        <h1>User does not exist!</h1>
                        <h2>The user you're looking for does not exist or they changed their username!</h2>
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
                                <img id="profile-picture" class="img-fluid profile-page-img lozad" alt="<?= htmlspecialchars($name); ?>'s Profile Picture'" data-src="<?= htmlspecialchars($data['Picture']); ?>">
                            </div>
                            <h1 id="username" style="margin: 0px"><?= htmlspecialchars($name); ?></h1>
                            <div class="row" style="justify-content: center">
                                <?php
                                $nsd = IsNsd($data['gid'], false);
                                if ($nsd['nsd']) {
                                ?>
                                    <p style="font-size: 1.5em"><?php echo $nsd['badge']; ?></p>
                                <?php
                                }
                                $admin = IsAdmin($data['gid']);
                                if ($admin['admin']) {
                                ?>
                                    <p style="font-size: 1.5em"><?php echo $admin['badge']; ?></p>
                                <?php
                                }
                                ?>

                            </div>
                            <?php if ($data['Bio'] == null) {
                                $data['Bio'] = "This user as no bio, encourage them to make one!";
                            } ?>
                            <h2>About</h2>
                            <p id="bio" class="text-center"><?= htmlspecialchars($data['Bio']); ?><br></p>
                            <?php if (isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) { ?>
                                <h2>Admin Actions</h2>
                                <div class="row" style="justify-content: center">
                                    <?php if (!$ban['banned']) { ?>
                                        <script src="<?= htmlspecialchars($dir); ?>admin-scripts/ban.js"></script>
                                        <button onclick="Ban('<?= htmlspecialchars($data['gid']);?>','<?= htmlspecialchars($dir);?>')" class="btn btn-danger">Ban</button>
                                    <?php } else { ?>
                                        <script src="<?= htmlspecialchars($dir); ?>admin-scripts/unban.js"></script>
                                        <button onclick="UnBan('<?= htmlspecialchars($data['gid']);?>','<?= htmlspecialchars($dir);?>')" class="btn btn-danger">Unban</button>
                                    <?php } ?>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/restrictions.js"></script>
                                    <button onclick="EditRestrictions('<?= htmlspecialchars($data['gid']);?>','<?= htmlspecialchars($dir);?>')" class="btn btn-warning">Edit Restrictions</button>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/edit-profile.js"></script>
                                    <button onclick="EditProfile('<?= htmlspecialchars($data['gid']);?>','<?= htmlspecialchars($dir);?>')" class="btn btn-success">Edit Profile</button>
                                    <script src="<?= htmlspecialchars($dir); ?>admin-scripts/extra-info.js"></script>
                                    <button onclick="ExtraInfo('<?= htmlspecialchars($data['gid']);?>','<?= htmlspecialchars($dir);?>')" class="btn btn-info">View Extra Information</button>
                                </div>
                            <?php } ?>
                        </div>
                        <br>
                        <div class="content-box">
                            <h1>Post</h1>
                            <p class="text-center">Nothing yet...</p>
                        </div>
                        <br>
                        <div class="content-box">
                            <h1>Stats</h1>
                            <div class="container">
                                <div class="row display-flex">
                                    <div class="col indexcol">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <img data-src="<?=htmlspecialchars($dir);?>img/resources/icons/png/document.png" alt="Text Post" class="img-fluid lozad" style="max-height:100px;" />
                                                <h1>Text Post</h1>
                                                <h2 style="font-size: 3em">0</h2>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col indexcol">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <img data-src="<?=htmlspecialchars($dir);?>img/resources/icons/png/video-camera.png" alt="Video Post" class="img-fluid lozad" style="max-height:100px;" />
                                                <h1>Video Post</h1>
                                                <h2 style="font-size: 3em">0</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col indexcol">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <?php
                                                    $query3 = SQLWrapper()->prepare("SELECT PostID,Approved FROM ImagePost WHERE gid= :gid AND Private=0");
                                                    $query3->execute([":gid"=>$data['gid']]);
                                                    $phocount = $query3->rowCount();
                                                    $phodata = $query3->fetchAll();
                                                    foreach($phodata as $post){
                                                        $app = json_decode($post['Approved'],true);
                                                        if($app['Status']==0 or $app['Status']==2){
                                                            $phocount = $phocount-1;
                                                        }
                                                    }
                                                ?>
                                                <img data-src="<?=htmlspecialchars($dir);?>img/resources/icons/png/gallery.png" alt="Photo Post" class="img-fluid lozad" style="max-height:100px;" />
                                                <h1>Photo Post</h1>
                                                <h2 style="font-size: 3em"><?=v($phocount);?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row display-flex">
                                    <div class="col indexcol">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <img data-src="<?=htmlspecialchars($dir);?>img/resources/icons/png/calendar.png" alt="Calendar" class="img-fluid lozad" style="max-height:100px;" />
                                                <h1>Account Created:</h1>
                                                <h2 style="font-size: 3em"><?= htmlspecialchars(FormatDate($data['CreationDate'])); ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="content-box">
                            <h1>This User Is Banned</h1>
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
    </script>

</body>

</html>