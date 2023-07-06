<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }

    $title = $description = $category = $author = $readTime = $thumbnail = "";
    $titleError = $descriptionError = $categoryError = $authorError = $readTimeError = $thumbnailError = "";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = trim($_POST['category']);
        $author = trim($_POST['author']);
        $readTime = trim($_POST['readTime']);
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

        if ($author == '') {
            $authorError = 'Author field is required!';
        }

        if ($readTime == '') {
            $readTimeError = 'Read time field is required!';
        }

        if ($thumbnail == '') {
            $thumbnailError = 'Thumbnail field is required!';
        }

        if ($titleError == "" && $descriptionError == "" && $categoryError == "" && $authorError == "" && $readTimeError == "" && $thumbnailError == "") {
            require "../config.php";

            $modifiedDesc = str_replace("'", "\\'", $description);

            $sql = "INSERT INTO news (title, description, category_id, created_at, author, read_time, thumbnail) VALUES ('{$title}', '{$modifiedDesc}', '{$category}', NOW(), '{$author}' , '{$readTime}' , '{$thumbnail}')";
            $result = mysqli_query($conn, $sql);

            if($result){
                header("location: news-d.php");
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="news-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">Create News</h1>
                        </div>
                        <p style="color: #333">Elevate your news game with our News Dashboard. Create, edit, view, and delete news articles seamlessly. Stay informed, tell compelling stories, and make a lasting impact. Welcome to the world of dynamic news management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/user-management.png" />
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
                                <input type="text" name="description" class="form-control" value="<?= $description; ?>">
                                <p class="text-danger-edit"> <?= $descriptionError ?> </p>
                            </div>
                        </div>

                        <div class="form-group" style="padding-bottom: 16px;">
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
                            <label for="author">Author</label>
                            <div class="in-box">
                                <input type="text" name="author" class="form-control" value="<?= $author; ?>">
                                <p class="text-danger-edit"> <?= $authorError ?> </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            <div class="in-box">
                                <input id="thumbnailInput" type="text" name="thumbnail" class="form-control" value="<?= $thumbnail; ?>">
                                <p class="text-danger-edit"> <?= $thumbnailError ?> </p>
                            </div>
                        </div>

                        <div id='thumbnailContainer' style="padding-bottom: 16px; display: none;">
                            <p style="font-size: 12px; padding-bottom: 6px;">Preview Thumbnail</p>
                            <img id='thumbnail' src="" width="100%" height='300px' style="object-fit: cover;" /> 
                        </div>

                        <div class="form-group">
                            <label for="readTime">Read Time</label>
                            <div class="in-box">
                                <input type="text" name="readTime" class="form-control" value="<?= $readTime; ?>">
                                <p class="text-danger-edit"> <?= $readTimeError ?> </p>
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
</html>