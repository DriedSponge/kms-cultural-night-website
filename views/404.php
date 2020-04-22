<html>
<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - 404</title>
        <meta name="description" content="Page Not Found">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <h1>404 - Page Not Found</h1>
                    <p class="text-center">Due to the COVID-19 virus and the closure of school, we've moved the event online! Here you can post... To</p>
                </div>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>

    <script>
        navitem = document.getElementById('homelink').classList.add('active')
        const observer = lozad();
        observer.observe();
    </script>
     



</body>

</html>