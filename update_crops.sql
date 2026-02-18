-- Add new columns to crops table
ALTER TABLE crops
ADD COLUMN variety VARCHAR(100) AFTER type,
ADD COLUMN season VARCHAR(50) AFTER variety,
ADD COLUMN duration INT COMMENT 'Duration in days' AFTER season,
ADD COLUMN soil_type VARCHAR(100) AFTER duration,
ADD COLUMN water_requirement VARCHAR(255) AFTER soil_type;
