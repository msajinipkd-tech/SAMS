<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    </div>
    <div class="col-md-4">
        <div class="card card-body bg-light mb-3">
            <a href="<?php echo URLROOT; ?>/expert/index" class="btn btn-light btn-block mb-3"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
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
                    <h5 class="modal-title" id="newChatModalLabel">Start New Chat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <?php foreach($data['users'] as $user) : ?>
                            <?php if($user->id != $_SESSION['user_id']): ?>
                                <a href="#" class="list-group-item list-group-item-action new-chat-user" data-id="<?php echo $user->id; ?>" data-username="<?php echo $user->username; ?>">
                                    <?php echo $user->username; ?> <span class="badge badge-info"><?php echo ucfirst($user->role); ?></span>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
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
                    <input type="hidden" id="receiver_id" name="receiver_id">
                    <div class="input-group">
                        <div class="input-group-prepend">
                             <button type="button" class="btn btn-secondary" id="mic-btn"><i class="fa fa-microphone"></i></button>
                        </div>
                        <input type="text" id="message_input" name="message" class="form-control" placeholder="Type a message..." required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-body text-center" id="no-chat-selected">
            <h3>Select a conversation to start chatting</h3>
        </div>
    </div>
</div>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const userId = '<?php echo $_SESSION['user_id']; ?>';
    let currentReceiverId = null;
    let chatInterval = null;

    document.addEventListener('DOMContentLoaded', function() {
        const conversationItems = document.querySelectorAll('.conversation-item');
        const chatCard = document.getElementById('chat-card');
        const noChatSelected = document.getElementById('no-chat-selected');
        const chatHeader = document.getElementById('chat-header');
        const chatWindow = document.getElementById('chat-window');
        const receiverIdInput = document.getElementById('receiver_id');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message_input');

        conversationItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                selectUser(this.getAttribute('data-id'), this.textContent.trim().split(' ')[0]); // Extract name crudely or better
            });
        });

        // New Chat Selection
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
            conversationItems.forEach(i => i.classList.remove('active'));
            
            // Try to find if this user is already in the list
            let existingItem = document.querySelector(`.conversation-item[data-id="${id}"]`);
            
            if(!existingItem) {
                // Create temp item
                const list = document.getElementById('conversation-list');
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
                
                // Re-bind click event (or use delegation, but simple re-bind for now or just let it be)
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
            
            // Start polling (if not already)
            if(chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(loadMessages, 3000); 

             // Update header
            chatHeader.innerText = 'Chat with ' + username;
        }

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

            fetch(URLROOT + '/chat/fetch/' + currentReceiverId) // The fetch method in controller looks for raw arg or POST? 
            // My controller implementation: public function fetch($other_user_id)
            // But standard MVC frameworks often map params. Assuming libraries support /controller/method/param
            // Let's verify routes. Usually yes.
            .then(response => response.json()) // Controller returns JSON
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

        // Voice Chat Logic
        const micBtn = document.getElementById('mic-btn');
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;

        micBtn.addEventListener('click', async () => {
             if (!isRecording) {
                // Start Recording
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.addEventListener("dataavailable", event => {
                        audioChunks.push(event.data);
                    });

                    mediaRecorder.addEventListener("stop", () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        sendVoiceMessage(audioBlob);
                        
                        // Stop all tracks to release mic
                        stream.getTracks().forEach(track => track.stop());
                    });

                    mediaRecorder.start();
                    isRecording = true;
                    micBtn.classList.remove('btn-secondary');
                    micBtn.classList.add('btn-danger');
                    micBtn.innerHTML = '<i class="fa fa-stop"></i>';
                } catch (err) {
                    console.error("Error accessing microphone:", err);
                    alert("Could not access microphone. Please allow permissions.");
                }
            } else {
                // Stop Recording
                mediaRecorder.stop();
                isRecording = false;
                micBtn.classList.remove('btn-danger');
                micBtn.classList.add('btn-secondary');
                micBtn.innerHTML = '<i class="fa fa-microphone"></i>';
            }
        });

        function sendVoiceMessage(audioBlob) {
            const formData = new FormData();
            formData.append('receiver_id', currentReceiverId);
            formData.append('audio', audioBlob);

            fetch(URLROOT + '/chat/sendVoice', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    loadMessages();
                } else {
                    alert('Error sending voice message');
                }
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
