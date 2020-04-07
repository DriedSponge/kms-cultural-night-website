
<html>

<body>

    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="<?=htmlspecialchars($dir);?>css/argon.css">
        <link rel="stylesheet" href="<?=htmlspecialchars($dir);?>css/styles.css">
        <link rel="stylesheet" href="<?=htmlspecialchars($dir);?>css/toastr.min.css" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title>CHUNK LOAD - Manage</title>
        <script src="https://driedsponge.net/functions.js"></script>
    </head>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container">
                
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>


    
    <script src="<?= htmlspecialchars($dir); ?>js/toastr.min.js"></script>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/argon.js"></script>
    <script src="https://kit.fontawesome.com/0add82e87e.js" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/tippy.js@4"></script>


    <script>
       function CopyURL() {
         var copyText = "test";
           navigator.clipboard.writeText(copyText);
         toastr["success"]("The URL has been copied to your clipboard!", "Congratulations!")
     }
    </script>

</body>

</html>