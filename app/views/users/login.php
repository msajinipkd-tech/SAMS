<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="login-container">
    <div class="card">
        <h2>Login</h2>
        <p>Please fill in your credentials to log in.</p>
        <form action="<?php echo URLROOT; ?>/users/login" method="post">
            <div class="form-group">
                <label for="username">Username: <sup>*</sup></label>
                <input type="text" name="username"
                    class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $data['username']; ?>">
                <span class="invalid-feedback">
                    <?php echo $data['username_err']; ?>
                </span>
            </div>
            <div class="form-group">
                <label for="password">Password: <sup>*</sup></label>
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $data['password']; ?>">
                <span class="invalid-feedback">
                    <?php echo $data['password_err']; ?>
                </span>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" class="btn btn-primary btn-block">
            </div>
        </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>