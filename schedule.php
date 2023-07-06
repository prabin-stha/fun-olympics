<?php
    session_start();
    date_default_timezone_set('Asia/Kathmandu');
    
    $sql;
    require "./config.php";

    $viewingSchedules = true;
    $userId = (int)$_SESSION['SESSION_ID'];

    if(isset($_GET['broadcast'])){
        $viewingSchedules = false;
        $broadcastId = (int)mysqli_real_escape_string($conn, $_GET['broadcast']);
        $sql = "SELECT b.id, b.title, b.description, b.thumbnail, c.name as category, b.url, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id WHERE b.id={$broadcastId};";
    }else{
        $sql = 'SELECT b.id, b.title, b.thumbnail, b.category_id, c.name as category, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id;';
        $viewingSchedules = true;
    }

    function convertToFormattedTime($dateString) {
        $date = new DateTime($dateString);
        $formattedDate = $date->format("h:i A");
        return $formattedDate;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>title-placeholder</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link
        rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous"
        />
        <link rel="stylesheet" type="text/css" href="/fun-olympics/css/style.css">
    </head>
    <body>
        <?php require "./component/header.php" ?>
        <div class="contain-main">
            <?php require "./component/sidebar.php" ?>
            <div class="box">
                <div>
                    <?php
                        if($viewingSchedules){

                            echo '<div class="contain-title" style="padding-bottom: 24px;">';
                            echo '<img width="100%" height="350px" style="object-fit:cover;object-position:center;" src="/fun-olympics/images/broadcast-hero.png" alt="hero image"/>';
                            echo '</div>';
                            echo '<div>';
                                if ($result = $conn->query($sql)){
                                    if ($result->num_rows > 0){
                                        $broadcasts = mysqli_fetch_all($result, MYSQLI_ASSOC);

                                        $startDates = array();

                                        foreach ($broadcasts as $broadcast) {
                                            $currentDateTime = new DateTime(null, new DateTimeZone('Asia/Kathmandu'));
                                            $currentEpochMilliseconds = $currentDateTime->format('Y-m-d H:i:s.u');

                                            $previousDate = $broadcast['starts_at'];
                                            $previousDateTime = new DateTime($previousDate, new DateTimeZone('Asia/Kathmandu'));
                                            $previousEpochMilliseconds = $previousDateTime->format('Y-m-d H:i:s.u');

                                            $startDateTime = new DateTime($broadcast['starts_at']);
                                            $formattedStartDate = $startDateTime->format('Y-m-d');

                                            if($currentEpochMilliseconds < $previousEpochMilliseconds){
                                                $startDates[$formattedStartDate] = $startDateTime;
                                            }
                                        }

                                        // Sort the start dates in ascending order
                                        usort($startDates, function ($a, $b) {
                                            return $a <=> $b;
                                        });

                                        $currentDateTime = new DateTime();
                                        $tomorrowDate = new DateTime('tomorrow');

                                        foreach ($startDates as $startDate) {
                                            $broadcastList = array();
                                            foreach ($broadcasts as $broadcast){
                                                $startDateTime = new DateTime($broadcast['starts_at']);
                                                if($startDate->format('Y-m-d') === $startDateTime->format('Y-m-d')){
                                                    $currentTime = time();

                                                    $convertedStartsAt = strtotime($broadcast['starts_at']);

                                                    $startDateTime = new DateTime($broadcast['starts_at']);
                                                    $formattedStartDate = $startDateTime->format('Y-m-d');

                                                    if($currentTime < $convertedStartsAt){
                                                        array_push($broadcastList, $broadcast);
                                                    }
                                                }

                                            }


                                            // array_filter($broadcastList, function($e){
                                            //     return $currentDateTime < $startDate;
                                            // });

                                            $formattedStartDate = $startDate->format('Y-m-d');

                                            if(($startDate->format('Y-m-d') === $currentDateTime->format('Y-m-d'))){
                                                echo '<div style="padding-bottom: 22px;">';
                                                echo "<h3 style='padding-bottom: 12px; color: #225c55;'>Today</h3>";
                                                echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                                echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">';
                                                foreach($broadcastList as $broadcast){
                                                    echo "<div style='display: grid; grid-template-rows: 200px 1fr;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='100%' style='object-fit: cover;border-radius: 12px 12px 0 0;margin: 2px' src='".$broadcast['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $broadcast['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #225c55;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $broadcast['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 80px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;color: #333;">' . convertToFormattedTime($broadcast['starts_at']) . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/schedule.php?broadcast=' . $broadcast['id'] . '" style="display: block; text-align: center;">View Details</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                                echo "</div>";
                                                echo "</div>";
                                                echo '</div>';
                                            }else if(($startDate->format('Y-m-d') === $tomorrowDate->format('Y-m-d'))){
                                                echo '<div style="padding-bottom: 22px;">';
                                                echo "<h3 style='padding-bottom: 12px; color: #225c55;'>Tomorrow</h3>";
                                                echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                                echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">';
                                                foreach($broadcastList as $broadcast){
                                                    echo "<div style='display: grid; grid-template-rows: 200px 1fr;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='100%' style='object-fit: cover;border-radius: 12px 12px 0 0;margin: 2px' src='".$broadcast['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $broadcast['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #225c55;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $broadcast['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 80px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;color: #333;">' . convertToFormattedTime($broadcast['starts_at']) . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/schedule.php?broadcast=' . $broadcast['id'] . '" style="display: block; text-align: center;">View Details</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                                echo "</div>";
                                                echo "</div>";
                                                echo '</div>';
                                            }else{
                                                echo '<div style="padding-bottom: 22px;">';
                                                echo "<h4 style='padding-bottom: 12px; color: #225c55;'>$formattedStartDate</h4>";
                                                echo "<div style='display: flex; flex-direction: column; gap: 8px'>";
                                                echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">';
                                                foreach($broadcastList as $broadcast){
                                                    echo "<div style='display: grid; grid-template-rows: 200px 1fr;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='100%' style='object-fit: cover;border-radius: 12px 12px 0 0;margin: 2px' src='".$broadcast['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $broadcast['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #225c55;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $broadcast['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 80px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;color: #333;">' . convertToFormattedTime($broadcast['starts_at']) . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/schedule.php?broadcast=' . $broadcast['id'] . '" style="display: block; text-align: center;">View Details</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                                echo "</div>";
                                                echo "</div>";
                                                echo '</div>';
                                            }
                                        }
                                    }else{
                                        echo "<p style='font-size: 18px;'>No Broadcasts Found</p>";
                                    }
                                }
                            echo "</div>";
                        }else{
                            // Single Broadcast
                            if($result = $conn->query($sql)){
                                if($result->num_rows == 1){
                                    $row = $result->fetch_assoc();
                                    $modifiedDesc = str_replace("'", "\\'", $row['description']);

                                    $broadcastId = (int)$row['id'];
                                    $query = "SELECT * from broadcast_notification where broadcast_id=" . $broadcastId . " and user_id=" . $userId . ";";
                                    $checked;

                                    if ($r = $conn->query($query)){
                                        if ($r->num_rows > 0){
                                            $ro = $r->fetch_assoc();
                                            $checked = $ro['notify'] === '1' ? TRUE : FALSE;
                                        }else{
                                            $checked=FALSE;
                                        }
                                    }

                                    echo "<div>";
                                    echo "<div style='display: flex; justify-items: space-between; align-items: center;'>";
                                    echo '<img width="65%" height="350px" style="object-fit:cover;object-position:50% 50%; border-radius: 12px;" src="'. $row['thumbnail'] .'" alt="hero image"/>';
                                    echo "<div style='display: flex; flex-direction: column; align-items: center; gap: 12px;'>";
                                    echo "<img width='30%' src='./images/achievement.svg' />";
                                    echo "<p>Winner will be awarded title of</p>";
                                    echo "<small>.".$row['award']."</small>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "<h2 style='padding-top: 16px;'>" . $row['title'] ."</h2>";
                                    echo "<div style='display: flex;gap: 18px;'>";
                                    echo "<div>";
                                    echo "<h3 style='padding-top: 12px; padding-bottom: 6px'>Description</h3>";
                                    echo "<p>" . $modifiedDesc . "</p>";
                                    if(isset($_SESSION["SESSION_ID"])){
                                        echo '<label role="button" style="display: flex; align-items: center; gap: 6px; margin-top: 12px; cursor: pointer; margin-top: 24px;">';
                                        echo '<input type="checkbox" '; 
                                        echo $checked ? 'checked' : "";
                                        echo ' data-broadcast-id="' . $row['id'] . '" onclick="check(this);" value="'. $row['id'] .'" />';
                                        echo 'Get Notified?';
                                        echo '</label>';
                                    }
                                    echo "</div>";
                                    echo "<div style='min-width: 400px'>";
                                    echo "<h4 style='padding-top: 12px; padding-bottom: 6px'>Category</h4>";
                                    echo "<p>" . $row['category'] . "</p>";
                                    echo "<h4 style='padding-top: 12px; padding-bottom: 6px'>Location</h4>";
                                    echo "<p>" . $row['location'] . "</p>";
                                    echo "<h4 style='padding-top: 12px; padding-bottom: 6px'>Gender Representation</h4>";
                                    echo "<p>" . $row['gender_representation'] . "</p>";
                                    echo "<h4 style='padding-top: 12px; padding-bottom: 6px'>Time</h4>";
                                    echo "<p>" . convertToFormattedTime($broadcast['starts_at']) . ' - ' . convertToFormattedTime($broadcast['ends_at']) . "</p>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            }
                        }
                    ?> 
                </div>
            </div>
        </div>
        <?php require "./component/footer.php" ?>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script>
            function check(cb)
            {
                const broadcastId = cb.getAttribute("data-broadcast-id");
                if($(cb).is(":checked")){
                    $.getScript('./actions/checkCheckbox.php?broadcast=' + broadcastId);
                }else{
                    $.getScript('./actions/uncheckCheckbox.php?broadcast=' + broadcastId);
                }
            }
        </script>
    </body>
</html>