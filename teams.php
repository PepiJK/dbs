<?php

require "./credentials.php";
require "./oracledb.class.php";

$db = new ORACLEDB($DBUSER, $DBPW, $DBCONN, $DBCHARSET);
$teams = $db->getTeams();
$leagues = $db->getLeagues();
$teamSponsors = $db->getTeamSponsors();


if (!empty($_POST["title"])) {
    $db->insertTeam($_POST["title"], $_POST["league"]);
    $teams = $db->getTeams();
}

if (isset($_GET["delete"])) {
    $db->deleteTeam($_GET["delete"]);
    header('Location: teams.php');
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
                    <a class="nav-item nav-link active" href="teams.php">Teams<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="matches.php">Matches</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-3">
        <h1>Teams</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Title</th>
                    <th scope="col">League</th>
                    <th scope="col">Sport</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team) { ?>
                    <tr>
                        <th class="align-middle" scope="row"><?php echo ($team->ID) ?></th>
                        <td class="align-middle"><?php echo ($team->Team) ?></td>
                        <td class="align-middle"><?php echo ($team->League) ?></td>
                        <td class="align-middle"><?php echo ($team->Sport) ?></td>
                        <td class="align-middle">
                            <a href="teams.php?delete=<?php echo $team->ID ?>">
                                <button type="button" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h1 class="mt-5">Leagues</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">League</th>
                    <th scope="col">Sport</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leagues as $league) { ?>
                    <tr>
                        <th scope="row"><?php echo ($league->ID) ?></th>
                        <td><?php echo ($league->League) ?></td>
                        <td><?php echo ($league->Sport) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h1 class="mt-5">Sponsors</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Sponsor</th>
                    <th scope="col">Team</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teamSponsors as $sponsor) { ?>
                    <tr>
                        <th scope="row"><?php echo ($sponsor->ID) ?></th>
                        <td><?php echo ($sponsor->Sponsor) ?></td>
                        <td><?php echo ($sponsor->Team) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


        <div class="mt-5">
            <h2>Insert Team</h2>
            <form class="mt-3" action="teams.php" method="POST">
                <div class="form-row">
                    <div class="form-group col">
                        <label for="title">Title</label>
                        <input id="title" type="text" class="form-control" name="title">
                    </div>
                    <div class="form-group col">
                        <label for="league">League</label>
                        <select id="league" class="form-control" name="league">
                            <option class="d-none"></option>
                            <?php foreach ($leagues as $league) { ?>
                                <option value="<?php echo $league->ID ?>"><?php echo $league->League ?></option>
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