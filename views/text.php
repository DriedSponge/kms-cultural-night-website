<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Text Post</title>
        <meta name="description" content="View all of the text post posted to the site!">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <div class="text-center">
                        <img style="max-height: 200px" data-src="<?= htmlspecialchars($dir); ?>img/resources/icons/png/document.png" alt="Photo Post" class="img-fluid lozad">
                    </div>
                    <h1>Text Post</h1>
                    <label>Search Text Post</label>
                    <div class="input-group input-group-alternative mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-search"></i></div>
                        </div>
                        <input id="search" placeholder="Search Query" class="form-control  form-control-alternative">
                        <script>
                            $(document).ready(function() {
                                $("#search").on("keyup", function() {
                                    var value = $(this).val().toLowerCase();
                                    $("table .search").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>
                    </div>
                    <div  class="table-responsive">
                        <table  id="photopost" url="<?= v($dir); ?>ajax/load-text-post.php" pid="tp" class="table table-hover text-center">
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
                                    AjaxPagination("tp", 1, true, "Date", "DESC");
                                })
                            </script>
                            <div id="tp-loading"></div>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul id="tp-blist" class="pagination justify-content-center">
                            <?php
                            $records_per_page = 75;
                            $sql = SQLWrapper()->prepare("SELECT PostID FROM TextPost WHERE Private = 0");
                            $sql->execute();
                            $results = $sql->rowCount();
                            $number_of_pages = ceil($results / $records_per_page);

                            for ($page = 1; $page <= $number_of_pages; $page++) {
                            ?>
                                <li id="tp-b-<?= v($page); ?>" class="page-item"><button class="page-link" onclick="AjaxPagination('tp',<?= v($page); ?>,true)"><?= v($page); ?></button></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('textlink').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>