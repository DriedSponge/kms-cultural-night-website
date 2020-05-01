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
                    <h1>Approval Queue</h1>
                    <p class="text-center">Please be sure to review each post before approving it.</p>
                    <div id="#photo-post-queue">
                        <h2>Photo Post Queue</h2>
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Culture/Region</th>
                                        <th>Date Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <script>
                                    $(document).ready(function() {
                                        Load("#photo-post");
                                    })
                                </script>
                                <tbody id="photo-post" url="<?= v($dir); ?>ajax/manage-photo-post.php">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div id="#video-post-queue">
                        <h2>Video Post Queue</h2>
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Culture/Region</th>
                                        <th>Date Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <script>
                                    $(document).ready(function() {
                                        Load("#video-post");
                                    })
                                </script>
                                <tbody id="video-post" url="<?= v($dir); ?>ajax/manage-video-post.php">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="#video-post-queue">
                        <h2>Text Post Queue</h2>
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Culture/Region</th>
                                        <th>Date Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <script>
                                    $(document).ready(function() {
                                        Load("#text-post");
                                    })
                                </script>
                                <tbody id="text-post" url="<?= v($dir); ?>ajax/manage-text-post.php">

                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    <div class="container">
                    </div>
                    <div class="container">

                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('postlink').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>