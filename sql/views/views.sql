/*********************************************************************
/**
/** View: view_members
/** Developer: Josef Koch
/** Description: This view is used to view all members including their
/**	name, sex, birthdate, type, as well as teams they are in and 
/** related league and sports.
/**
/*********************************************************************/

CREATE OR REPLACE VIEW view_members AS
	SELECT members.id, members.firstname AS "Firstname", members.lastname AS "Lastname", 
	members.sex AS "Sex", TO_CHAR(members.date_of_birth, 'dd.mm.yyyy') AS "Date_of_Birth", 
	types.title AS "Type", teams.title AS "Team"
	FROM members_teams_types
	JOIN members ON members_teams_types.member_id = members.id
	LEFT JOIN teams ON members_teams_types.team_id = teams.id
	LEFT JOIN types ON members_teams_types.type_id = types.id
	ORDER BY teams.id, types.id, members.lastname
	
	
	
/*********************************************************************
/**
/** View: view_matches
/** Developer: Daniel Krottendorfer
/** Description: This view is used to view all matches including date
/**	if it is a homegame, team, opponent, result, venue and league.
/**
/*********************************************************************/
	
	
CREATE OR REPLACE VIEW view_matches AS
	SELECT matches.id, TO_CHAR(matches.datetime, 'dd.mm.yyyy hh24:mi') AS "Datetime", matches.is_homegame, teams.title AS "Team", matches.opponend, matches.result, 
	venues.title AS "Venue"
	FROM matches
	LEFT JOIN venues ON matches.venue_id = venues.id
	LEFT JOIN teams ON matches.team_id = teams.id
	ORDER BY matches.datetime, matches.id;
	
	

/*********************************************************************
/**
/** View: view_teams
/** Developer: Leo Gruber
/** Description: This view is used to view all teams that are part of 
/** a league, including team, leauge and sport.
/**
/*********************************************************************/
	
CREATE OR REPLACE VIEW view_teams AS
	SELECT teams.id, teams.title AS "Team", sports.title AS "Sport", leagues.title AS "League" 
	FROM teams 
	INNER JOIN leagues ON teams.league_id = leagues.id
	LEFT JOIN sports ON leagues.sport_id = sports.id
	ORDER BY sports.id, leagues.priority;



/*********************************************************************
/**
/** View: view_teamssponsors
/** Developer: Josef Koch
/** Description: This view is used to view all sponsors and the teams
/** whichare sponsored by that sponsor
/**
/*********************************************************************/
	
CREATE OR REPLACE VIEW view_teamsponsors AS
	SELECT sponsors.id, sponsors.title AS "Sponsor", teams.title AS "Team"
	FROM teams_sponsors
	JOIN sponsors ON teams_sponsors.sponsor_id = sponsors.id
	JOIN teams on teams_sponsors.team_id = teams.id
	ORDER BY sponsors.id;


/*********************************************************************
/**
/** View: view_leagues
/** Developer: Josef Koch
/** Description: This view is used to view all leagues of a all sports
/**
/*********************************************************************/
	
CREATE OR REPLACE VIEW view_leagues AS 
	SELECT leagues.id, leagues.title AS "League", sports.title AS "Sport" 
	FROM sports
	JOIN leagues ON leagues.sport_id = sports.id
	ORDER BY sports.id, leagues.priority;
