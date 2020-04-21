<?php
if (isset($_POST['restrict'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $gid = $_POST['gid'];
                $user = SQLWrapper()->prepare("SELECT Name,Restrictions FROM Users WHERE gid = :gid");
                $user->execute([":gid" => $gid]);
                $data = $user->fetch();
                $restrictions = json_decode($data['Restrictions'], true);
                if ($data == null) {
                    AlertError("User does not exist!");
                } else {
?>
                    <script>
                        $("#view-modal").modal("show");
                        observer.observe();

                        var unamedef = <?php echo $restrictions['UserNameChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("uname-r").checked = unamedef;

                        var biodef = <?php echo $restrictions['BioChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("bio-r").checked = biodef;

                        var picdef = <?php echo $restrictions['PictureChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("pic-r").checked = picdef;

                        $(document).ready(function() {
                            if (!IsBlurred) {
                                $("#blur-ammount").hide()
                            }
                        })
                        $("#blurcheck").click(function() {
                            if ($("#blurcheck").prop("checked")) {
                                $("#blur-ammount").show()
                            } else {
                                $("#blur-ammount").hide()
                            }
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="view-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Restrictions</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <form>
                                    <div class="modal-body">
                                        <h1>Modify what <?= htmlspecialchars($data['Name']); ?> can/can't do</h1>
                                        <br>
                                        <div class="form-group text-center">
                                            <label>Restrict User Name Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input id="uname-r" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    
                                        <div class="form-group text-center">
                                            <label>Restrict Bio Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input type="checkbox">
                                                <span id="bio-r" class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>

                                        <div class="form-group text-center">
                                            <label>Restrict Profile Image Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input id="pic-r" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="submit" class="btn btn-success" data-dismiss="modal">Apply Restrictions</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<?php
                }
            } else {
                AlertError("Unauthorized");
            }
        } else {
            AlertError("Session Expired");
        }
    } else {
        AlertError("Invalid Post Data");
    }
}
