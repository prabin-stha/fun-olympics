<?php
    session_start();
    if(!$_SESSION['SESSION_ADMIN']){
        header("Location: /fun-olympics/home");
        die();
    }

    $id = $title = $description = $category = $createdAt = $categoryId = $author = $readTime = $thumbnail = "";
    $titleError = $descriptionError = $categoryError = $createdAtError = $authorError = $readTimeError = $thumbnailError = "";
    
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        require "../config.php";

        //prepare an sql statement
        $sql = "SELECT n.id, n.title, n.description, n.created_at, c.id as category_id, c.name as category, n.author, n.thumbnail, n.read_time FROM news n inner join category c on c.id = n.category_id WHERE n.id = ?";

        if($statement = $conn->prepare($sql)){
            $statement->bind_param("i", $c_id);
        }

        $id = $_GET['id'];
        $c_id = trim($id);

        if($statement->execute()){
            $result = $statement->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $modifiedDesc = str_replace("'", "\\'", $row["description"]);

                $id = $row["id"];
                $title = $row["title"];
                $description = $modifiedDesc;
                $category = $row["category"];
                $createdAt = $row["created_at"];
                $categoryId = $row["category_id"];
                $author = $row["author"];
                $thumbnail = $row["thumbnail"];
                $readTime = $row["read_time"];
            }
            $statement->close();
        }
        $conn->close();

    }

    // post
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = trim($_POST['id']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = (int)trim($_POST['category']);
        $createdAt = trim($_POST['createdAt']);
        $author = trim($_POST['author']);
        $thumbnail = trim($_POST['thumbnail']);
        $readTime = trim($_POST['readTime']);

        if ($title == '') {
            $titleError = 'Title field is required!';
        }

        if ($description == '') {
            $descriptionError = 'Description field is required!';
        }

        if ($category == '') {
            $categoryError = 'Category field is required!';
        }

        if ($createdAt == '') {
            $createdAtError = 'Created at field is required!';
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

        if ($titleError == "" && $descriptionError == "" && $categoryError == "" && $createdAtError == "" && $authorError == "" && $readTimeError == "" && $thumbnailError == "") {
            require "../config.php";

            $modifiedDescription = str_replace("'", "\\'", $description);

            $sql = "UPDATE news SET title='$title', description='$modifiedDescription', category_id=$category, created_at='$createdAt', author='$author', read_time='$readTime', thumbnail='$thumbnail' WHERE id = $id";
            $result =mysqli_query($conn, $sql);

            if($result){
                header("location: news-d.php");
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
                            <a style="height: 28px; width: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background-color: #225c55; color: white;" href="news-d.php">
                                <i class="fa fa-arrow-left" style="margin-top: 3px;"></i>
                            </a>
                            <h1 style="color: #225c55;">Update News Details</h1>
                        </div>
                        <p style="color: #333">Elevate your news game with our News Dashboard. Create, edit, view, and delete news articles seamlessly. Stay informed, tell compelling stories, and make a lasting impact. Welcome to the world of dynamic news management.</p>
                    </div>
                    <img height="350px" src="/fun-olympics/images/user-management.png" />
                </div>
                <div class="form-container">
                    <form action="edit.php" method="POST">
                        <div class="form-group">
                            <label for="id">Id</label>
                            <div class="in-box">
                                <input style="border: none;" type="text" name="id" class="form-control" value="<?= $id; ?>">
                            </div>
                            
                        </div>

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
                                    <?php echo "<option value={$option['id']}" ?> <?php if($categoryId==$option['id']) echo"selected" ?>
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

                        <div class="form-group" style="padding-bottom: 16px;">
                            <label for='createdAt'>Created At</label>
                            <div class="in-box">
                                <input type="datetime-local" value="<?= $createdAt; ?>" name="createdAt" class="form-control" >
                                <p class="text-danger-edit"> <?= $createdAtError ?> </p>
                            </div>
                        </div>
                        
                        <input type="Submit" value="Update" class="btn-submit">
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