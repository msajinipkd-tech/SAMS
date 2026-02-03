<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <a href="<?php echo URLROOT; ?>/crops" class="btn btn-primary">
            < Back</a>
                <h2>Edit Crop</h2>
                <form action="<?php echo URLROOT; ?>/crops/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="name">Name: <sup>*</sup></label>
                        <input type="text" name="name"
                            class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['name_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="type">Type: <sup>*</sup></label>
                        <input type="text" name="type"
                            class="form-control <?php echo (!empty($data['type_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['type']; ?>">
                        <span class="invalid-feedback">
                            <?php echo $data['type_err']; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control"><?php echo $data['description']; ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>