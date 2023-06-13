-- #! sqlite
-- #{ database
        -- { init
CREATE TABLE IF NOT EXISTS punishments (
  id INT NOT NULL AUTO_INCREMENT,
  modType INT NOT NULL,
  target TEXT NOT NULL,
  reason TEXT NOT NULL,
  staff TEXT NOT NULL,
  duration TEXT NOT NULL,
  timeAt INT NOT NULL,
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