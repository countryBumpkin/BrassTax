<?php

    $TID = $date = $description = $taxCategory = $amount = $purpose = $employerAuthorized = '';
    $errors = array('TID' => '', 'date'=>'', 'description'=>'', 'taxCategory'=>'', 'amount'=>'' ,'purpose'=>''
                    , 'employerAuthorized'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Employer Expenses</h4>
        <form class="white" action="form_newuser.php" method="POST">
            <label>Tax Payer ID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Date</label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($description)?>">
            <div class="red-text"><?php echo $errors['description']; ?></div>
            <label>Tax Category</label>
            <input type="text" name="taxCategory" value="<?php echo htmlspecialchars($taxCategory)?>">
            <div class="red-text"><?php echo $errors['taxCategory']; ?></div>
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <label>Purpose</label>
            <input type="text" name="purpose" value="<?php echo htmlspecialchars($purpose)?>">
            <div class="red-text"><?php echo $errors['purpose']; ?></div>
            <label>Employer Authorized</label>
            <input type="text" name="employerAuthorized" value="<?php echo htmlspecialchars($employerAuthorized)?>">
            <div class="red-text"><?php echo $errors['employerAuthorized']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>