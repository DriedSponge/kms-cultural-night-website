<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,RealName FROM Users WHERE gid = :gid");
$user->execute([":gid" => $_SESSION['gid']]);
$data = $user->fetch();
$restrictions = FetchRestrictions($_SESSION['gid']);
?>
<!DOCTYPE html>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Contact</title>
        <meta name="description" content="Give general feedback, ask questions, or get support with your account!">


    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php
                $ban = IsBanned($_SESSION['gid']);
                if (!$ban['banned']) {
                ?>
                    <div class="content-box">
                        <h1>Contact</h1>
                        <script>
                            $(document).ready(function() {
                                $("#conatact-form").submit(function(e) {
                                    e.preventDefault()
                                    $("#conatact-form").hide();
                                    Loading(true, "#loading")
                                    var c = $("#c").val();
                                    var msg = $("#msg").val();
                                    $.post("<?= v($dir); ?>ajax/contact.php", {
                                            c: c,
                                            msg: msg,
                                            contact: 1
                                        })
                                        .done(function(data) {
                                            Loading(false, "#loading")
                                            if (data.success) {
                                                $("#success-mesage").removeClass("d-none")
                                            } else {
                                                $("#conatact-form").show();
                                                if (data.SysErr) {
                                                    AlertError(data.Msg);
                                                } else {
                                                    if (data.MsgErr) {
                                                        InValidate("#msg", data.MsgErr)
                                                    } else {
                                                        Validate("#msg", data.MsgErr)
                                                    }
                                                    if (data.CErr) {
                                                        InValidate("#c", data.CErr)
                                                    } else {
                                                        Validate("#c", data.CErr)
                                                    }
                                                }

                                            }
                                        })
                                })
                            })
                        </script>
                        <div id="loading"></div>
                        <div id="success-mesage" class="d-none">
                            <div class="modal-body">
                                <div class="alert alert-success text-center" role="alert">
                                    <span><b>Success!</b><br><span id="success_message_text">Your message has been sent!</span></span>
                                </div>
                            </div>
                        </div>
                        <form id="conatact-form">
                            <div class="form-group">
                                <label>Category</label>
                                <select id="c" feedback="#c-f" class="form-control form-control-alternative">
                                    <option value="General Contact">General Contact</option>
                                    <option value="Question">Question</option>
                                    <option value="Site Feedback">Site Feedback</option>
                                    <option value="Bug Report">Bug Report</option>
                                    <option value="Support">Support</option>
                                </select>
                                <div id="c-f"></div>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea feedback="#msg-f" id="msg" maxlength="1500" placeholder="Enter your message here..." rows="8" class="form-control form-control-alterantive paragraph"></textarea>
                                <div id="msg-f"></div>
                            </div>
                            <button type="submit" style="width: 100%" class="btn btn-success">Send Message</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="content-box">
                        <h1>You are banned!</h1>
                        <h2>Reason: <?= v($ban['Reason']); ?></h2>
                        <p class="text-center">To appeal your ban, please visit the <a href="<?= v($dir); ?>ban-appeal/">ban appeals</a> page!</p>
                    </div>

                <?php } ?>
            </div>
            <br>

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