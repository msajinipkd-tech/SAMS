<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/farmer/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/crop_management">Crop Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Growth Tracking</li>
            </ol>
        </nav>
        <h1>Growth Tracking</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Select Active Crop Cycle</h5>
                <form action="<?php echo URLROOT; ?>/crop_management/tracking/" method="GET" id="cycleForm">
                    <!-- Note: Implementation uses JS to redirect or simple links below -->
                    <div class="form-group">
                        <select class="form-control"
                            onchange="window.location.href='<?php echo URLROOT; ?>/crop_management/tracking/' + this.value">
                            <option value="">-- Select a Cycle to Track --</option>
                            <?php foreach ($data['cycles'] as $cycle): ?>
                                <option value="<?php echo $cycle->id; ?>" <?php echo (isset($data['cycle_id']) && $data['cycle_id'] == $cycle->id) ? 'selected' : ''; ?>>
                                    <?php echo $cycle->crop_name . ' (' . $cycle->field_name . ') - ' . $cycle->season; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($data['selected_cycle'])): ?>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h4 class="card-title text-info mb-3">Cultivation Summary</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted">Crop Details</h6>
                            <p class="mb-1"><strong>Crop:</strong> <?php echo $data['selected_cycle']->crop_name; ?></p>
                            <p class="mb-1"><strong>Variety:</strong> <?php echo $data['selected_cycle']->variety; ?></p>
                            <p class="mb-1"><strong>Field:</strong> <?php echo $data['selected_cycle']->field_name; ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Schedule</h6>
                            <p class="mb-1"><strong>Start Date:</strong>
                                <?php echo date('d M Y', strtotime($data['selected_cycle']->start_date)); ?></p>
                            <p class="mb-1"><strong>Status:</strong>
                                <span
                                    class="badge badge-<?php echo ($data['selected_cycle']->status == 'active') ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($data['selected_cycle']->status); ?>
                                </span>
                            </p>
                            <p class="mb-1"><strong>Expected Harvest:</strong>
                                <?php echo date('d M Y', strtotime($data['selected_cycle']->expected_harvest_date)); ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Requirements & Guide</h6>
                            <p class="mb-1"><strong>Duration:</strong>
                                ~<?php echo $data['selected_cycle']->recommended_duration; ?> days</p>
                            <p class="mb-1"><strong>Soil Req:</strong>
                                <?php echo $data['selected_cycle']->required_soil_type; ?></p>
                            <p class="mb-1"><strong>Water Req:</strong>
                                <?php echo $data['selected_cycle']->recommended_water; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="trackingTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="irrigation-tab" data-toggle="tab" href="#irrigation"
                                role="tab">Irrigation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nutrients-tab" data-toggle="tab" href="#nutrients"
                                role="tab">Nutrients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pests-tab" data-toggle="tab" href="#pests" role="tab">Pests &
                                Diseases</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="harvest-tab" data-toggle="tab" href="#harvest" role="tab">Harvest</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">

                        <!-- IRRIGATION TAB -->
                        <div class="tab-pane fade show active" id="irrigation" role="tabpanel">
                            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addIrrigationModal">Log
                                Irrigation</button>
                            <?php if (empty($data['irrigation'])): ?>
                                <p class="text-muted">No irrigation logs found.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($data['irrigation'] as $log): ?>
                                        <li class="list-group-item">
                                            <strong>
                                                <?php echo $log->date; ?>
                                            </strong>:
                                            <?php echo $log->method; ?> (
                                            <?php echo $log->duration; ?> mins,
                                            <?php echo $log->water_volume; ?> L)
                                            <br><small>
                                                <?php echo $log->notes; ?>
                                            </small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <!-- NUTRIENTS TAB -->
                        <div class="tab-pane fade" id="nutrients" role="tabpanel">
                            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNutrientModal">Log
                                Nutrient Application</button>
                            <?php if (empty($data['nutrients'])): ?>
                                <p class="text-muted">No nutrient logs found.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($data['nutrients'] as $log): ?>
                                        <li class="list-group-item">
                                            <strong>
                                                <?php echo $log->date; ?>
                                            </strong>:
                                            <?php echo $log->fertilizer_name; ?> -
                                            <?php echo $log->quantity_used; ?>
                                            <br><small>
                                                <?php echo $log->notes; ?>
                                            </small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <!-- PESTS TAB -->
                        <div class="tab-pane fade" id="pests" role="tabpanel">
                            <button class="btn btn-danger mb-3" data-toggle="modal" data-target="#addPestModal">Report
                                Pest/Disease</button>
                            <?php if (empty($data['pests'])): ?>
                                <p class="text-muted">No pest reports found.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($data['pests'] as $report): ?>
                                        <li class="list-group-item">
                                            <span
                                                class="badge badge-<?php echo $report->severity == 'high' ? 'danger' : 'warning'; ?> float-right">
                                                <?php echo ucfirst($report->severity); ?>
                                            </span>
                                            <strong>
                                                <?php echo $report->observation_date; ?>
                                            </strong>:
                                            <?php echo $report->pest_name; ?>
                                            <br><small>
                                                <?php echo $report->notes; ?>
                                            </small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <!-- HARVEST TAB -->
                        <div class="tab-pane fade" id="harvest" role="tabpanel">
                            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addHarvestModal">Record
                                Harvest</button>
                            <div class="alert alert-info">Recording a harvest will update your financial records.</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Irrigation Modal -->
    <div class="modal fade" id="addIrrigationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo URLROOT; ?>/crop_management/log_irrigation" method="POST">
                    <input type="hidden" name="cycle_id" value="<?php echo $data['cycle_id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Log Irrigation</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($data['selected_cycle']->crop_water)): ?>
                            <div class="alert alert-info">
                                <small><strong>Guide:</strong> This crop requires:
                                    <?php echo $data['selected_cycle']->crop_water; ?></small>
                            </div>
                        <?php endif; ?>
                        <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control"
                                required></div>
                        <div class="form-group"><label>Duration (mins)</label><input type="number" name="duration"
                                class="form-control"></div>
                        <div class="form-group"><label>Method</label><input type="text" name="method" class="form-control"
                                placeholder="e.g. Drip, Flood"></div>
                        <div class="form-group"><label>Volume (Liters)</label><input type="number" step="0.01"
                                name="water_volume" class="form-control"></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Nutrient Modal -->
    <div class="modal fade" id="addNutrientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo URLROOT; ?>/crop_management/log_nutrient" method="POST">
                    <input type="hidden" name="cycle_id" value="<?php echo $data['cycle_id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Log Nutrient</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Fertilizer</label>
                            <select name="fertilizer_id" class="form-control">
                                <?php foreach ($data['fertilizers'] as $fert): ?>
                                    <option value="<?php echo $fert->id; ?>">
                                        <?php echo $fert->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control"
                                required></div>
                        <div class="form-group"><label>Quantity Used</label><input type="number" step="0.01"
                                name="quantity_used" class="form-control" required></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Pest Modal -->
    <div class="modal fade" id="addPestModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo URLROOT; ?>/crop_management/log_pest" method="POST">
                    <input type="hidden" name="cycle_id" value="<?php echo $data['cycle_id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Report Pest</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Pest Name</label><input type="text" name="pest_name"
                                class="form-control" required></div>
                        <div class="form-group">
                            <label>Severity</label>
                            <select name="severity" class="form-control">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Date</label><input type="date" name="observation_date"
                                class="form-control" required></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-danger">Report</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Harvest Modal -->
    <div class="modal fade" id="addHarvestModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo URLROOT; ?>/crop_management/add_harvest" method="POST">
                    <input type="hidden" name="cycle_id" value="<?php echo $data['cycle_id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Harvest</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control"
                                required></div>
                        <div class="form-group"><label>Quantity</label><input type="number" step="0.01" name="quantity"
                                class="form-control" required></div>
                        <div class="form-group">
                            <label>Unit</label>
                            <select name="unit" class="form-control">
                                <option value="kg">kg</option>
                                <option value="tons">tons</option>
                                <option value="quintals">quintals</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Market Price (per Unit)</label><input type="number" step="0.01"
                                name="market_price" class="form-control" required></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Record Income</button></div>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Prompt to select a cycle is already visible in the card above -->
<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>