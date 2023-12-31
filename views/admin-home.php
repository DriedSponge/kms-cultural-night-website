<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Admin Panel Home</title>
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php include("views/includes/managenav.php"); ?>
                <div class="content-box">
                    <h1>Welcome to the Admin Panel.</h1>
                    <p class="text-center">Here you can moderate post and users.</p>
                </div>
                <br>
                <div class="content-box">
                    <h1>Recent Events</h1>
                    <h2>Coming soon...</h2>
                </div>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        navitem = document.getElementById('hometab').classList.add('active')
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>