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

    public function getMembers()
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'SELECT * FROM VIEW_MEMBERS');
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

        // Fetch the results of the query
        $members = array();
        while ($row = oci_fetch_object($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            array_push($members, $row);
        }

        oci_free_statement($stid);
        return $members;
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

    public function deleteMember($id)
    {
        // Prepare the statement
        $stid = oci_parse($this->conn, 'BEGIN PA_MEMBERS.SP_DEL_MEMBER(:l_n_memberID_in, :l_n_valid_out); END;');
        $out = null;
        oci_bind_by_name($stid, ':l_n_memberID_in', $id);
        oci_bind_by_name($stid, ':l_n_valid_out', $out);

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


    function __destruct()
    {
        oci_close($this->conn);
    }
}
