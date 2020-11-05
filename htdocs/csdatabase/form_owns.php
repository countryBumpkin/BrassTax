<?php

    $ownerID = $homeID ='';
    $errors = array('ownerID' => '', 'homeID'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Owns</h4>
        <form class="white" action="form_employers.php" method="POST">
            <label>Owner ID </label>
            <input type="text" name="ownerID" value="<?php echo htmlspecialchars($ownerID)?>">
            <div class="red-text"><?php echo $errors['ownerID']; ?></div>
            <label>Home ID</label>
            <input type="text" name="homeID" value="<?php echo htmlspecialchars($homeID)?>">
            <div class="red-text"><?php echo $errors['homeID']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>