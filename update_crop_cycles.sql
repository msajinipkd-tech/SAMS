-- Add new columns to crop_cycles table
ALTER TABLE crop_cycles
ADD COLUMN seed_quantity DECIMAL(10,2) DEFAULT 0.00 AFTER expected_harvest_date,
ADD COLUMN sowing_method VARCHAR(50) DEFAULT 'Direct Seeding' AFTER seed_quantity,
ADD COLUMN expected_yield DECIMAL(10,2) DEFAULT 0.00 AFTER sowing_method,
ADD COLUMN notes TEXT AFTER expected_yield;
