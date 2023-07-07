<?php
    session_start();

    function getStatus($startsAt, $endsAt) {
        $currentTime = time();

        $convertedStartsAt = strtotime($startsAt);
        $convertedEndsAt = strtotime($endsAt); 
    
        if ($currentTime < $convertedStartsAt) {
            return "NOT YET AIRED";
        } elseif ($currentTime > $convertedEndsAt) {
            return "COMPLETED";
        } else {
            return "ONGOING";
        }
    }

    $sql;
    require "../config.php";

    $viewingBroadcasts = true;
    $viewingCategories = false;
    $categoryThumbnail = null;
    $userId = (int)$_SESSION['SESSION_ID'];

    if(isset($_GET['broadcast'])){
        $viewingBroadcasts = false;
        $broadcastId = (int)mysqli_real_escape_string($conn, $_GET['broadcast']);
        $_SESSION["SESSION_CURRENT_BROADCAST"] = $broadcastId;
        $sql = "SELECT b.id, b.title, b.description, b.thumbnail, c.name as category, b.url, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id WHERE b.id={$broadcastId};";
        
        if ($resultSelect = $conn->query("SELECT * from broadcast_notification bn left join broadcast b on bn.broadcast_id=b.id where bn.user_id=$userId AND bn.broadcast_id=$broadcastId AND b.starts_at < NOW()")){
            if ($resultSelect->num_rows == 1){
                $sqlNotifyUpdate = "UPDATE broadcast_notification SET notify='0', mark_as_read='0' where user_id=$userId AND broadcast_id=$broadcastId"; 
                $result =mysqli_query($conn, $sqlNotifyUpdate);
            }else{
            }
          }
          

    }else{
        if (isset($_GET['category'])) {
            $viewingCategories = true;
            $categoryId = (int)mysqli_real_escape_string($conn, $_GET['category']);
            $sql = 'SELECT b.id, b.title, b.description, b.thumbnail, b.category_id, c.name as category, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id where b.category_id=' . $categoryId . ';';
            $sqlCategoryThumbnail = 'SELECT thumbnail as category_thumbnail from category WHERE id=' . $categoryId;
            if($result = $conn->query($sqlCategoryThumbnail)){
                if($result->num_rows == 1){
                    $row = $result->fetch_assoc();
                    $categoryThumbnail = $row['category_thumbnail'];
                }
            }
        } else {
            $sql = 'SELECT b.id, b.title, b.description, b.thumbnail, b.category_id, c.name as category, b.location, b.gender_representation, b.starts_at, b.ends_at FROM broadcast b INNER JOIN category c on b.category_id = c.id;';
        }
        $viewingBroadcasts = true;
    }
?>

<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= "/fun-olympics/config.php";

    $query ="SELECT * FROM category";
    $result = $conn->query($query);

    if($result->num_rows> 0){
      $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
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
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" type="text/css" href="/fun-olympics/css/style.css">
    </head>
    <body>
        <?php require "../component/header.php" ?>
        <div class="contain-main">
            <?php require "../component/sidebar.php" ?>
            <div class="box">
                <div>
                    <?php
                        if($viewingBroadcasts){
                            echo '<div class="contain-title" style="padding-bottom: 24px;">';
                            echo '<img width="100%" height="350px" style="object-fit:cover;object-position:center; border-radius:4px" src="';
                            if($categoryThumbnail==null){
                                echo "/fun-olympics/images/broadcast-hero.png";
                            }else{
                                echo "$categoryThumbnail";
                            }
                            echo '" alt="hero image"/>';
                            echo "<div style='display: flex; justify-content: flex-start; align-items:center; gap: 18px; margin-top: 18px;'>";
                            echo "<div style='display: flex;justify-items: center; align-items:center; gap: 4px; color: #333;'><iconify-icon style='padding-bottom: 2px;' icon='mdi:filter'></iconify-icon>Filters</div>";
                            echo "<a style='background-color: transparent; border: none; padding: 0; margin: 0;color: #333; font-weight: 400; text-decoration: underline;";
                            if(!activeLinkContains("category")) echo "color: #2c746b; font-weight:bold;";
                            echo "' href='/fun-olympics/home'>All</a>";

                            foreach ($options as $option) {
                                echo "<a style='background-color: transparent; border: none; padding: 0; margin: 0;color: #333; font-weight: 400; text-decoration: underline;";
                                if(activeLinkContains("category=" . (int)$option['id'])) echo "color: #2c746b; font-weight:bold;";
                                echo "' href='/fun-olympics/home?category={$option['id']}'>{$option['name']}</a>";
                            }
                            echo "</div>";
                            echo '</div>';

                                if($viewingCategories){
                                    if ($result = $conn->query($sql)){
                                        if ($result->num_rows > 0){
                                            echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">';
                                            $hasAtLeastOneBroadcast = false;
                                            while($row = $result->fetch_assoc()){
                                                $status = getStatus($row['starts_at'], $row['ends_at']);
                                                $broadcastId = (int)$row['id'];
                                                $query = "SELECT * from broadcast_notification where broadcast_id=" . $broadcastId . " and user_id=" . $userId . ";";
    
                                                if($status == 'ONGOING'){
                                                    $hasAtLeastOneBroadcast = true;
                                                    echo "<div style='display: grid; grid-template-rows: 200px 1fr;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='100%' style='object-fit: cover;border-radius: 12px 12px 0 0;margin: 2px' src='".$row['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $row['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #2c746b;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $row['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 80px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;color: #333;">' . $row['description'] . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/home?broadcast=' . $row['id'] . '" style="display: block; text-align: center;">Watch</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                            }
                                            echo "</div>";
                                            if($hasAtLeastOneBroadcast == false){
                                                echo "<p style='font-size: 18px; text-align: center; margin-top: 82px;'>No live broadcast with such category not found!</p>";
                                            }
                                        }else{
                                            echo "<p style='font-size: 18px; text-align: center;margin-top: 82px;'>Broadcast with such category not found!</p>";
                                        }
                                    }
                                }else{
                                    $broadcasts = array();
                                    $liveBroadcastLength = 0;
                                    if ($result = $conn->query($sql)){
                                        if ($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                $status = getStatus($row['starts_at'], $row['ends_at']);

                                                if($status == 'ONGOING'){
                                                    array_push($broadcasts, $row);
                                                }
                                            }

                                            $twoFeaturedBroadcast = array_slice($broadcasts, 0, 2);
                                            $rest = array_slice($broadcasts, 2);

                                            echo '<h1 style="color: #2c746b;margin-top: 12px;padding-bottom: 12px;">Featured Broadcasts</h1>';
                                            echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">';
                                            foreach($twoFeaturedBroadcast as $row){
                                                $status = getStatus($row['starts_at'], $row['ends_at']);
                                                $broadcastId = (int)$row['id'];
    
                                                if($status == 'ONGOING'){
                                                    $liveBroadcastLength = $liveBroadcastLength + 1;
                                                    echo "<div style='display: grid; grid-template-columns: 1fr 250px;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='256px' style='object-fit: cover;border-radius: 12px 0px 0px 12px;margin: 2px' src='".$row['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $row['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #2c746b;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $row['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 94px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 4;color: #333;">' . $row['description'] . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/home?broadcast=' . $row['id'] . '" style="display: block; text-align: center;">Watch</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                            }
                                            echo "</div>";                                            

                                            echo '<h1 style="color: #2c746b;margin-top: 42px;padding-bottom: 12px;">Live Broadcasts</h1>';
                                            echo '<div class="grid-container" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">';
                                            foreach($rest as $row){
                                                $status = getStatus($row['starts_at'], $row['ends_at']);
                                                $broadcastId = (int)$row['id'];
    
                                                if($status == 'ONGOING'){
                                                    $liveBroadcastLength = $liveBroadcastLength + 1;
                                                    echo "<div style='display: grid; grid-template-rows: 200px 1fr;border-radius: 12px; border: 2px solid rgba(0, 0, 0, 0.2); box-shadow: 6px 6px 20px 2px #888888;'>";
                                                    echo "<img width='100%' height='100%' style='object-fit: cover;border-radius: 12px 12px 0 0;margin: 2px' src='".$row['thumbnail']."' alt='image'/>";
                                                    echo '<div style="padding: 24px;max-height: 400px; overflow: hidden;">';
                                                    echo '<small style="text-transform: uppercase;letter-spacing: 1px; font-weight: 500;">'. $row['category'] .'</small>';
                                                    echo '<h3 style="padding-bottom: 12px;color: #2c746b;max-height: 30px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 1;">' . $row['title'] . '</h3>';
                                                    echo '<p style="margin-bottom: 18px; max-height: 80px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;color: #333;">' . $row['description'] . '</p>';
                                                    echo '<a class="btn-submit" href="/fun-olympics/home?broadcast=' . $row['id'] . '" style="display: block; text-align: center;">Watch</a>';
                                                    echo '</div>';
                                                    echo "</div>";
                                                }
                                            }
                                            echo "</div>";
                                            if($liveBroadcastLength == 0 && count($twoFeaturedBroadcast) == 0){
                                                echo "<p style='font-size: 18px; text-align: center; margin-top: 82px;'>No live broadcasts found!</p>";
                                            }
                                        }else{
                                            echo "<p style='font-size: 18px; text-align: center;margin-top: 82px;'>No live broadcasts found!</p>";
                                        }
                                    }
                                }
                        }else{
                            // Single Broadcast
                            if($result = $conn->query($sql)){
                                if($result->num_rows == 1){
                                    $row = $result->fetch_assoc();
                                    echo "<div>";
                                    echo '<img width="100%" height="350px" style="object-fit:cover;object-position:center; border-radius: 12px;" src="'. $row['thumbnail'] .'" alt="hero image"/>';
                                    echo '<h1 style="padding-block: 24px; display: flex; align-items: center; gap: 8px;">' . $row['title'];
                                    echo '<div style="font-weight: bold; border: 1px solid rgba(0, 0, 0, 0.2); display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 10px;padding-top: 4px;">LIVE</div>';
                                    echo '</h1>';
                                    echo "<div style='display:flex;'>";
                                    echo '<div style="flex: 9; position:relative;height:500px;overflow:hidden;margin-right: 12px;"> <iframe style="width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden" frameborder="0" type="text/html" src="'.$row['url'].'?autoplay=1" width="100%" height="100%" allowfullscreen title="Dailymotion Video Player" allow="autoplay"> </iframe> </div>';
                                    require './chat.php';
                                    echo "</div>";
                                    echo '<h3 style="padding-top: 16px;">Description</h3>';
                                    echo '<p>' . $row['description'] . '</p>';
                                    echo '</div>';
                                }
                            }
                        }
                    ?> 
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script src="js/chat.js"></script>
        <script src="js/signup.js"></script>
        <script src="js/contentmenu.js"></script>
        <script>
            window.onload = function() {
                var usernameInput = document.getElementById('username');
                var submitButton = document.getElementById('btn-submit');

                // Retrieve value from cookie with key "userName"
                var userNameCookie = $.cookie('userName');

                if (usernameInput && userNameCookie) {
                    // Add value to the input field
                    usernameInput.value = userNameCookie;

                    // Simulate a click event on the button
                    submitButton.click();
                }
            };
        </script>
    </body>
</html>