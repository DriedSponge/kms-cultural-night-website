<html>
<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Photo Post</title>
        <meta name="description" content="View all of the photos posted to the site!">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <div class="text-center">
                        <img style="max-height: 200px" data-src="<?=htmlspecialchars($dir);?>img/resources/icons/png/gallery.png" alt="Photo Post" class="img-fluid lozad">
                    </div>
                    <h1>Photo Post</h1>
                    
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
                                $(document).ready(function(){
                                    Load("#photo-post");                                    
                                })
                            </script>
                            <tbody id="photo-post" url="<?=v($dir);?>ajax/manage-photo-post.php">
                                
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('photoslink').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>
     



</body>

</html>