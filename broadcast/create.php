<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }

    $title = $description = $category = $location = $genderRepresentation = $url = $startsAt = $endsAt = $thumbnail = "";
    $titleError = $descriptionError = $categoryError = $locationError = $genderRepresentationError = $urlError = $startsAtError = $endsAtError = $thumbnailError = "";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = (int)trim($_POST['category']);
        $location = trim($_POST['location']);
        $genderRepresentation = trim($_POST['gender_representation']);
        $url = trim($_POST['url']);
        $startsAt = trim($_POST['starts_at']);
        $endsAt = trim($_POST['ends_at']);
        $thumbnail = trim($_POST['thumbnail']);

        if ($title == '') {
            $titleError = 'Title field is required!';
        }

        if ($description == '') {
            $descriptionError = 'Description field is required!';
        }

        if ($category == '') {
            $categoryError = 'Category field is required!';
        }

        if ($location == '') {
            $locationError = 'Location field is required!';
        }

        if ($genderRepresentation == '') {
            $genderRepresentationError = 'Gender Representation field is required!';
        }
    
        if ($url == '') {
            $urlError = 'Url field is required!';
        } else{
            if(!filter_var($url, FILTER_VALIDATE_URL)){
                $urlError = 'Invalid url format!';
            }
        }

        if ($startsAt == '') {
            $startsAtError = 'Starts at field is required!';
        }

        if ($endsAt == '') {
            $endsAtError = 'Ends at field is required!';
        }

        if ($thumbnail == '') {
            $thumbnailError = 'Thumbnail field is required!';
        }

        if ($titleError == "" && $descriptionError == "" && $urlError == "" && $startsAtError == "" && $endsAtError == "" && $thumbnailError == "" && $genderRepresentationError == "" && $locationError == "" && $categoryError == "") {
            require "../config.php";

            $modifiedDesc = str_replace("'", "\\'", $description);

            $sql = "INSERT INTO broadcast(title, description, category_id, location, gender_representation, url, starts_at, ends_at, thumbnail) VALUES ('{$title}', '{$modifiedDesc}', '{$category}', '{$location}', '{$genderRepresentation}', '{$url}', '{$startsAt}', '{$endsAt}', '{$thumbnail}')";
            $result = mysqli_query($conn, $sql);

            if($result){
                header("location: broadcast-d.php");
                $conn->close();
            }else{
                echo "Error Inserting record" . mysqli_error($conn);
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
        <title>title-placeholder</title>
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="broadcast-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">Create Broadcast</h1>
                        </div>
                        <p style="color: #333">Unleash your broadcasting prowess with our Broadcast Dashboard. Create, edit, view, and delete captivating shows effortlessly. Take control, captivate your audience, and make every broadcast count. Welcome to seamless broadcasting management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/broadcast-management-hero.png" />
                </div>
                <div class="form-container">
                    <form action="create.php" method="POST">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <div class="in-box">
                                <input type="text" name="title" class="form-control" value="<?= $title; ?>">
                                <p class="text-danger-edit"> <?= $titleError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <div class="in-box">
                                <textarea name="description" class="form-control" rows="6"><?= $description; ?></textarea>
                                <p class="text-danger-edit"> <?= $descriptionError ?> </p>
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
                            <label for="location">Location</label>
                            <div class="in-box">
                                <input type="text" name="location" class="form-control" value="<?= $location; ?>">
                                <p class="text-danger-edit"> <?= $locationError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gender_representation">Gender Representation</label>
                            <div class="in-box">
                                <select name="gender_representation" class="form-control">
                                    <option value=""></option>
                                    <option value="Male"  <?php if($genderRepresentation=="Male") echo"selected" ?>>Male</option>
                                    <option value="Female" <?php if($genderRepresentation=="Female") echo"selected" ?>>Female</option>
                                    <option value="Hybrid" <?php if($genderRepresentation=="Hybrid") echo"selected" ?>>Hybrid</option>
                                </select>
                                <p class="text-danger-edit"> <?= $genderRepresentationError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for='url'>Url</label>
                            <div class="in-box">
                                <input id='urlInput' type="text" name="url" value="<?= $url; ?>" class="form-control">
                                <p class="text-danger-edit"> <?= $urlError ?> </p>
                            </div>
                        </div>

                        <div id='urlContainer' style="padding-bottom: 16px; display: none;">
                            <p style="font-size: 12px; padding-bottom: 6px;">Preview Video</p>
                            <iframe id='url' style="" frameborder="0" type="text/html" src="" width="100%" height="300px" allowfullscreen allow="autoplay"></iframe>
                        </div>

                        <div class="form-group">
                            <label for='thumbnail'>Thumbnail</label>
                            <div class="in-box">
                                <input id="thumbnailInput" type="text" name="thumbnail" value="<?= $thumbnail; ?>" class="form-control">
                                <p class="text-danger-edit"> <?= $thumbnailError ?> </p>
                            </div>
                        </div>

                        <div id='thumbnailContainer' style="padding-bottom: 16px; display: none;">
                            <p style="font-size: 12px; padding-bottom: 6px;">Preview Thumbnail</p>
                            <img id='thumbnail' src="" width="100%" height='300px' style="object-fit: cover;" /> 
                        </div>

                        <div class="form-group">
                            <label for='starts_at'>Starts At</label>
                            <div class="in-box">
                                <input type="datetime-local" value="<?= $startsAt; ?>" name="starts_at" class="form-control" >
                                <p class="text-danger-edit"> <?= $startsAtError ?> </p>
                            </div>
                        </div>

                        <div class="form-group" style="padding-bottom: 16px;">
                            <label for='ends_at'>Ends At</label>
                            <div class="in-box">
                                <input type="datetime-local" value="<?= $endsAt; ?>" name="ends_at" class="form-control" >
                                <p class="text-danger-edit"> <?= $endsAtError ?> </p>
                            </div>
                        </div>

                        <input type="Submit" value="Create" class="btn-submit">
                    </form>     
                </div>
            </div>
        </div>
        <?php require "../component/footer.php" ?>
    </body>

    <script>
        const thumbnailContainer = document.getElementById('thumbnailContainer');
        const thumbnailInput = document.getElementById('thumbnailInput');
        const thumbnail = document.getElementById('thumbnail');

        if(thumbnailInput.value){
            thumbnailContainer.style.display = 'block';
            thumbnail.src = thumbnailInput.value;
        }

        const onThumbnailBlur = (event) => {
            console.log('blurred');
            if(event.target.value){
                thumbnailContainer.style.display = 'block';
            }else{
                thumbnailContainer.style.display = 'none';
            }
            thumbnail.src = event.target.value;
        };
        thumbnailInput.addEventListener("blur", onThumbnailBlur);
    </script>

    <script>
        const urlContainer = document.getElementById('urlContainer');
        const urlInput = document.getElementById('urlInput');
        const url = document.getElementById('url');

        if(urlInput.value){
            urlContainer.style.display = 'block';
            url.src = urlInput.value;
        }

        const onUrlBlur = (event) => {
            console.log('blurred');
            if(event.target.value){
                urlContainer.style.display = 'block';
            }else{
                urlContainer.style.display = 'none';
            }
            url.src = event.target.value;
        };
        urlInput.addEventListener("blur", onUrlBlur);
    </script>
</html>