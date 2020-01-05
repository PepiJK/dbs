<?php

require "./credentials.php";
require "./oracledb.class.php";

$db = new ORACLEDB($DBUSER, $DBPW, $DBCONN, $DBCHARSET);
$members = $db->getMembers();

if (!empty($_POST["firstname"]) && !empty($_POST["lastname"])) {
    $db->insertMember($_POST["firstname"], $_POST["lastname"], $_POST["sex"], $_POST["date"], $_POST["type"], $_POST["team"]);
    $members = $db->getMembers();
}

if (isset($_GET["delete"])) {
    $db->deleteMember($_GET["delete"]);
    header('Location: members.php');
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
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-brand">SV Yeet</div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="members.php">Members<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="teams.php">Teams</a>
                    <a class="nav-item nav-link" href="matches.php">Matches</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-3">
        <h1>Members</h1>
        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Sex</th>
                    <th scope="col">Date of Birth</th>
                    <th scope="col">Type</th>
                    <th scope="col">Team</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member) { ?>
                    <tr>
                        <th scope="row"><?php echo ($member->ID) ?></th>
                        <td><?php echo ($member->Firstname) ?></td>
                        <td><?php echo ($member->Lastname) ?></td>
                        <td><?php echo ($member->Sex) ?></td>
                        <td><?php echo ($member->Date_of_Birth) ?></td>
                        <td><?php echo ($member->Type) ?></td>
                        <td><?php echo ($member->Team) ?></td>
                        <td>
                            <a href="members.php?delete=<?php echo $member->ID ?>">
                                <button type="button" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="mt-5">
            <h2>Insert</h2>
            <form class="mt-3" action="members.php" method="POST">
                <div class="form-row">
                    <div class="form-group col">
                        <label for="firstname">Firstname</label>
                        <input id="firstname" type="text" class="form-control" name="firstname">
                    </div>
                    <div class="form-group col">
                        <label for="lastname">Lastname</label>
                        <input id="lastname" type="text" class="form-control" name="lastname">
                    </div>
                    <div class="form-group col-2">
                        <label for="sex">Sex</label>
                        <select id="sex" class="form-control" name="sex">
                            <option class="d-none"></option>
                            <option value="f">Female</option>
                            <option value="m">Male</option>
                            <option value="n">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="date">Date off Birth</label>
                        <input id="date" type="date" class="form-control" name="date">
                    </div>
                    <div class="form-group col-2">
                        <label for="type">Type Id</label>
                        <input id="type" type="number" class="form-control" name="type">
                    </div>
                    <div class="form-group col-2">
                        <label for="team">Team Id</label>
                        <input id="team" type="number" class="form-control" name="team">
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