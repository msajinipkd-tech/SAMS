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
                <p class="card-text">View and manage crop information.</p>
                <a href="<?php echo URLROOT; ?>/crops" class="btn btn-primary">Go to Crops</a>
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
                <h5 class="card-title">Buy Products</h5>
                <p class="card-text">Browse and purchase agricultural products.</p>
                <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">Shop Now</a>
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
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Buyer Feedback</h5>
                <p class="card-text">View feedback and suggestions from buyers.</p>
                <a href="<?php echo URLROOT; ?>/farmer/feedback" class="btn btn-info" style="background-color: #6C5CE7; border-color: #6C5CE7;">View Feedback</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Product Reviews</h5>
                <p class="card-text">See what buyers are saying about your products.</p>
                <a href="<?php echo URLROOT; ?>/farmer/reviews" class="btn btn-warning" style="background-color: #fdcb6e; border-color: #fdcb6e; color: #2d3436;">View Reviews</a>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>