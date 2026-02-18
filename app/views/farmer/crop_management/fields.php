<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/crop_management">Crop Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fields</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1>Manage Fields</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFieldModal">
                <i class="fa fa-plus"></i> Add Field
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (empty($data['fields'])): ?>
            <div class="alert alert-info">No fields added yet. Add your first field to get started.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover bg-white shadow-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Size (Acres)</th>
                            <th>Soil Type</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['fields'] as $field): ?>
                            <tr>
                                <td>
                                    <?php echo $field->name; ?>
                                </td>
                                <td>
                                    <?php echo $field->size; ?>
                                </td>
                                <td>
                                    <?php echo $field->soil_type; ?>
                                </td>
                                <td>
                                    <?php echo $field->location; ?>
                                </td>
                                <td>
                                    <!-- Edit/Delete implementation skipped for brevity, logical placeholder -->
                                    <button class="btn btn-sm btn-secondary" disabled>Edit</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Field Modal -->
<div class="modal fade" id="addFieldModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/crop_management/add_field" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Field Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Size (Acres/Hectares)</label>
                        <input type="number" step="0.01" name="size" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Soil Type</label>
                        <input type="text" name="soil_type" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Location/Description</label>
                        <textarea name="location" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Field</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>