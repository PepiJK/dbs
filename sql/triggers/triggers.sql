/*********************************************************************
/**
/** Trigger: insert_matches
/** Type: Before row
/** Type Extension: insert
/** Developer: Leo Gruber
/** Description: Dieser Trigger garantiert bei Hinzufügen eines 
/**	Matches folgendes: Die Beginnzeiten zu anderen Matches des Teams
/** an jenem Tag müssen mindestens 6 Stunden auseinander liegen, am
/** Veranstaltungsort müssen die Beginnzeiten zu anderen Matches
/** mindestens 3 Stunden auseinander liegen, liegt das Match in der 
/** Zukunft muss das Ergebnis NULL sein
/**		-Exception thrown if team/venue id invalid, 
/**		 if time between matches of teams < 6,
/**		 if time between matches in venue < 3
/*********************************************************************/
/
CREATE OR REPLACE TRIGGER tr_br_i_matches 
before insert on matches
for each row
declare
	l_n_teamID_count teams.id%TYPE;
	l_n_venueID_count venues.id%TYPE;
	l_d_timestamp TIMESTAMP;
	l_n_difference NUMBER;
	CURSOR matches_team_cur IS 
		SELECT m.id FROM teams t 
			JOIN matches m ON t.id = m.team_id
			WHERE t.id = :new.team_id;
	l_cv_matches_team matches_team_cur%ROWTYPE;
	CURSOR matches_venue_cur IS 
		SELECT m.id FROM matches m
			WHERE m.venue_id = :new.venue_id;
	l_cv_matches_venue matches_venue_cur%ROWTYPE;
	x_invalid_team_id EXCEPTION;
	x_invalid_venue_id EXCEPTION;
	x_time_team EXCEPTION;
	x_time_venue EXCEPTION;
	PRAGMA EXCEPTION_INIT(x_invalid_team_id, -20105);
	PRAGMA EXCEPTION_INIT(x_invalid_venue_id, -20106);
	PRAGMA EXCEPTION_INIT(x_time_team, -20107);
	PRAGMA EXCEPTION_INIT(x_time_venue, -20108);
begin
	l_d_timestamp := :new.datetime;
	SELECT COUNT(*) INTO l_n_teamID_count 
	FROM teams WHERE id = :new.team_id;
	IF l_n_teamID_count != 1 THEN
		raise_application_error(-20105, 'Team not found');
	END IF;
	SELECT COUNT(*) INTO l_n_venueID_count
	FROM venues WHERE id = :new.venue_id;
	IF l_n_venueID_count != 1 THEN
		raise_application_error(-20106, 'Venue not found');
	END IF;
	IF l_d_timestamp > SYSTIMESTAMP THEN
		:new.result := NULL;
	END IF;
	OPEN matches_team_cur;
	LOOP
		FETCH matches_team_cur INTO l_cv_matches_team;
		EXIT WHEN matches_team_cur%NOTFOUND;
		SELECT ABS(24*EXTRACT(day FROM diff) + EXTRACT(hour FROM diff)) INTO l_n_difference FROM (
		SELECT l_d_timestamp - m.datetime diff FROM
				matches m WHERE m.id=l_cv_matches_team.id);
		IF l_n_difference < 6 THEN
			raise_application_error(-20107, 'Teams must have at least 6 hours between match starting times');
		END IF;
	END LOOP;
	CLOSE matches_team_cur;
	OPEN matches_venue_cur;
	LOOP
		FETCH matches_venue_cur INTO l_cv_matches_venue;
		EXIT WHEN matches_venue_cur%NOTFOUND;
		SELECT ABS(24*EXTRACT(day FROM diff) + EXTRACT(hour FROM diff)) INTO l_n_difference FROM (
		SELECT l_d_timestamp - m.datetime diff FROM
				matches m WHERE m.id=l_cv_matches_venue.id);
		IF l_n_difference < 3 THEN
			raise_application_error(-20108, 'There must be at least 3 hours between match starting times in a venue');
		END IF;
	END LOOP;
	CLOSE matches_venue_cur;
	EXCEPTION
		WHEN x_invalid_team_id OR x_invalid_venue_id OR x_time_team OR x_time_venue THEN
			pa_logging.sp_ins_error('Exception raised in trigger insert_matches',SQLCODE);
			raise_application_error(-20110, 'Insert failed'); -- raise error to prevent insert 
end;
/

/*********************************************************************
/**
/** Trigger: delete_members
/** Type: Before row
/** Type Extension: delete
/** Developer: Josef Koch
/** Description: Dieser Trigger garantiert, dass bei Löschen eines 
/**	Members, also beim Entfernen eines Vereinsmitglieds, dieser auch
/**	aus zugehörigen Teams und Typen entfernt wird.
/**
/*********************************************************************/
/
CREATE OR REPLACE TRIGGER tr_br_d_members
before delete on members
for each row
begin
	DELETE FROM members_teams_types
	WHERE member_id = :old.id;
end;
/



/*********************************************************************
/**
/** Trigger: delete_teams
/** Type: Before row
/** Type Extension: delete
/** Developer: Daniel Krottendorfer
/** Description: Dieser Trigger garantiert, dass bei Löschen eines 
/**	Teams, zugehörige Matches entfernt werden. Weiters
/** werden zugehörige Members und Types Einträge entfernt, genauso
/** wie Sponsoren Einträge.
/*********************************************************************/
/
CREATE OR REPLACE TRIGGER tr_br_d_teams
before delete on teams
for each row
begin

	DELETE FROM members_teams_types 
	WHERE team_id = :old.id;

	DELETE FROM teams_sponsors
	WHERE team_id = :old.id;
	
	DELETE FROM matches
	WHERE team_id = :old.id;
	
	EXCEPTION 
		WHEN OTHERS THEN
			pa_logging.sp_ins_error('Unexpected Exception raised in trigger delete_teams',SQLCODE);
			RAISE;
end;
/


/*********************************************************************
/**
/** Trigger: delete_teams
/** Type: After row
/** Type Extension: delete
/** Developer: Leo Gruber
/** Description: Dieser Trigger garantiert, dass bei Löschen eines 
/**	Teams, die Matches die in der Zukunft liegen und bei denen
/** das gelöschte Team der Gegner ist, der Name durch 'tbd' ersetzt wird.
/*********************************************************************/
/
CREATE OR REPLACE TRIGGER tr_ar_d_teams
after delete on teams
for each row
begin
	UPDATE matches SET opponend = 'tbd' 
	WHERE opponend = pa_teams.g_v_team_title
	AND DATETIME > SYSTIMESTAMP;
	EXCEPTION 
		WHEN NO_DATA_FOUND THEN
			pa_logging.sp_ins_error('Exception raised in after trigger delete_teams',SQLCODE);
			RAISE;
		WHEN OTHERS THEN
			pa_logging.sp_ins_error('Unexpected Exception raised in after trigger delete_teams',SQLCODE);
			RAISE;
end;
/