

<!--another modal-->
<div id="demo-modal" class="modal fade bd-example-modal-lg">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"> <span>x</span> </button>
            </div>
            <div class="modal-body ">
                <h3 id = "mylbl">Sign In</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "needs-validation">
                    <div class="form-group">
                        <input type="hidden" name="hiddencontainer" id = "hiddencontainer" value="Doctor" >
                        <label>Username</label>
                        <input id="uname" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                        <span class="invalid-feedback"><?php echo $username_err; ?></span> </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span> </div>
                    <div class="form-group text-right">
                        <input type="submit" class="btn btn-primary" name="submitlogin" value="Login">
                    </div>
                    <div class="form-group text-centre">
                        <p id="mycreate">Don't have an account? <a href='#' data-toggle='modal' data-target='#contact-modal'>Sign up now</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="contact-modal" class="modal fade bd-example-modal-lg">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"> <span>x</span> </button>
            </div>
            <div class="modal-body ">
				<h5>Message Systems Administrator</h5>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "needs-validation">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input id="fullname" type="text" name="fullname" class="form-control" placeholder="Enter name here" required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Phone No.</label>
                        <input id="phnnum" type="text" name="phnnum" class="form-control" placeholder="0712345678" required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input id="cemail" type="email" name="cemail" class="form-control" placeholder="email@someone.com"required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="cmessage" id="cmessage" class="form-control"  placeholder="Enter your message here"required></textarea>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group text-right">
                        <input type="submit" class="btn btn-success" name="submitmessage" value="Send Message">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

