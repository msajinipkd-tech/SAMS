<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="card card-body bg-light mb-3">
            <h4>Expert Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo URLROOT; ?>/expert/index"><i class="fa fa-tachometer"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/expert/profile"><i class="fa fa-user"></i> My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/expert/advisory"><i class="fa fa-bullhorn"></i> Post Advisory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/chat"><i class="fa fa-comments"></i> Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/expert/history"><i class="fa fa-history"></i> History & Reports</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="jumbotron jumbotron-fluid text-center bg-primary text-white">
            <div class="container">
                <h1 class="display-4">Expert Dashboard</h1>
                <p class="lead">Manage your advisories and communicate with farmers.</p>
            </div>
        </div>

        <div class="row mb-3">
             <div class="col-md-12">
                 <div class="card bg-white mb-3">
                    <div class="card-body">
                         <h2 class="card-title">Welcome, <?php echo !empty($data['profile']) && !empty($data['profile']->full_name) ? $data['profile']->full_name : $_SESSION['user_username']; ?></h2>
                         <?php if(!empty($data['profile'])): ?>
                             <p class="text-muted"><i class="fa fa-map-marker"></i> <?php echo $data['profile']->address; ?> | <i class="fa fa-phone"></i> <?php echo $data['profile']->phone; ?></p>
                         <?php else: ?>
                             <p><a href="<?php echo URLROOT; ?>/expert/profile">Complete your profile</a></p>
                         <?php endif; ?>
                    </div>
                 </div>
             </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 h-100 shadow-sm">
                    <div class="card-header">Total Advisories</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['advisory_count']; ?></h5>
                        <p class="card-text">Advisories posted by you.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="card text-white bg-info mb-3 h-100 shadow-sm">
                    <div class="card-header">Weather Widget</div>
                    <div class="card-body">
                        <h5 class="card-title">Local Weather</h5>
                        <p class="card-text">
                            <!-- Placeholder for weather API -->
                            <i class="fa fa-sun-o fa-2x"></i> 28&deg;C, Sunny
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="card text-white bg-warning mb-3 h-100 shadow-sm">
                    <div class="card-header">Average Rating</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['average_rating']; ?> / 5</h5>
                        <p class="card-text">Based on farmer feedback.</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <div class="card card-body bg-light mb-3 h-100 shadow-sm">
                    <h3>Recent Advisories</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['recent_advisories'] as $advisory) : ?>
                                <tr>
                                    <td><?php echo $advisory->title; ?></td>
                                    <td><?php echo ucfirst($advisory->type); ?></td>
                                    <td><?php echo date('M d', strtotime($advisory->created_at)); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if(empty($data['recent_advisories'])): ?>
                            <p class="text-center">No advisories yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body bg-light mb-3 h-100 shadow-sm">
                    <h3>Recent Feedback</h3>
                    <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach($data['recent_ratings'] as $rating) : ?>
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    <?php for($i=0; $i<$rating->rating; $i++) echo '★'; ?>
                                    <?php for($i=$rating->rating; $i<5; $i++) echo '☆'; ?>
                                </h5>
                                <small><?php echo date('M d', strtotime($rating->created_at)); ?></small>
                                </div>
                                <p class="mb-1"><?php echo $rating->feedback; ?></p>
                                <small>By: <?php echo $rating->farmer_name; ?></small>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($data['recent_ratings'])): ?>
                            <p class="text-center p-3">No feedback yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
