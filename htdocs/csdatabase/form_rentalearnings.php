<?php

    $TID = $date = $taxYear = $homeID = $renterID = $amount = '';
    $errors = array('TID' => '', 'date'=>'', 'taxYear'=>'', 'homeID'=>'', 'renterID'=>'', 'amount'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Earnings</h4>
        <form class="white" action="form_expense.php" method="POST">
            <label>Your Personal TID </label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Date </label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <label>Tax Year</label>
            <input type="text" name="taxYear" value="<?php echo htmlspecialchars($taxYear)?>">
            <div class="red-text"><?php echo $errors['taxYear']; ?></div>
            <label>Home ID</label>
            <input type="text" name="homeID" value="<?php echo htmlspecialchars($homeID)?>">
            <div class="red-text"><?php echo $errors['homeID']; ?></div>
            <label>Renter ID</label>
            <input type="text" name="renterID" value="<?php echo htmlspecialchars($renterID)?>">
            <div class="red-text"><?php echo $errors['renterID']; ?></div>
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>