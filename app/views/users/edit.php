<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <a href="<?php echo URLROOT; ?>/users/manage" class="btn btn-primary">
            < Back</a>
                <h2>Edit User</h2>
                <form action="<?php echo URLROOT; ?>/users/edit/<?php echo $data['id']; ?>" method="post">
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
                        <label for="role">Role: <sup>*</sup></label>
                        <select name="role" class="form-control">
                            <option value="farmer" <?php echo ($data['role'] == 'farmer') ? 'selected' : ''; ?>>Farmer
                            </option>
                            <option value="buyer" <?php echo ($data['role'] == 'buyer') ? 'selected' : ''; ?>>Buyer
                            </option>
                            <option value="expert" <?php echo ($data['role'] == 'expert') ? 'selected' : ''; ?>>Expert
                            </option>
                            <option value="equipment_seller" <?php echo ($data['role'] == 'equipment_seller') ? 'selected' : ''; ?>>Equipment Seller
                            </option>
                            <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin
                            </option>
                        </select>
                        <span class="invalid-feedback">
                            <?php echo $data['role_err']; ?>
                        </span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>