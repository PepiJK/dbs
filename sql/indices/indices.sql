/******* INDICES FOREIGN KEYS *********/

CREATE INDEX idx_leagues_fk_sportID ON leagues(sport_id);
CREATE INDEX idx_teams_fk_leagueID ON teams(league_id);
CREATE INDEX idx_teams_sponsors_fk_teamID ON teams_sponsors(team_id);
CREATE INDEX idx_teams_sponsors_fk_sponsorID ON teams_sponsors(sponsor_id);
CREATE INDEX idx_members_teams_types_fk_memberID ON members_teams_types(member_id);
CREATE INDEX idx_members_teams_types_fk_teamID ON members_teams_types(team_id);
CREATE INDEX idx_members_teams_types_fk_typeID ON members_teams_types(type_id);
CREATE INDEX idx_matches_fk_venueID ON matches(venue_id);
CREATE INDEX idx_matches_fk_teamID ON matches(team_id);

/******* OTHER INDICES *******/

CREATE INDEX idx_matches_datetime ON matches(datetime); -- for faster selection on attribute "datetime" in table matches, often used for sorting and time calculation
CREATE INDEX idx_leagues_priority ON leagues(priority); -- mainly for faster ordering by priority in table leagues, since values are already sorted in index
CREATE INDEX idx_members_lastname_firstname ON members(lastname, firstname); -- for faster ordering by lastname and firstname in table members, as well as faster selection on lastname
CREATE INDEX idx_types_title ON types(title); -- for faster selection of attribute title in table types, if filtering for specific types is required
CREATE INDEX idx_sponsors_title ON sponsors(title); -- for faster selection of attribute title, if filtering for specific sponsors is required