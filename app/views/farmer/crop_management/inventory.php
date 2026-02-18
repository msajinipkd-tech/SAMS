<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/crop_management">Crop Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory</li>
            </ol>
        </nav>
        <h1>Inventory Management</h1>
    </div>
</div>

<div class="row">
    <!-- Seeds Section -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-success">Seeds Stock</h5>
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addSeedModal">
                    <i class="fa fa-plus"></i> Add Seeds
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Variety</th>
                            <th>Qty</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['seeds'])): ?>
                            <tr>
                                <td colspan="4" class="text-center p-3">No seeds in stock.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['seeds'] as $seed): ?>
                                <tr>
                                    <td>
                                        <?php echo $seed->name; ?>
                                    </td>
                                    <td>
                                        <?php echo $seed->variety; ?>
                                    </td>
                                    <td>
                                        <?php echo $seed->quantity . ' ' . $seed->unit; ?>
                                    </td>
                                    <td>
                                        <?php echo $seed->expiry_date; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Fertilizers Section -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-warning">Fertilizers Stock</h5>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#addFertilizerModal">
                    <i class="fa fa-plus"></i> Add Fertilizer
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['fertilizers'])): ?>
                            <tr>
                                <td colspan="3" class="text-center p-3">No fertilizers in stock.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['fertilizers'] as $fert): ?>
                                <tr>
                                    <td>
                                        <?php echo $fert->name; ?>
                                    </td>
                                    <td>
                                        <?php echo $fert->type; ?>
                                    </td>
                                    <td>
                                        <?php echo $fert->quantity . ' ' . $fert->unit; ?>
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

<!-- Add Seed Modal -->
<div class="modal fade" id="addSeedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/crop_management/add_seed" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Seeds</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Seed Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Variety</label>
                        <input type="text" name="variety" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit" class="form-control">
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="packets">packets</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Seeds</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Fertilizer Modal -->
<div class="modal fade" id="addFertilizerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/crop_management/add_fertilizer" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Fertilizer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="Organic">Organic</option>
                            <option value="Chemical">Chemical</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit" class="form-control">
                                    <option value="kg">kg</option>
                                    <option value="liters">liters</option>
                                    <option value="bags">bags</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Add Fertilizer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>