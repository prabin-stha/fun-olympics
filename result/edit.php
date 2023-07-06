<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }

    $id = $award = $winner = $runnerUp = $third = $awardedDate = $category = "";
    $awardError = $winnerError = $runnerUpError = $thirdError = $awardedDateError = $categoryError = "";
    
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        require "../config.php";

        //prepare an sql statement
        $sql = "SELECT * FROM result WHERE id = ?";

        if($statement = $conn->prepare($sql)){
            $statement->bind_param("i", $c_id);
        }

        $id = $_GET['id'];
        $c_id = trim($id);

        if($statement->execute()){
            $result = $statement->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_assoc();

                $id = $row["id"];
                $award = $row["award"];
                $category = $row["category_id"];
                $winner = $row["winner"];
                $runnerUp = $row["runner_up"];
                $third = $row["third"];
                $awardedDate = $row["awarded_date"];
            }
            $statement->close();
        }
        $conn->close();

    }


    // post
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = trim($_POST['id']);
        $award = trim($_POST['award']);
        $winner = trim($_POST['winner']);
        $category = (int)trim($_POST['category']);
        $runnerUp = trim($_POST['runnerUp']);
        $third = trim($_POST['third']);
        $awardedDate = trim($_POST['awardedDate']);

        if ($award == '') {
            $awardError = 'Award field is required!';
        }

        if ($winner == '') {
            $winnerError = 'Winner field is required!';
        }

        if ($runnerUp == '') {
            $runnerUpError = 'Runner up field is required!';
        }

        if ($category == '') {
            $categoryError = 'Category field is required!';
        }

        if ($third == '') {
            $thirdError = 'Third field is required!';
        }

        if ($awardedDate == '') {
            $awardedDateError = 'Awarded date field is required!';
        }

        if ($awardError == "" && $winnerError == "" && $runnerUpError == "" && $thirdError == "" && $categoryError == "" && $awardedDateError == "") {
            require "../config.php";

            $sql = "UPDATE result SET award='$award', winner='$winner', category_id=$category, runner_up='$runnerUp', third='$third', awarded_date='$awardedDate' WHERE id = $id";
            $result =mysqli_query($conn, $sql);

            if($result){
                header("location: result-d.php");
                mysqli_close($conn);
            }else{
                echo "Error Updating record" . mysqli_error($conn);
            }
        }
    }
?> 

<?php
    require "../config.php";

    $query ="SELECT * FROM category";
    $result = $conn->query($query);

    if($result->num_rows> 0){
      $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>title-placeholer</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link
        rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"
        />
        <link rel="stylesheet" type="text/css" href="../css/style.css">
    </head>
    <body>
        <?php require "../component/header.php" ?>
        <div class="contain-main">
            <?php require "../component/sidebar.php" ?>
            <div class="box">
                <div style="display: flex;justify-content: center; align-items: center; ">
                    <div>
                        <div style="display: flex; align-items:center; gap: 12px;">
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="result-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">Update Result Details</h1>
                        </div>
                        <p style="color: #333">Celebrate triumphs with our Results Dashboard. Create, edit, view, and delete winning country records effortlessly. Embrace the spirit of victory, honor achievements, and cherish the moments that define greatness. Welcome to a world of results redefined.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/rewards.png" />
                </div>
                <div class="form-container">
                    <form action="edit.php" method="POST">
                        <div class="form-group" style="display: none;">
                            <label for="id">Id</label>
                            <div class="in-box">
                                <input style="border: none;" type="text" name="id" class="form-control" value="<?= $id; ?>">
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="award">Award</label>
                            <div class="in-box">
                                <input type="text" name="award" class="form-control" value="<?= $award; ?>">
                                <p class="text-danger-edit"> <?= $awardError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="in-box">
                                <select name="category" class="form-control">
                                    <option value=""></option>
                                    <?php 
                                        foreach ($options as $option) {
                                    ?>
                                    <?php echo "<option value={$option['id']}" ?> <?php if($category==$option['id']) echo"selected" ?>
                                    <?php echo ">" ?>
                                    <?php echo "{$option['name']}" ?>
                                    </option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                                <p class="text-danger-edit"> <?= $categoryError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="winner">Winner</label>
                            <div class="in-box">
                                <input type="text" name="winner" class="form-control" value="<?= $winner; ?>">
                                <p class="text-danger-edit"> <?= $winnerError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="runnerUp">Runner Up</label>
                            <div class="in-box">
                                <input type="text" name="runnerUp" class="form-control" value="<?= $runnerUp; ?>">
                                <p class="text-danger-edit"> <?= $runnerUpError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for='third'>Third</label>
                            <div class="in-box">
                                <input type="text" name="third" value="<?= $third; ?>" class="form-control">
                                <p class="text-danger-edit"> <?= $thirdError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for='awardedDate'>Awarded At</label>
                            <div class="in-box">
                                <input type="datetime-local" value="<?= $awardedDate; ?>" name="awardedDate" class="form-control" >
                                <p class="text-danger-edit"> <?= $awardedDateError ?> </p>
                            </div>
                        </div>
                        
                        <input type="Submit" value="Update" class="btn-submit">
                    </form>     
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>
</html>