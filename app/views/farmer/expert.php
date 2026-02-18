<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<div class="row align-items-center mb-3 mt-3">
    <div class="col-md-6">
        <h1>Expert Advice</h1>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-danger" data-toggle="modal" data-target="#askQuestionModal">
            <i class="fa fa-question-circle"></i> Ask New Question
        </button>
    </div>
</div>

<!-- List of Requests -->
<div class="row">
    <div class="col-md-12">
        <?php if (empty($data['requests'])): ?>
            <p class="text-center text-muted">You haven't asked any questions yet.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($data['requests'] as $req): ?>
                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?php echo $req->subject; ?></h5>
                            <small class="text-muted">Status:
                                <span class="badge badge-<?php echo $req->status == 'answered' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($req->status); ?>
                                </span>
                            </small>
                        </div>
                        <p class="mb-1 text-muted"><?php echo $req->message; ?></p>

                        <?php if ($req->status == 'answered'): ?>
                            <div class="alert alert-success mt-2">
                                <strong>Expert Answer:</strong><br>
                                <?php echo $req->answer; ?>
                                <br>
                                <small class="text-muted">Replied on: <?php echo $req->answered_at; ?></small>
                            </div>
                        <?php else: ?>
                            <p class="text-info mt-2"><i class="fa fa-clock"></i> Waiting for expert reply...</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ask Modal -->
<div class="modal fade" id="askQuestionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/farmer/ask_expert" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ask Expert</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Pest on Leaves"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Describe your problem..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Submit Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>