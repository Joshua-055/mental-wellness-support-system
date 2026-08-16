-- Align stored mood values with the labels shown on the student home page.
ALTER TABLE wellness_checkins
  MODIFY mood ENUM('very_good','excellent','good','neutral','low','stressed','very_low','overwhelmed') NOT NULL;

UPDATE wellness_checkins SET mood = 'excellent' WHERE mood = 'very_good';
UPDATE wellness_checkins SET mood = 'stressed' WHERE mood = 'low';
UPDATE wellness_checkins SET mood = 'overwhelmed' WHERE mood = 'very_low';

ALTER TABLE wellness_checkins
  MODIFY mood ENUM('excellent','good','neutral','stressed','overwhelmed') NOT NULL;
