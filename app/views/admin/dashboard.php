<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin panel.</p>

        <div class="card-grid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
            <div class="card">
                <h3>Users</h3>
                <p>Manage application users.</p>
                <a href="<?php echo URLROOT; ?>/users/manage" class="btn btn-primary">Manage Users</a>
            </div>
            <div class="card">
                <h3>Crops</h3>
                <p>Manage crops information.</p>
                <a href="<?php echo URLROOT; ?>/crops" class="btn btn-primary">Manage Crops</a>
            </div>
            <div class="card">
                <h3>Pesticides</h3>
                <p>Manage pesticides information.</p>
                <a href="<?php echo URLROOT; ?>/pesticides" class="btn btn-primary">Manage Pesticides</a>
            </div>
            <div class="card">
                <h3>Products</h3>
                <p>Manage products inventory.</p>
                <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">Manage Products</a>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>