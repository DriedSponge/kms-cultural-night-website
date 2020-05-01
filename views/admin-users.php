<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Admin Panel</title>
        <meta name="description" content="Due to the COVID-19 virus and the closure of school, we've moved the event online!">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php include("views/includes/managenav.php"); ?>
                <div class="content-box">
                    <script src="<?= v($dir); ?>admin-scripts/denypost.js"></script>
                    <script src="<?= v($dir); ?>admin-scripts/approvepost.js"></script>
                    <h1>Banned Users</h1>
                    <p class="text-center">These are the users that are currently banned on the site.</p>
                    <div id="#photo-post-queue">
                        <div class="table-responsive">
                            <table id="bannedusers" url="<?= v($dir); ?>ajax/manage-banned-users.php" pid="ban" class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Admin</th>
                                        <th>Reason</th>
                                        <th>Ban Date</th>
                                    </tr>
                                </thead>
                                <script>
                                    $(document).ready(function() {
                                        AjaxPagination("ban", 1, true, "Date", "DESC");
                                    })
                                </script>
                                <tbody >

                                </tbody>
                            </table>
                            <nav>
                                <ul id="ban-blist" class="pagination justify-content-center">
                                    <?php
                                    $records_per_page = 75;
                                    $sql = SQLWrapper()->prepare("SELECT gid FROM Bans");
                                    $sql->execute();
                                    $results = $sql->rowCount();
                                    $number_of_pages = ceil($results / $records_per_page);

                                    for ($page = 1; $page <= $number_of_pages; $page++) {
                                    ?>
                                        <li id="ban-b-<?= v($page); ?>" class="page-item"><button class="page-link" onclick="AjaxPagination('ban',<?= v($page); ?>,true)"><?= v($page); ?></button></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                    </div>
                </div>
                <br>
            </div>
        </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('userstab').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>