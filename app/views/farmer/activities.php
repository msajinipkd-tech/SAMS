<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<div class="row align-items-center mb-3">
    <div class="col-md-6">
        <h1>Activity Planner</h1>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addActivityModal">
            <i class="fa fa-plus"></i> Add New Task
        </button>
    </div>
</div>

<!-- Activities List -->
<?php if (empty($data['activities'])): ?>
    <p class="text-muted text-center">No activities planned. Add a task to get started.</p>
<?php else: ?>
    <div class="row">
        <?php
        $currentDate = null;
        foreach ($data['activities'] as $activity):
            if ($currentDate != $activity->date):
                $currentDate = $activity->date;
                ?>
                <div class="col-12 mt-3 mb-2">
                    <h4 class="text-primary border-bottom pb-2">
                        <?php echo date('F j, Y', strtotime($currentDate)); ?>
                    </h4>
                </div>
            <?php endif; ?>

            <div class="col-md-12 mb-2">
                <div
                    class="card shadow-sm <?php echo $activity->status == 'completed' ? 'border-success bg-light' : 'border-left-primary'; ?>">
                    <div class="card-body d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="card-title mb-1 <?php echo $activity->status == 'completed' ? 'text-muted' : ''; ?>"
                                style="<?php echo $activity->status == 'completed' ? 'text-decoration: line-through;' : ''; ?>">
                                <?php echo $activity->title; ?>
                            </h5>
                            <p class="card-text mb-0 text-muted small">
                                <?php echo $activity->description; ?>
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="<?php echo URLROOT; ?>/farmer/toggle_activity_status/<?php echo $activity->id; ?>"
                                class="btn btn-sm btn-outline-<?php echo $activity->status == 'completed' ? 'secondary' : 'success'; ?>"
                                title="<?php echo $activity->status == 'completed' ? 'Mark Pending' : 'Mark Complete'; ?>">
                                <i class="fa fa-<?php echo $activity->status == 'completed' ? 'undo' : 'check'; ?>"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-info" data-toggle="modal"
                                data-target="#editActivityModal<?php echo $activity->id; ?>">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a href="<?php echo URLROOT; ?>/farmer/delete_activity/<?php echo $activity->id; ?>"
                                class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editActivityModal<?php echo $activity->id; ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="<?php echo URLROOT; ?>/farmer/edit_activity" method="POST">
                            <input type="hidden" name="id" value="<?php echo $activity->id; ?>">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo $activity->title; ?>"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" value="<?php echo $activity->date; ?>"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description"
                                        class="form-control"><?php echo $activity->description; ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/farmer/add_activity" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Task</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Spray Pesticide"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
    <?php require APPROOT . '/views/inc/footer.php'; ?>