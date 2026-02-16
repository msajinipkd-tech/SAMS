<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="card card-body bg-light mb-3">
            <a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light btn-block mb-3"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
            <h4>Conversations</h4>
            <div class="list-group">
                <?php foreach($data['conversations'] as $conversation) : ?>
                    <a href="<?php echo URLROOT; ?>/expert/chat/<?php echo $conversation->id; ?>" class="list-group-item list-group-item-action <?php echo (isset($data['currentChatUser']) && $data['currentChatUser']->id == $conversation->id) ? 'active' : ''; ?>">
                        <?php echo $conversation->username; ?> <span class="badge badge-secondary"><?php echo ucfirst($conversation->role); ?></span>
                    </a>
                <?php endforeach; ?>
                <?php if(empty($data['conversations'])): ?>
                    <p>No conversations yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?php if($data['currentChatUser']): ?>
            <div class="card mb-3">
                <div class="card-header">
                    Chat with <?php echo $data['currentChatUser']->username; ?>
                </div>
                <div class="card-body chat-window" style="height: 400px; overflow-y: scroll;">
                    <?php foreach($data['messages'] as $msg) : ?>
                        <div class="message <?php echo ($msg->sender_id == $_SESSION['user_id']) ? 'text-right' : 'text-left'; ?>">
                            <div class="message-content <?php echo ($msg->sender_id == $_SESSION['user_id']) ? 'bg-primary text-white' : 'bg-light'; ?>" style="display:inline-block; padding: 10px; border-radius: 10px; margin: 5px;">
                                <?php echo $msg->message; ?>
                                <small class="d-block text-muted" style="font-size: 0.7rem; color: #ddd;"><?php echo $msg->created_at; ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <form action="<?php echo URLROOT; ?>/expert/sendMessage" method="post">
                        <input type="hidden" name="receiver_id" value="<?php echo $data['currentChatUser']->id; ?>">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card card-body text-center">
                <h3>Select a conversation to start chatting</h3>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
