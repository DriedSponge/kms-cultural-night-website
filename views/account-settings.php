<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,RealName FROM Users WHERE gid = :gid");
$user->execute([":gid" => $_SESSION['gid']]);
$data = $user->fetch();
$restrictions = FetchRestrictions($_SESSION['gid']);
?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Home</title>
        <meta name="description" content="Decription">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <h1>Account Settings</h1>
                    <br>
                    <p class="text-center">Here you can edit your account settings. Your email and profile picture are both synced with your google account.</p>
                    <p class="text-center">Please note we do have the ablilty to restrict actions on your account and if neccessary, ban you from the site. Please make sure the content you enter is appropiate for everyone.</p>
                    <?php if($restrictions['BioChange']){ ?>
                    <p class="text-center text-danger">Your account is no longer eligible for bio changes!</p>
                    <?php } ?>
                    <?php if($restrictions['UserNameChange']){ ?>
                    <p class="text-center text-danger">Your account is no longer eligible for username changes!</p>
                    <?php } 
                    if($restrictions['PictureChange']){ ?>
                    <p class="text-center text-danger">Your account is no longer eligible for profile picture changes!</p>
                    <?php } ?>
                    
                    
                    <br>
                    <h2>Primary Settings</h2>
                    <div style="max-width: 650px" class="container">
                        <script>
                            $(document).ready(function() {
                                document.getElementById("uname").disabled = <?php echo $restrictions['UserNameChange'] ? 'true' :'false'?>;
                                document.getElementById("bio").disabled = <?php echo $restrictions['BioChange'] ? 'true' :'false'?>;
                                <?php 
                                    if($restrictions['UserNameChange'] && $restrictions['BioChange']){
                                        ?>
                                        document.getElementById("submit").disabled = true;
                                        <?php
                                    }
                                ?>
                                $("#account-settings").submit(function(event) {
                                    event.preventDefault();
                                    var uname = $("#uname").val()
                                    var bio = $("#bio").val()
                                    Loading(true, "#loading")
                                    $("#account-settings").hide();
                                    $.post('<?= htmlspecialchars($dir); ?>ajax/save-settings.php',{
                                        uname: uname,
                                        bio: bio,
                                        save: 1
                                    })
                                    .done(function(data){
                                        Loading(false, "#loading")
                                        $("#account-settings").show();
                                        if(data.success){
                                            AlertSuccess(data.Msg);
                                            Validate("#uname")
                                            Validate("#bio")

                                        }else{
                                            if(data.SysErr){
                                                AlertError(data.Msg);
                                            }else{
                                                if(data.unameERR){
                                                    InValidate("#uname",data.unameERR)
                                                }else{
                                                    Validate("#uname")
                                                }
                                                if(data.bioERR){
                                                    InValidate("#bio",data.bioERR)
                                                }else{
                                                    Validate("#bio")
                                                }
                                            }
                                        }
                                    })
                                })
                            })
                        </script>
                        <div id="loading"></div>
                        <form id="account-settings">
                            <div class="form-group">
                                <label>Change your username</label>
                               
                                    <input id="uname" feedback="#uname-f" maxlength="30" minlength="3" class="form-control form-control-alternative" value="<?= htmlspecialchars($data['Name']); ?>" placeholder="Please enter a username" type="text">
                                
                                <div id="uname-f"></div>

                            </div>
                            <div class="form-group">
                                <label>Edit your bio or leave it blank</label>
                                <textarea id="bio" feedback="#bio-f" maxlength="600" rows="3" placeholder="Tell people about yourself!" class="form-control form-control-alternative"><?= htmlspecialchars($data['Bio']); ?></textarea>
                                <div id="bio-f"></div>
                            </div>

                            <button id="submit" type="submit" class="btn btn-success" style="width: 100%">Save Changes</button>
                        </form>
                    </div>
                    <br>
                    <h2>Other Settings</h2>
                    <div class="row justify-content-center">
                        <button title="Big Red Button" class="btn btn-danger">Delete Account</button>
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
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>