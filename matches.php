<?php

require "./credentials.php";
require "./oracledb.class.php";

$db = new ORACLEDB($DBUSER, $DBPW, $DBCONN, $DBCHARSET);
$matches = $db->getMatches();
$venues = $db->getVenues();
$teams = $db->getTeams();


if (!empty($_POST["team"]) && !empty($_POST["opponend"])) {
    $datetime = $_POST["date"] . " " . $_POST["time"];
    $db->insertMatch($datetime, $_POST["homegame"], $_POST["opponend"], $_POST["result"], $_POST["venue"], $_POST["team"]);
    $matches = $db->getMatches();
}

if (isset($_GET["delete"])) {
    $db->deleteMatch($_GET["delete"]);
    header('Location: matches.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>SV Yeet</title>
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-brand">SV Yeet</div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link" href="members.php">Members</a>
                    <a class="nav-item nav-link" href="teams.php">Teams</a>
                    <a class="nav-item nav-link active" href="matches.php">Matches<span class="sr-only">(current)</span></a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-3">
        <h1>Matches</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Datetime</th>
                    <th scope="col">Location</th>
                    <th scope="col">Yeet Team</th>
                    <th scope="col">Opponend</th>
                    <th scope="col">Result</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matches as $match) { ?>
                    <tr>
                        <th class="align-middle" scope="row"><?php echo ($match->ID) ?></th>
                        <td class="align-middle"><?php echo ($match->Datetime) ?></td>
                        <td class="align-middle"><?php echo ($match->IS_HOMEGAME == 1) ? 'Home' : 'Away' ?></td>
                        <td class="align-middle"><?php echo ($match->Team) ?></td>
                        <td class="align-middle"><?php echo ($match->OPPONEND) ?></td>
                        <td class="align-middle"><?php echo ($match->RESULT) ?></td>
                        <td class="align-middle"><?php echo ($match->Venue) ?></td>
                        <td class="align-middle">
                            <a href="matches.php?delete=<?php echo $match->ID ?>">
                                <button type="button" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h1 class="mt-5">Venues</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Title</th>
                    <th scope="col">Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venues as $venue) { ?>
                    <tr>
                        <th scope="row"><?php echo ($venue->ID) ?></th>
                        <td><?php echo ($venue->TITLE) ?></td>
                        <td><?php echo ($venue->ADDRESS) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="mt-5">
            <h2>Insert Match</h2>
            <form class="mt-3" action="matches.php" method="POST">
                <div class="form-row">
                    <div class="form-group col">
                        <label for="date">Date</label>
                        <input id="date" type="date" class="form-control" name="date">
                    </div>
                    <div class="form-group col">
                        <label for="time">Time</label>
                        <input id="time" type="time" class="form-control" name="time">
                    </div>
                    <div class="form-group col">
                        <label for="homegame">Location</label>
                        <select id="homegame" class="form-control" name="homegame">
                            <option class="d-none"></option>
                            <option value="1">Home</option>
                            <option value="0">Away</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="team">Yeet Team</label>
                        <select id="team" class="form-control" name="team">
                            <option class="d-none"></option>
                            <?php foreach ($teams as $team) { ?>
                                <option value="<?php echo $team->ID ?>"><?php echo $team->Team ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="opponend">Opponend</label>
                        <input id="opponend" type="text" class="form-control" name="opponend">
                    </div>
                    <div class="form-group col">
                        <label for="result">Result</label>
                        <input id="result" type="text" class="form-control" name="result">
                    </div>
                    <div class="form-group col">
                        <label for="venue">Venue</label>
                        <select id="venue" class="form-control" name="venue">
                            <option class="d-none"></option>
                            <?php foreach ($venues as $venue) { ?>
                                <option value="<?php echo $venue->ID ?>"><?php echo $venue->TITLE ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>