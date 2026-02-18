<?php require APPROOT . '/views/inc/header.php'; ?>
<style>
    .profile-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }
    .profile-sidebar {
        flex: 1;
        min-width: 250px;
    }
    .profile-main {
        flex: 3;
        min-width: 300px;
    }
    .profile-pic-container {
        text-align: center;
    }
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid var(--secondary-color);
    }
    .section-title {
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: var(--primary-color);
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .alert {
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
        color: white;
    }
    .alert-success { background-color: #28a745; }
    .alert-danger { background-color: #dc3545; }
</style>

<div class="container">
    <a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-secondary">&larr; Back to Dashboard</a>
    
    <div class="profile-container">
        <!-- Sidebar: Profile Picture -->
        <div class="profile-sidebar">
            <div class="card profile-pic-container">
                <?php 
                    $profile_pic = isset($data['profile']->profile_picture) && !empty($data['profile']->profile_picture) ? URLROOT . '/' . $data['profile']->profile_picture : 'https://placehold.co/150x150?text=Profile';
                ?>
                <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic">
                <h3><?php echo isset($data['user']->username) ? $data['user']->username : 'User'; ?></h3>
                <p class="text-muted"><?php echo isset($data['profile']->full_name) ? $data['profile']->full_name : ''; ?></p>
                
                <hr>
                <h5>Change Picture</h5>
                <?php if(isset($_GET['upload'])): ?>
                    <?php if($_GET['upload'] == 'success'): ?>
                        <div class="alert alert-success">Uploaded!</div>
                    <?php else: ?>
                        <div class="alert alert-danger">Upload failed.</div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <form action="<?php echo URLROOT; ?>/farmer/update_profile_picture" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="profile_picture" required accept="image/*" style="width: 100%;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Upload</button>
                </form>
            </div>
        </div>

        <!-- Main Content: forms -->
        <div class="profile-main">
            <!-- Edit Details -->
            <div class="card">
                <h2 class="section-title">Edit Personal Details</h2>
                
                <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                    <div class="alert alert-success">Profile updated successfully!</div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/farmer/update_profile" method="post">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo isset($data['profile']->full_name) ? $data['profile']->full_name : ''; ?>">
                    </div>
                    
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex:1;">
                            <label>Farm Size</label>
                            <input type="text" name="farm_size" class="form-control" value="<?php echo isset($data['profile']->farm_size) ? $data['profile']->farm_size : ''; ?>">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Main Crops</label>
                            <input type="text" name="main_crops" class="form-control" value="<?php echo isset($data['profile']->main_crops) ? $data['profile']->main_crops : ''; ?>">
                        </div>
                    </div>

                    <h3 class="section-title" style="margin-top: 20px;">Contact Information</h3>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo isset($data['profile']->phone) ? $data['profile']->phone : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3"><?php echo isset($data['profile']->address) ? $data['profile']->address : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card" style="margin-top: 20px;">
                <h2 class="section-title">Change Password</h2>
                
                <?php if(isset($_GET['password_status']) && $_GET['password_status'] == 'success'): ?>
                    <div class="alert alert-success">Password changed successfully!</div>
                <?php elseif(isset($_GET['password_error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                            if($_GET['password_error'] == 'mismatch') echo 'New passwords do not match.';
                            else echo 'Incorrect current password.';
                        ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/farmer/change_password" method="post">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="background-color: #d32f2f;">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>