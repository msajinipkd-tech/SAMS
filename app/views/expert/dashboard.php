<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1>Expert Dashboard</h1>
        <p>Welcome, Expert. You have pending questions from farmers.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (empty($data['requests'])): ?>
            <div class="alert alert-success">No pending requests!</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($data['requests'] as $req): ?>
                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">
                                <?php echo $req->subject; ?>
                            </h5>
                            <small class="text-muted">From:
                                <?php echo $req->farmer_name; ?>
                            </small>
                        </div>
                        <p class="mb-1">
                            <?php echo $req->message; ?>
                        </p>
                        <small>Received:
                            <?php echo $req->created_at; ?>
                        </small>
                        <br>
                        <a href="<?php echo URLROOT; ?>/expert/reply/<?php echo $req->id; ?>"
                            class="btn btn-primary btn-sm mt-2">Reply</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>