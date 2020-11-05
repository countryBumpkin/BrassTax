<?php

    $EmployerID = $TID = $annualSalary = $dateJoined ='';
    $errors = array('EmployerID' => '', 'TID'=>'', 'annualSalary'=>'', 'dateJoined'=>'');
    
    if(isset($_POST['submit'])){

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Works For</h4>
        <form class="white" action="form_employers.php" method="POST">
            <label>Employer ID </label>
            <input type="text" name="EmployerID" value="<?php echo htmlspecialchars($EmployerID)?>">
            <div class="red-text"><?php echo $errors['EmployerID']; ?></div>
            <label>TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Annual Sallary</label>
            <input type="text" name="annualSalary" value="<?php echo htmlspecialchars($annualSalary)?>">
            <div class="red-text"><?php echo $errors['annualSalary']; ?></div>
            <label>Date Joined</label>
            <input type="text" name="dateJoined" value="<?php echo htmlspecialchars($dateJoined)?>">
            <div class="red-text"><?php echo $errors['dateJoined']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>