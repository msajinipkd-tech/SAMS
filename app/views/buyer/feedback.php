<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row justify-content-center fade-in">
    <div class="col-md-8">
        <div class="glass-card p-5">
            <h2 class="text-center mb-4">Give Feedback</h2>
            <p class="text-center text-muted mb-4">We value your feedback! Let us know your thoughts about the products and service.</p>
            
            <form action="<?php echo URLROOT; ?>/buyer/submitFeedback" method="post">
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea name="message" class="form-control <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>" rows="6" placeholder="Write your feedback here..."><?php echo $data['message']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo URLROOT; ?>/buyer/dashboard" class="btn btn-secondary">Back to Dashboard</a>
                    <button type="submit" class="btn btn-primary px-5">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
