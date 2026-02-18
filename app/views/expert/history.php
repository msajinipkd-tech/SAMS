<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="card card-body bg-light mb-3">
            <a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light btn-block"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-body bg-light mb-3">
            <h2>Advisory History & Reports</h2>
            <p>List of all advisories posted by you.</p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Date Posted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['advisories'] as $advisory) : ?>
                        <tr>
                            <td><?php echo $advisory->title; ?></td>
                            <td><?php echo substr($advisory->message, 0, 50) . '...'; ?></td>
                            <td><?php echo ucfirst($advisory->type); ?></td>
                            <td><?php echo $advisory->created_at; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
