<?php require APPROOT . '/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<div class="card card-body bg-light mt-5">
    <h2>Reply to Request</h2>
    <p><strong>Subject:</strong>
        <?php echo $data['request']->subject; ?>
    </p>
    <p><strong>Message:</strong>
        <?php echo $data['request']->message; ?>
    </p>
    <p class="text-muted"><small>From:
            <?php echo $data['request']->farmer_name; ?>
        </small></p>

    <form action="<?php echo URLROOT; ?>/expert/submit_reply" method="post">
        <input type="hidden" name="request_id" value="<?php echo $data['request']->id; ?>">
        <div class="form-group">
            <label for="answer">Your Advice: *</label>
            <textarea name="answer" class="form-control form-control-lg" rows="5" required></textarea>
        </div>
        <input type="submit" class="btn btn-success" value="Send Reply">
    </form>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>