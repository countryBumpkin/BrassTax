<?php

    $SID = $address = $contactPerson = '';
    $errors = array('SID' => '', 'address'=>'', 'contactPerson'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Earnings</h4>
        <form class="white" action="form_expense.php" method="POST">
            <label>Source ID </label>
            <input type="text" name="SID" value="<?php echo htmlspecialchars($SID)?>">
            <div class="red-text"><?php echo $errors['SID']; ?></div>
            <label>Address </label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address)?>">
            <div class="red-text"><?php echo $errors['address']; ?></div>
            <label>Contact Person</label>
            <input type="text" name="contactPerson" value="<?php echo htmlspecialchars($contactPerson)?>">
            <div class="red-text"><?php echo $errors['contactPerson']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>