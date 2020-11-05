<?php

    $renterID = $ownerID = $propertyID = $rentPerMonth = $startDate = '';
    $errors = array('renterID' => '', 'ownerID'=>'', 'propertyID'=>'', 'rentPerMonth'=>'', 'startDate'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Rental Properties</h4>
        <form class="white" action="form_employers.php" method="POST">
            <label>Renter ID </label>
            <input type="text" name="renterID" value="<?php echo htmlspecialchars($renterID)?>">
            <div class="red-text"><?php echo $errors['renterID']; ?></div>
            <label>Owner ID</label>
            <input type="text" name="ownerID" value="<?php echo htmlspecialchars($ownerID)?>">
            <div class="red-text"><?php echo $errors['ownerID']; ?></div>
            <label>Property ID</label>
            <input type="text" name="propertyID" value="<?php echo htmlspecialchars($propertyID)?>">
            <div class="red-text"><?php echo $errors['propertyID']; ?></div>
            <label>Rent Per Month</label>
            <input type="text" name="rentPerMonth" value="<?php echo htmlspecialchars($rentPerMonth)?>">
            <div class="red-text"><?php echo $errors['rentPerMonth']; ?></div>
            <label>Start Date</label>
            <input type="text" name="startDate" value="<?php echo htmlspecialchars($startDate)?>">
            <div class="red-text"><?php echo $errors['startDate']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>