<!--The template for login form-->
<div class="alert alert-warning text-center" role="alert">
    All contents for internal use only <br><b>Please login to continue</b>
    <br><p>Please your Monash Username & Password to login</p>
</div>
<form class="navbar-form" role="form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group">
        <input type="text" placeholder="User Name" class="form-control" name="uname">
    </div>
    <div class="form-group">
        <input type="password" placeholder="Password" class="form-control" name="upassword">
    </div>
    <button type="submit" class="btn btn-success">Sign in</button>
</form>
