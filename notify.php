<?php
    if(isset($_SESSION['SESSION_ID'])){
        $userIdNotify = (int)$_SESSION['SESSION_ID'];
        $sqlNotify = "SELECT c.name as category, bn.id as notification_id, b.title, b.thumbnail, b.id, b.starts_at from broadcast_notification bn left join broadcast b on bn.broadcast_id = b.id inner join category c on b.category_id=c.id where bn.user_id=$userIdNotify AND bn.notify=1 AND bn.mark_as_read=0 AND b.starts_at < NOW()";
    }

    if(isset($_POST['mark_as_read'])) {
        $configPath = $_SERVER['DOCUMENT_ROOT'];
        $configPath .= "/fun-olympics/config.php";

        include $configPath;

        $notificationIds = array();
        $userID = (int)$_SESSION['SESSION_ID'];

        if ($result = $conn->query("SELECT bn.id as notification_id, b.title, b.thumbnail, b.id, b.starts_at from broadcast_notification bn left join broadcast b on bn.broadcast_id = b.id where b.starts_at < NOW() AND bn.user_id=$userID AND bn.notify=1 AND bn.mark_as_read=0")){
          if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
              array_push($notificationIds, (int)$row['notification_id']);
            }
          }
        }

        foreach($notificationIds as $id){
          $sqlNotifyUpdate = "UPDATE broadcast_notification SET notify='0', mark_as_read='0' where id=$id"; 
          $result =mysqli_query($conn, $sqlNotifyUpdate);
          
          if($result){
          }else{
            mysqli_error($conn);
          }
        }
    }
?>

<style>
.notification{
  position: relative;
  display: inline-block;
}

.number{
  height: 22px;
  width:  22px;
  background-color: #d63031;
  border-radius: 20px;
  color: white;
  text-align: center;
  position: absolute;
  top: -4px;
  left: 52px;
  padding-top: 2px;
  border-style: solid;
  border-width: 2px;
  font-size: 12px;
}

.number:empty {
   display: none;
}

.notBtn{
  transition: 0.5s;
  cursor: pointer
}

.fas{
  font-size: 25pt;
  padding-bottom: 10px;
  color: black;
  margin-right: 40px;
  margin-left: 40px;
}

.box-container{
  width: 400px;
  height: 0px;
  border-radius: 10px;
  transition: 0.5s;
  position: absolute;
  overflow-y: scroll;
  padding: 0px;
  left: -300px;
  margin-top: 5px;
  background-color: #F4F4F4;
  -webkit-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.2);
  -moz-box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  box-shadow: 10px 10px 23px 0px rgba(0,0,0,0.1);
  cursor: context-menu;
}

.notBtn:hover > .box-container{
  height: 40vh;
}

.content{
  padding: 20px;
  color: black;
  vertical-align: middle;
  text-align: left;
}

.top{
  color: black;
  padding: 10px
}

.display{
  position: relative;
}

.cont{
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: #F4F4F4;
}

.cont:empty{
  display: none;
}

.stick{
  text-align: center;  
  display: block;
  font-size: 50pt;
  padding-top: 70px;
  padding-left: 80px
}

.stick:hover{
  color: black;
}

.cent{
  text-align: center;
  display: block;
}

.sec{
  padding: 12px 10px;
  background-color: #F4F4F4;
  transition: 0.5s;
}

.sub{
  font-size: 1rem;
  color: grey;
}

.new{
  border-style: none none solid none;
  border-color: rgba(0, 0, 0, 0.5);
}

.sec:hover{
  background-color: #BFBFBF;
}
</style>

<div class = "notification">
  <a href = "#">
    <div class = "notBtn" href = "#">
      <?php
      $configPath = $_SERVER['DOCUMENT_ROOT'];
      $configPath .= "/fun-olympics/config.php";

      include $configPath;
        if ($result = $conn->query($sqlNotify)){
          if ($result->num_rows > 0){
            echo '<div class = "number">'.$result->num_rows.'</div>';
            echo '<i class="fas fa-bell" style="font-size: 24px; padding: 0;"></i>';
            echo '<div class = "box-container">';
            echo '<div class = "display">';
            echo '<div class = "cont">';
            echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid rgba(0, 0, 0, 0.2)">';
            echo '<p style="font-weight: bold; font-size: 24px; color: #333;">Notifications</p>';
            echo '<form method="post" style="display: flex; justify-content: flex-end;">';
            echo '<input style="border:none;text-decoration: underline; color:#2c746b; cursor: pointer;" type="submit" name="mark_as_read" value="Mark all as read?"/>';
            echo '</form>';
            echo '</div>';
            while($row = $result->fetch_assoc()){
              $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['starts_at']);
              $formattedDate = $date->format('M d, Y h:i A');

              $_SESSION["SESSION_CURRENT_BROADCAST"] = $row['title'];
              echo '<div class = "sec new">';
              echo '<a href = "/fun-olympics/home?broadcast='.$row['id'].'">';
              echo '<p style="font-size: 18px; color: #222; font-weight: bold;max-height: 64px; overflow: hidden;display: -webkit-box; -webkit-box-orient: vertical;-webkit-line-clamp: 2;">'.$row['title'].'</p>';
              echo '<p style="font-size: 12px; color: #333; font-weight: bold; text-transform: uppercase;">'.$row['category'].'</p>';
              echo '<p style="font-size: 14px; color: #333;">'.$formattedDate.'</p>';
              echo '</a>';
              echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }else{
            echo '<div class = "number"></div>';
            echo '<i class="fas fa-bell" style="font-size: 24px; padding: 0;"></i>';
            echo '<div class = "box-container">';
            echo '<div class = "display">';
            echo '<div class = "nothing">'; 
            echo '<i class="fas fa-comment-alt-slash stick" style="margin-top: 32px; margin-right: 120px;"></i>'; 
            echo '<div class = "cent" style="color: #333; padding-top: 18px;">You\'ve unlocked the vault of complete awareness!</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }
        }
      ?>
    </div>
  </a>
</div>


<div class = "box-container">
  <div class = "display">
    <div class = "nothing"> 
      <i class="fas fa-child stick"></i> 
      <div class = "cent">Looks Like your all caught up!</div>
    </div>
  </div>
</div>