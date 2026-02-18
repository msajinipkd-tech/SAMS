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
                        <label for="variety">Variety:</label>
                        <input type="text" name="variety" class="form-control" value="<?php echo $data['variety']; ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="season">Season:</label>
                                <select name="season" class="form-control">
                                    <option value="Kharif" <?php echo ($data['season'] == 'Kharif') ? 'selected' : ''; ?>>
                                        Kharif</option>
                                    <option value="Rabi" <?php echo ($data['season'] == 'Rabi') ? 'selected' : ''; ?>>Rabi
                                    </option>
                                    <option value="Zaid" <?php echo ($data['season'] == 'Zaid') ? 'selected' : ''; ?>>Zaid
                                    </option>
                                    <option value="All Year" <?php echo ($data['season'] == 'All Year') ? 'selected' : ''; ?>>All Year</option>
                                    <option value="" <?php echo ($data['season'] == '') ? 'selected' : ''; ?>>Select
                                        Season</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration">Duration (days):</label>
                                <input type="number" name="duration" class="form-control"
                                    value="<?php echo $data['duration']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="soil_type">Required Soil Type:</label>
                        <select name="soil_type" class="form-control">
                            <?php
                            $soil_types = [
                                'Alluvial Soil',
                                'Black Cotton Soil',
                                'Red & Yellow Soil',
                                'Laterite Soil',
                                'Mountainous or Forest Soil',
                                'Arid or Desert Soil',
                                'Saline and Alkaline Soil',
                                'Peaty and Marshy Soil'
                            ];
                            ?>
                            <option value="">Select Soil Type</option>
                            <?php foreach ($soil_types as $soil): ?>
                                <option value="<?php echo $soil; ?>" <?php echo ($data['soil_type'] == $soil) ? 'selected' : ''; ?>>
                                    <?php echo $soil; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="water_requirement">Water Requirements:</label>
                        <input type="text" name="water_requirement" class="form-control"
                            value="<?php echo $data['water_requirement']; ?>">
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