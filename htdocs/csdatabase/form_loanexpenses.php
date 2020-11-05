<?php

    $TID = $date = $taxYear = $description = $taxCategory = $amount = $LID = $loanNumber = $loanAmount = $remainingBalance = '';
    $errors = array('TID' => '', 'date' => '', 'taxYear'=>'', 'description'=>'', 'taxCategory'=>'', 'amount'=>''
                    , 'LID'=>'','loanNumber'=>'', 'loanAmount' => '', 'remainingBalance'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Loan Expenses</h4>
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
            <label>Lender ID</label>
            <input type="text" name="LID" value="<?php echo htmlspecialchars($LID)?>">
            <div class="red-text"><?php echo $errors['LID']; ?></div>
            <label>Loan Number</label>
            <input type="text" name="loanNumber" value="<?php echo htmlspecialchars($loanNumber)?>">
            <div class="red-text"><?php echo $errors['loanNumber']; ?></div>
            <label>Loan Amount</label>
            <input type="text" name="loanAmount" value="<?php echo htmlspecialchars($loanAmount)?>">
            <div class="red-text"><?php echo $errors['loanAmount']; ?></div>
            <label>Remaining Balance</label>
            <input type="text" name="remainingBalance" value="<?php echo htmlspecialchars($remainingBalance)?>">
            <div class="red-text"><?php echo $errors['remainingBalance']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>