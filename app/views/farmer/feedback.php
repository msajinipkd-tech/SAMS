<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Buyer Feedback</h2>
        <a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="glass-card p-4">
        <?php if(empty($data['feedbacks'])): ?>
            <div class="text-center p-5">
                <h4 class="text-muted">No feedback received yet.</h4>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Buyer</th>
                            <th scope="col">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['feedbacks'] as $feedback) : ?>
                            <tr>
                                <td class="text-muted" style="min-width: 120px;">
                                    <?php echo date('M d, Y', strtotime($feedback->feedbackCreated)); ?>
                                </td>
                                <td class="font-weight-bold" style="min-width: 150px;">
                                    <?php echo $feedback->username; ?>
                                </td>
                                <td>
                                    <?php echo nl2br($feedback->message); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
