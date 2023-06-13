-- #! mysql
-- #{ database
-- { init
CREATE TABLE IF NOT EXISTS punishments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    modType INT(11) NOT NULL,
    target VARCHAR(255) NOT NULL,
    reason TEXT NOT NULL,
    staff VARCHAR(255) NOT NULL,
    duration TEXT NOT NULL,
    timeAt INT(11) NOT NULL,
    PRIMARY KEY (id)
);
-- }
-- { add
INSERT INTO punishments (modType, target, reason, staff, duration, timeAt) VALUES (modType, target, reason, staff, duration, timeAt);
-- }
-- { remove
UPDATE punishments SET duration = duration - 20 WHERE target = :target AND modType = :modType
-- }
-- { getAll
SELECT * FROM punishments WHERE target = :target AND modType = :modType
-- }
-- #}