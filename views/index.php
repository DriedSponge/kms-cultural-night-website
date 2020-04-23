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
            <h1 class="page-title">Welcome!</h1>
            <div class="container-fluid">
                <div class="content-box">
                    <h1>About</h1>
                    <p class="text-center">Due to the COVID-19 virus and the closure of school, we've moved the event online!</p>
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