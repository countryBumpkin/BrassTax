<?php

    $TID = $date = $taxYear = $amount = $taxWithheld = $EID = $retirementContributions = '';
    $errors = array('TID' => '', 'date'=>'', 'taxYear'=>'', 'amount'=>'', 'taxWithheld'=>'', 'EID'=>''
                    , 'retirementContributions'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Employment Earnings</h4>
        <form class="white" action="form_expense.php" method="POST">
            <label>Personal TID </label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Date </label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <label>Tax Year</label>
            <input type="text" name="taxYear" value="<?php echo htmlspecialchars($taxYear)?>">
            <div class="red-text"><?php echo $errors['taxYear']; ?></div>
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <label>Tax Withheld</label>
            <input type="text" name="taxWithheld" value="<?php echo htmlspecialchars($taxWithheld)?>">
            <div class="red-text"><?php echo $errors['taxWithheld']; ?></div>
            <label>Employer ID</label>
            <input type="text" name="EID" value="<?php echo htmlspecialchars($EID)?>">
            <div class="red-text"><?php echo $errors['EID']; ?></div>
            <label>Retirement Contributions</label>
            <input type="text" name="retirementContributions" value="<?php echo htmlspecialchars($retirementContributions)?>">
            <div class="red-text"><?php echo $errors['retirementContributions']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>