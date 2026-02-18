<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <style>
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .rating-stars input {
            display: none;
        }
        .rating-stars label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }
        .rating-stars input:checked ~ label {
            color: #ffc107;
        }
        .rating-stars input:hover ~ label {
            color: #ffc107;
        }
    </style>
    <div class="col-md-4">
        <div class="card card-body bg-light mb-3">
            <a href="<?php echo URLROOT; ?>/farmer/dashboard" class="btn btn-light btn-block mb-3"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4>Conversations</h4>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newChatModal"><i class="fa fa-plus"></i> New</button>
            </div>
            <div class="list-group" id="conversation-list" style="max-height: 400px; overflow-y: auto;">
                <?php foreach($data['conversations'] as $conversation) : ?>
                    <a href="#" class="list-group-item list-group-item-action conversation-item" data-id="<?php echo $conversation->id; ?>">
                        <?php echo $conversation->username; ?> <span class="badge badge-secondary float-right"><?php echo ucfirst($conversation->role); ?></span>
                    </a>
                <?php endforeach; ?>
                <?php if(empty($data['conversations'])): ?>
                    <p class="text-muted p-2">No conversations yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- New Chat Modal -->
    <div class="modal fade" id="newChatModal" tabindex="-1" role="dialog" aria-labelledby="newChatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newChatModalLabel">Ask an Expert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <?php foreach($data['experts'] as $expert) : ?>
                                <a href="#" class="list-group-item list-group-item-action new-chat-user" data-id="<?php echo $expert->id; ?>" data-username="<?php echo $expert->username; ?>">
                                    <?php echo $expert->username; ?> <span class="badge badge-info">Expert</span>
                                </a>
                        <?php endforeach; ?>
                        <?php if(empty($data['experts'])): ?>
                             <p>No experts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3" id="chat-card" style="display:none;">
            <div class="card-header" id="chat-header">
                Chat
            </div>
            <div class="card-body chat-window" id="chat-window" style="height: 400px; overflow-y: scroll;">
                <!-- Messages will be loaded here -->
            </div>
            <div class="card-footer">
                <form id="chat-form">
                     <!-- Receiver ID will be set by JS -->
                    <input type="hidden" id="receiver_id" name="receiver_id">
                    <div class="input-group">
                        <input type="text" id="message_input" name="message" class="form-control" placeholder="Type a message..." required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </div>
                </form>
            </div>
                </form>
            </div>
        </div>
        <div class="card card-body text-center" id="no-chat-selected">
            <h3>Select a conversation or start a new chat with an Expert</h3>
        </div>
    </div>
    
    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate Expert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rating-form">
                        <input type="hidden" id="rating_expert_id" name="expert_id">
                        <div class="form-group">
                            <label>Rating:</label>
                            <div class="rating-stars">
                                <input type="radio" name="rating" value="5" id="star5"><label for="star5">☆</label>
                                <input type="radio" name="rating" value="4" id="star4"><label for="star4">☆</label>
                                <input type="radio" name="rating" value="3" id="star3"><label for="star3">☆</label>
                                <input type="radio" name="rating" value="2" id="star2"><label for="star2">☆</label>
                                <input type="radio" name="rating" value="1" id="star1"><label for="star1">☆</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="feedback">Feedback:</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const userId = '<?php echo $_SESSION['user_id']; ?>';
    let currentReceiverId = null;
    let chatInterval = null;
    let statusInterval = null;

    document.addEventListener('DOMContentLoaded', function() {
        const conversationItems = document.querySelectorAll('.conversation-item');
        const chatCard = document.getElementById('chat-card');
        const noChatSelected = document.getElementById('no-chat-selected');
        const chatHeader = document.getElementById('chat-header');
        const chatWindow = document.getElementById('chat-window');
        const receiverIdInput = document.getElementById('receiver_id');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message_input');

        // Existing conversations click
        conversationItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                // Extract name crudely or better, assuming format "Name <span..."
                // let's just get the first text node
                const name = this.childNodes[0].textContent.trim();
                selectUser(this.getAttribute('data-id'), name);
            });
        });

        // New Chat Selection (Experts)
        const newChatUsers = document.querySelectorAll('.new-chat-user');
        newChatUsers.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                
                // Close modal
                $('#newChatModal').modal('hide');
                
                // Select user
                selectUser(id, username);
            });
        });

        function selectUser(id, username) {
            // Remove active class from all
            document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
            
            // Try to find if this user is already in the list
            let existingItem = document.querySelector(`.conversation-item[data-id="${id}"]`);
            
            const list = document.getElementById('conversation-list');
            
            if(!existingItem) {
                // Create temp item
                const newItem = document.createElement('a');
                newItem.href = '#';
                newItem.className = 'list-group-item list-group-item-action conversation-item active';
                newItem.setAttribute('data-id', id);
                newItem.innerHTML = `${username} <span class="badge badge-secondary float-right">New</span>`;
                
                // Remove "No conversations" text if it exists
                if(list.innerText.trim() === 'No conversations yet.') {
                    list.innerHTML = '';
                }
                
                list.prepend(newItem);
                existingItem = newItem;
                
                // Re-bind click event
                newItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    selectUser(id, username);
                });
            } else {
                existingItem.classList.add('active');
            }

            currentReceiverId = id;
            receiverIdInput.value = currentReceiverId;

            // Show chat card, hide placeholder
            chatCard.style.display = 'block';
            noChatSelected.style.display = 'none';

            // Load initial messages
            loadMessages();
            
            // Start polling (if not already for this user, but we just clear and restart)
            if(chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(loadMessages, 3000); 

            // Status Polling
            if(statusInterval) clearInterval(statusInterval);
            updateUserStatus(id); // Initial check
            statusInterval = setInterval(() => updateUserStatus(id), 10000); // Check every 10s

             // Update header
            chatHeader.innerHTML = 'Chat with ' + username + ' <span id="chat-status" class="badge badge-secondary ml-2">Offline</span> <button class="btn btn-sm btn-warning float-right" onclick="openRatingModal(' + id + ')">Rate Expert</button>';
        }

        function updateUserStatus(id) {
            fetch(URLROOT + '/users/status/' + id)
            .then(response => response.json())
            .then(data => {
                const statusSpan = document.getElementById('chat-status');
                if(statusSpan) {
                    if(data.online) {
                        statusSpan.className = 'badge badge-success ml-2';
                        statusSpan.innerText = 'Online';
                    } else {
                        statusSpan.className = 'badge badge-secondary ml-2';
                        statusSpan.innerText = 'Offline';
                    }
                }
            })
            .catch(err => console.error(err));
        }
        
        window.openRatingModal = function(id) {
            document.getElementById('rating_expert_id').value = id;
            $('#ratingModal').modal('show');
        }

        document.getElementById('rating-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(URLROOT + '/farmer/rateExpert', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Thanks for your feedback!');
                    $('#ratingModal').modal('hide');
                    this.reset();
                } else {
                    alert('Error submitting rating');
                }
            })
            .catch(err => console.error(err));
        });

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value;
            if(!message.trim()) return;

            // Send message
            const formData = new FormData();
            formData.append('receiver_id', currentReceiverId);
            formData.append('message', message);

            fetch(URLROOT + '/chat/send', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    messageInput.value = '';
                    loadMessages(); // Refresh immediately
                } else {
                    alert('Error sending message');
                }
            })
            .catch(err => console.error(err));
        });

        function loadMessages() {
            if(!currentReceiverId) return;

            fetch(URLROOT + '/chat/fetch/' + currentReceiverId)
            .then(response => response.json())
            .then(messages => {
                chatWindow.innerHTML = '';
                if(messages.length === 0) {
                     chatWindow.innerHTML = '<p class="text-center text-muted">No messages yet.</p>';
                     return;
                }

                messages.forEach(msg => {
                    const isMe = msg.sender_id == userId;
                    const align = isMe ? 'text-right' : 'text-left';
                    const bg = isMe ? 'bg-primary text-white' : 'bg-light';
                    // Trigger delete on right click via contextmenu event
                    const onRightClick = isMe ? `oncontextmenu="event.preventDefault(); window.deleteMessage(${msg.id});"` : '';
                    
                    let content = msg.message;
                    if(content.startsWith('[VOICE]')) {
                        const filename = content.replace('[VOICE]', '');
                        content = `<audio controls src="${URLROOT}/public/uploads/voice/${filename}"></audio>`;
                    }
                    
                    const msgDiv = document.createElement('div');
                    msgDiv.className = `message ${align} mb-2`;
                    msgDiv.innerHTML = `
                        <div class="message-content ${bg}" style="display:inline-block; padding: 10px; border-radius: 10px; max-width: 70%;" ${onRightClick}>
                            ${content}
                            <small class="d-block ${isMe ? 'text-light' : 'text-muted'}" style="font-size: 0.7rem; opacity: 0.8;">${msg.created_at}</small>
                        </div>
                    `;
                    chatWindow.appendChild(msgDiv);
                });
                
                // Scroll to bottom
                chatWindow.scrollTop = chatWindow.scrollHeight;
            })
            .catch(err => console.error(err));
        }

        window.deleteMessage = function(id) {
            if(confirm('Are you sure you want to delete this message?')) {
                fetch(URLROOT + '/chat/delete/' + id, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        loadMessages();
                    } else {
                        alert('Error deleting message');
                    }
                })
                .catch(err => console.error(err));
            }
        };
    });
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>