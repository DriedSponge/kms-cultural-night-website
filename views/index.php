<html>
<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Home</title>
        <meta name="description" content="Due to the COVID-19 virus and the closure of school, we've moved the event online!">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <h1>About</h1>
                    <p class="text-center">Due to the COVID-19 virus and the closure of school, we've moved the event online!</p>
                </div>
                <br>
                <div class="content-box">
                    <h1>How To Post</h1>
                    <ul class="paragraph">
                        <li><a href="/register/">Register for an account</a>, if you already have one you can <a href="/login/">log in</a>.</li>
                        <li>Head to the <a href="/new-post/">create post page</a>.</li>
                        <li>Select the post type you want.</li>
                        <li>Fill out the required forms.</li>
                        <li>Once you have submitted your post, it will be added to the approval queue. If the post meets our <a href="/community-standards/">Community Standards</a>, then it will be approved and will appear on the site!</li>
                        <li>You always have the option to private or delete your post!</li>
                    </ul>
                </div>
                <br>
                <div class="content-box">
                    <h1>Known Bugs And Issues</h1>
                    <ul class="paragraph">
                        <li>Right now you cannot delete your account, I will add this feature later.</li>
                        <li>You cannot yet edit post, I will also add this feature later.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('homelink').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>
     



</body>

</html>