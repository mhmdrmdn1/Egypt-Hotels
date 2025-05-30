-- Add created_at column to reviews table if it doesn't exist
ALTER TABLE reviews ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Update existing records to have created_at set to current timestamp
UPDATE reviews SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL; 