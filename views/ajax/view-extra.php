<?php
if (isset($_POST['view'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $gid = $_POST['gid'];
                $user = SQLWrapper()->prepare("SELECT Name,Picture,Email,Bio,RealName,UNIX_TIMESTAMP(CreationDate) AS CreationDate FROM Users WHERE gid = :gid");
                $user->execute([":gid" => $gid]);
                $data = $user->fetch();
                if ($data == null) {
                    AlertError("User does not exist!");
                } else {
?>
                    <script>
                        $("#view-modal").modal("show");
                        observer.observe();
                    </script>
                    <div class="modal" tabindex="-1" id="view-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= htmlspecialchars($data['Name']); ?>'s Info</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>

                                </div>
                                <form>
                                    <div class="modal-body">
                                        <div class="alert alert-danger text-center" role="alert">
                                            <b>Warning:</b><br>Viewing sensitive info
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input title="Email" value="<?= htmlspecialchars($data['Email']); ?>" class="form-control  form-control-alternative" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Account ID</label>
                                            <input title="ID" value="<?= htmlspecialchars($gid); ?>" class="form-control  form-control-alternative" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input title="Name" value="<?= htmlspecialchars($data['RealName']); ?>" class="form-control  form-control-alternative" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Creation Date</label>
                                            <input title="Creation Date" value="<?= htmlspecialchars(FormatDate($data['CreationDate'])); ?>" class="form-control  form-control-alternative" disabled>
                                        </div>
                                        
                                        <div class="text-center">
                                            <img style="max-height: 150px;" class="img-fluid lozad" data-src="<?= htmlspecialchars($data['Picture']); ?>">
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
