USE agriculture;

ALTER TABLE expert_requests
ADD COLUMN answer TEXT AFTER message,
ADD COLUMN expert_id INT AFTER answer,
ADD COLUMN answered_at TIMESTAMP NULL AFTER expert_id;

-- Add foreign key if we want to valid expert_id, but user table shares IDs so it's INT
-- ALTER TABLE expert_requests ADD FOREIGN KEY (expert_id) REFERENCES users(id);
