<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crop Management</li>
            </ol>
        </nav>
        <h1>Crop Management Dashboard</h1>
        <p>Manage every aspect of your farming operations.</p>
    </div>
</div>

<div class="row">


    <!-- Alerts Section -->
    <?php if (!empty($data['alerts'])): ?>
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="fa fa-bell"></i> Alerts & Notifications</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($data['alerts'] as $alert): ?>
                            <li class="list-group-item list-group-item-<?php echo $alert['type']; ?>">
                                <?php echo $alert['message']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Fields & Land -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-map-marked-alt fa-3x mb-3 text-primary"></i>
                <h5 class="card-title">Fields & Land</h5>
                <p class="card-text">Map and manage your farm land and soil details.</p>
                <a href="<?php echo URLROOT; ?>/crop_management/fields" class="btn btn-outline-primary">Manage
                    Fields</a>
            </div>
        </div>
    </div>

    <!-- Planning -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-calendar-alt fa-3x mb-3 text-success"></i>
                <h5 class="card-title">Crop Planning</h5>
                <p class="card-text">Schedule crop cycles and track planting seasons.</p>
                <a href="<?php echo URLROOT; ?>/crop_management/planning" class="btn btn-outline-success">Plan & Add
                    Cycles</a>
            </div>
        </div>
    </div>

    <!-- Inventory -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-boxes fa-3x mb-3 text-warning"></i>
                <h5 class="card-title">Inventory</h5>
                <p class="card-text">Track seeds and fertilizers stock.</p>
                <a href="<?php echo URLROOT; ?>/crop_management/inventory" class="btn btn-outline-warning">Manage
                    Inventory</a>
            </div>
        </div>
    </div>

    <!-- Tracking -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-clipboard-list fa-3x mb-3 text-info"></i>
                <h5 class="card-title">Growth Tracking</h5>
                <p class="card-text">Log irrigation, nutrients, and pest control.</p>
                <a href="<?php echo URLROOT; ?>/crop_management/tracking" class="btn btn-outline-info">Track Growth</a>
            </div>
        </div>
    </div>

    <!-- Financials -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-chart-line fa-3x mb-3 text-danger"></i>
                <h5 class="card-title">Financials</h5>
                <p class="card-text">Monitor expenses, harvests, and profits.</p>
                <a href="<?php echo URLROOT; ?>/crop_management/financials" class="btn btn-outline-danger">View
                    Reports</a>
            </div>
        </div>
    </div>

    <!-- Weather & Advice -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fa fa-cloud-sun fa-3x mb-3 text-info"></i>
                <h5 class="card-title">Weather & Advice</h5>
                <p class="card-text">Get local weather updates and crop advice.</p>
                <a href="<?php echo URLROOT; ?>/farmer/weather" class="btn btn-outline-info">Check Weather</a>
            </div>
        </div>
    </div>
</div>

<!-- Crop Details List -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Managed Crops</h4>
                <a href="<?php echo URLROOT; ?>/crops/add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add
                    New Crop</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Variety</th>
                                <th>Season</th>
                                <th>Duration</th>
                                <th>Soil Type</th>
                                <th>Water Req</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['crops'])): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No crops defined. Add one to start
                                        planning.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['crops'] as $crop): ?>
                                    <tr>
                                        <td><?php echo $crop->name; ?></td>
                                        <td><?php echo $crop->type; ?></td>
                                        <td><?php echo $crop->variety; ?></td>
                                        <td><?php echo $crop->season; ?></td>
                                        <td><?php echo $crop->duration; ?> days</td>
                                        <td><?php echo $crop->soil_type; ?></td>
                                        <td><?php echo $crop->water_requirement; ?></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/crop_management/planning"
                                                class="btn btn-sm btn-outline-success">Plan</a>
                                            <a href="<?php echo URLROOT; ?>/crops/edit/<?php echo $crop->id; ?>"
                                                class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>