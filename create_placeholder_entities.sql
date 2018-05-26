-- Scripts to bootstrap the system when creating a new clinic
-- Run this script after the creating the tables with vcp_db.sql

-- The essence of this script is to create the conditions necessary for the first user
-- to register. 


-- Users are required to have a preferred location, partner, and training_level

INSERT INTO locations (`name`) 
VALUES ('Placeholder location. Go to Manage > Locations to edit me and add actual details');

INSERT INTO partners (`name`) 
VALUES ('Placeholder affiliation. Go to Manage > Partners to edit me and add actual details');

SELECT @partner_id := LAST_INSERT_ID();

INSERT INTO training_levels (`created_on`, `name`, `partner_id`) 
VALUES (NOW(), 'Placeholder training level. Go to Manage > Training Levels to edit me and add actual details', @partner_id);