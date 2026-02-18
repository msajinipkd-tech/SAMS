<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/crop_management">Crop Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Planning</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1>Crop Planning & Scheduling</h1>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCycleModal">
                <i class="fa fa-plus"></i> Schedule New Cycle
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (empty($data['cycles'])): ?>
            <div class="alert alert-info">No crop cycles scheduled. Plan your first crop season now.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped bg-white shadow-sm">
                    <thead>
                        <tr>
                            <th>Crop</th>
                            <th>Field</th>
                            <th>Season</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['cycles'] as $cycle): ?>
                            <tr>
                                <td>
                                    <?php echo $cycle->crop_name; ?>
                                </td>
                                <td>
                                    <?php echo $cycle->field_name; ?>
                                </td>
                                <td>
                                    <?php echo $cycle->season; ?>
                                </td>
                                <td>
                                    <?php echo $cycle->start_date; ?>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-<?php echo $cycle->status == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($cycle->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/crop_management/tracking/<?php echo $cycle->id; ?>"
                                        class="btn btn-sm btn-info">Track</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Cycle Modal -->
<div class="modal fade" id="addCycleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/crop_management/add_cycle" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Crop Cycle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Crop</label>
                        <select name="crop_id" id="crop_selector" class="form-control" required>
                            <option value="" data-duration="0" data-soil="">Select Crop</option>
                            <?php foreach ($data['crops'] as $crop): ?>
                                <option value="<?php echo $crop->id; ?>" data-duration="<?php echo $crop->duration; ?>"
                                    data-soil="<?php echo $crop->soil_type; ?>">
                                    <?php echo $crop->name; ?> (<?php echo $crop->variety ? $crop->variety : 'Gen'; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Field</label>
                        <select name="field_id" id="field_selector" class="form-control" required>
                            <option value="" data-soil="">Select Field</option>
                            <?php foreach ($data['fields'] as $field): ?>
                                <option value="<?php echo $field->id; ?>" data-soil="<?php echo $field->soil_type; ?>">
                                    <?php echo $field->name; ?> (<?php echo $field->soil_type; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="alert alert-warning" id="soil_alert" style="display:none;">
                        <strong>Warning:</strong> The selected field's soil type may not match the crop's requirement.
                        <br>
                        <span id="soil_mismatch_msg"></span>
                    </div>

                    <div class="form-group">
                        <label>Season</label>
                        <select name="season" class="form-control">
                            <option value="Kharif">Kharif</option>
                            <option value="Rabi">Rabi</option>
                            <option value="Zaid">Zaid</option>
                            <option value="All Year">All Year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Sowing Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Expected Harvest Date</label>
                        <input type="date" name="expected_harvest_date" id="harvest_date" class="form-control">
                        <small class="form-text text-muted">Auto-calculated based on crop duration.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cropSelect = document.getElementById('crop_selector');
        const fieldSelect = document.getElementById('field_selector');
        const startDateInput = document.getElementById('start_date');
        const harvestDateInput = document.getElementById('harvest_date');
        const soilAlert = document.getElementById('soil_alert');
        const soilMsg = document.getElementById('soil_mismatch_msg');

        function updateHarvestDate() {
            const startDate = new Date(startDateInput.value);
            const selectedOption = cropSelect.options[cropSelect.selectedIndex];
            const duration = parseInt(selectedOption.getAttribute('data-duration')) || 0;

            if (startDateInput.value && duration > 0) {
                const harvestDate = new Date(startDate);
                harvestDate.setDate(harvestDate.getDate() + duration);

                // Format to YYYY-MM-DD
                const yyyy = harvestDate.getFullYear();
                const mm = String(harvestDate.getMonth() + 1).padStart(2, '0');
                const dd = String(harvestDate.getDate()).padStart(2, '0');

                harvestDateInput.value = `${yyyy}-${mm}-${dd}`;
            }
        }

        function checkSoilCompatibility() {
            const cropOption = cropSelect.options[cropSelect.selectedIndex];
            const fieldOption = fieldSelect.options[fieldSelect.selectedIndex];

            const cropSoil = cropOption.getAttribute('data-soil');
            const fieldSoil = fieldOption.getAttribute('data-soil');

            if (cropSoil && fieldSoil && cropSoil !== "" && fieldSoil !== "") {
                // Simple case-insensitive inclusion check or exact match
                if (!fieldSoil.toLowerCase().includes(cropSoil.toLowerCase()) && !cropSoil.toLowerCase().includes(fieldSoil.toLowerCase())) {
                    soilMsg.textContent = `Crop requires '${cropSoil}', but Field is '${fieldSoil}'.`;
                    soilAlert.style.display = 'block';
                } else {
                    soilAlert.style.display = 'none';
                }
            } else {
                soilAlert.style.display = 'none';
            }
        }

        cropSelect.addEventListener('change', function() {
            updateHarvestDate();
            checkSoilCompatibility();
        });
        startDateInput.addEventListener('change', updateHarvestDate);
        fieldSelect.addEventListener('change', checkSoilCompatibility);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>