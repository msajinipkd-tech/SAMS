<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="card card-body bg-light mb-3">
             <a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light btn-block mb-3"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
             <h4>Account</h4>
             <p><strong>Username:</strong> <?php echo $data['username']; ?></p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-body bg-light mb-5">
            <h2>My Profile</h2>
            <p>Update your profile information</p>
            
            <?php if(!empty($data['success'])): ?>
                <div class="alert alert-success"><?php echo $data['success']; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/expert/profile" method="post">
                <div class="form-group">
                    <label for="full_name">Full Name: <sup>*</sup></label>
                    <input type="text" name="full_name" class="form-control form-control-lg" value="<?php echo $data['full_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address: <sup>*</sup></label>
                    <textarea name="address" class="form-control form-control-lg"><?php echo $data['address']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number: <sup>*</sup></label>
                    <input type="text" name="phone" class="form-control form-control-lg" value="<?php echo $data['phone']; ?>" maxlength="10" required>
                </div>
                
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Update Profile" class="btn btn-success btn-block">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
