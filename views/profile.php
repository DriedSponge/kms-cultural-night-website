<?php
$user = SQLWrapper()->prepare("SELECT Name, CreationDate, Picture,Bio,gid,RealName FROM Users WHERE Name = :name");
$user->execute([":name" => $name]);
$data = $user->fetch();

?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title><?= htmlspecialchars($name); ?>'s Profile</title>
        <meta name="description" content="Decription">
    </head>
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
                                <?php if($ban['banned']){ ?>
                                <div class="alert alert-danger" role="alert">
                                    This user is banned! Reason: <?=htmlspecialchars($ban['Reason']);?>
                                </div>
                                <?php } ?>
                                <img class="img-fluid profile-page-img" src="<?= htmlspecialchars($data['Picture']); ?>">
                            </div>
                            <h1 style="margin: 0px"><?= htmlspecialchars($name); ?></h1>
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
                            <h2>About</h2>
                            <p class="text-center"><?= htmlspecialchars($data['Bio']); ?><br></p>
                            <?php if (isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) { ?>
                                <h2>Admin Actions</h2>
                                <div class="row" style="justify-content: center">
                                    <?php if (!$ban['banned']) { ?>
                                        <button class="btn btn-danger">Ban</button>
                                    <?php }else{ ?>
                                        <button class="btn btn-danger">Unban</button>
                                    <?php } ?>
                                    <button class="btn btn-success">Edit Profile</button>
                                    <button class="btn btn-info">View Extra Information</button>
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
                            <p class="text-center">Nothing yet...</p>
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



    <script src="<?= htmlspecialchars($dir); ?>js/toastr.min.js"></script>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="<?= htmlspecialchars($dir); ?>js/argon.js"></script>
    <script src="https://kit.fontawesome.com/0add82e87e.js" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/tippy.js@4"></script>




</body>

</html>