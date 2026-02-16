<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="card card-body bg-light mb-3">
            <a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light btn-block"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-body bg-light mt-5">
            <h2>Post New Advisory</h2>
            <p>Share updates about crops, diseases, or weather with farmers.</p>
            <form action="<?php echo URLROOT; ?>/expert/advisory" method="post">
                <div class="form-group">
                    <label for="title">Title: <sup>*</sup></label>
                    <input type="text" name="title" class="form-control form-control-lg <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
                    <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select name="type" class="form-control">
                        <option value="weather">Weather</option>
                        <option value="crop">Crop</option>
                        <option value="disease">Disease</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message: <sup>*</sup></label>
                    <textarea name="message" class="form-control form-control-lg <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['message']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Post Advisory" class="btn btn-success btn-block">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
