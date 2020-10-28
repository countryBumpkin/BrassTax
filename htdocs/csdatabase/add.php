<?php

    $ID = $title = $amount = '';
    $errors = array('ID' => '', 'title'=>'', 'amount'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['ID'])){
            $errors['ID'] = 'A \'Personal ID Number\' is required <br />';
        }
        else{
            $ID = $_POST['ID'];
            if(!preg_match('/^[0-9]{5}$/', $ID)){ //{#} is the length it will match
                $errors['ID'] = 'ID must only be numbers with a length of 20 <br />';
            }
            //echo htmlspecialchars($_POST['ID']);
        }

        if(empty($_POST['title'])){
            $errors['title'] = 'An \'Expense Title\' is required <br />';
        }
        else{
            $title = $_POST['title'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $title)){
                $errors['title'] = 'Title must only contain letters, numbers, and spaces <br />';
            }
        }

        if(empty($_POST['amount'])){
            $errors['amount'] = 'At least one \'Amount\' is required <br />';
        }
        else{
            $amount = $_POST['amount'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1};)*$/', $amount)){
                $errors['amount'] = 'Amount must contain at least one number followed by a semicolon';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{
            //redirect user to main page
            header('Location: index.php');
        }

    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Add Expense</h4>
        <form class="white" action="add.php" method="POST">
            <label>Your Personal ID </label>
            <input type="text" name="ID" value="<?php echo htmlspecialchars($ID)?>">
            <div class="red-text"><?php echo $errors['ID']; ?></div>
            <label>Expese Title </label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title)?>">
            <div class="red-text"><?php echo $errors['title']; ?></div>
            <label>Amounts (Each number must end with ; ) </label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn brand z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>