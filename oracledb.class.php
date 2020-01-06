<?php

class ORACLEDB
{
    private $conn;

    public function __construct($DBUSER, $DBPW, $DBCONN, $DBCHARSET)
    {
        $this->conn = oci_connect($DBUSER, $DBPW, $DBCONN, $DBCHARSET);

        if (!$this->conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
    }

    private function executeGet($stid, $curs)
    {
        if (!$stid) {
            $e = oci_error($this->conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Execute statement
        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Execute Cursor
        oci_execute($curs);
        $data = array();
        while (($row = oci_fetch_object($curs, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            array_push($data, $row);
        }

        return $data;
    }

    private function executeInsertDelete($stid)
    {
        if (!$stid) {
            $e = oci_error($this->conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Perform the logic of the query
        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
    }

    // --------------------------------------------------------- MEMBERS ---------------------------------------------------------


    public function getMembers()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_MEMBERS.GET_MEMBERS(:members_cur_out); END;");
        oci_bind_by_name($stid, ":members_cur_out", $curs, -1, OCI_B_CURSOR);

        $members = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $members;
    }

    public function getMemberTypes()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_MEMBERS.GET_TYPES(:types_cur_out); END;");
        oci_bind_by_name($stid, ":types_cur_out", $curs, -1, OCI_B_CURSOR);

        $types = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $types;
    }

    public function insertMember($firstname, $lastname, $sex, $dob, $typeId, $teamId)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_MEMBERS.SP_INS_MEMBER(:l_v_firstname_in, :l_v_lastname_in, :l_v_sex_in, :l_v_birthdate_in, :l_n_typeID_in, :l_n_teamID_in, :l_n_memberID_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_v_firstname_in', $firstname);
        oci_bind_by_name($stid, ':l_v_lastname_in', $lastname);
        oci_bind_by_name($stid, ':l_v_sex_in', $sex);
        oci_bind_by_name($stid, ':l_v_birthdate_in', $dob);
        oci_bind_by_name($stid, ':l_n_typeID_in', $typeId);
        oci_bind_by_name($stid, ':l_n_teamID_in', $teamId);
        oci_bind_by_name($stid, ':l_n_memberID_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }

    public function deleteMember($id)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_MEMBERS.SP_DEL_MEMBER(:l_n_memberID_in, :l_n_valid_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_n_memberID_in', $id);
        oci_bind_by_name($stid, ':l_n_valid_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }

    // --------------------------------------------------------- TEAMS ---------------------------------------------------------

    public function getTeams()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_TEAMS.GET_TEAMS(:teams_cur_out); END;");
        oci_bind_by_name($stid, ":teams_cur_out", $curs, -1, OCI_B_CURSOR);

        $teams = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $teams;
    }

    public function getTeamSponsors()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_TEAMS.GET_TEAMSSPONSORS(:sponsors_cur_out); END;");
        oci_bind_by_name($stid, ":sponsors_cur_out", $curs, -1, OCI_B_CURSOR);

        $teamSponsors = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $teamSponsors;
    }

    public function getLeagues()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_TEAMS.GET_LEAGUES(:leagues_cur_out); END;");
        oci_bind_by_name($stid, ":leagues_cur_out", $curs, -1, OCI_B_CURSOR);

        $leagues = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $leagues;
    }

    public function insertTeam($title, $league)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_TEAMS.SP_INS_TEAM(:l_v_title_in, :l_n_leagueID_in, :l_n_teamID_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_v_title_in', $title);
        oci_bind_by_name($stid, ':l_n_leagueID_in', $league);
        oci_bind_by_name($stid, ':l_n_teamID_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }

    public function deleteTeam($id)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_TEAMS.SP_DEL_TEAM(:l_n_teamID_in, :l_n_valid_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_n_teamID_in', $id);
        oci_bind_by_name($stid, ':l_n_valid_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }


    // --------------------------------------------------------- MATCHES ---------------------------------------------------------

    public function getMatches()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_MATCHES.GET_MATCHES(:matches_cur_out); END;");
        oci_bind_by_name($stid, ":matches_cur_out", $curs, -1, OCI_B_CURSOR);

        $matches = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $matches;
    }

    public function getVenues()
    {
        // Create Cursor
        $curs = oci_new_cursor($this->conn);

        // Prepare the statement
        $stid = oci_parse($this->conn, "BEGIN PA_MATCHES.GET_VENUES(:venues_cur_out); END;");
        oci_bind_by_name($stid, ":venues_cur_out", $curs, -1, OCI_B_CURSOR);

        $venues = $this->executeGet($stid, $curs);

        oci_free_statement($stid);
        oci_free_statement($curs);

        return $venues;
    }

    public function insertMatch($datetime, $homegame, $opponent, $result, $venue, $team)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_MATCHES.SP_INS_MATCH(:l_v_datetime_in, :l_n_homegame_in, :l_v_opponent_in, :l_v_result_in, :l_n_venueID_in, :l_n_teamID_in, :l_n_matchID_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_v_datetime_in', $datetime);
        oci_bind_by_name($stid, ':l_n_homegame_in', $homegame);
        oci_bind_by_name($stid, ':l_v_opponent_in', $opponent);
        oci_bind_by_name($stid, ':l_v_result_in', $result);
        oci_bind_by_name($stid, ':l_n_venueID_in', $venue);
        oci_bind_by_name($stid, ':l_n_teamID_in', $team);
        oci_bind_by_name($stid, ':l_n_matchID_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }

    public function deleteMatch($id)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_MATCHES.SP_DEL_MATCH(:l_n_matchID_in, :l_n_valid_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_n_matchID_in', $id);
        oci_bind_by_name($stid, ':l_n_valid_out', $out);

        $this->executeInsertDelete($stid);

        oci_free_statement($stid);
    }


    function __destruct()
    {
        oci_close($this->conn);
    }
}
