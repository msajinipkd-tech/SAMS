<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <h1>Farmer Dashboard</h1>
        <p>Welcome,
            <?php echo $_SESSION['user_username']; ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Crop Management</h5>
                <p class="card-text">Manage crops, plan cycles, and track growth.</p>
                <a href="<?php echo URLROOT; ?>/crop_management" class="btn btn-primary">Manage Crops</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Weather Information</h5>
                <p class="card-text">Check weather forecast for your farm.</p>
                <a href="<?php echo URLROOT; ?>/farmer/weather" class="btn btn-info">View Weather</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Pesticides</h5>
                <p class="card-text">Get recommendations for pesticides.</p>
                <a href="<?php echo URLROOT; ?>/pesticides" class="btn btn-warning">View Pesticides</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Activity Planner</h5>
                <p class="card-text">Schedule and track your farming activities.</p>
                <a href="<?php echo URLROOT; ?>/farmer/activities" class="btn btn-success">Manage Activities</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Sell Products</h5>
                <p class="card-text">List and sell your agricultural products.</p>
                <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">Add Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">My Orders</h5>
                <p class="card-text">Track your product orders.</p>
                <a href="<?php echo URLROOT; ?>/farmer/orders" class="btn btn-secondary">View Orders</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Expert Advice</h5>
                <p class="card-text">Ask for advice from agricultural experts.</p>
                <a href="<?php echo URLROOT; ?>/farmer/expert" class="btn btn-danger">Ask Expert</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Profile Settings</h5>
                <p class="card-text">Update your personal and farm details.</p>
                <a href="<?php echo URLROOT; ?>/farmer/profile" class="btn btn-dark">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>