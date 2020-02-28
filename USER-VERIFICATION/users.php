<?php require_once 'controller/authController.php';

if(!isset($_SESSION['id'])) {
  header('location: login.php');
  exit();
}

if (isset($_POST['submit_user'])) {
  $_SESSION['user_checked'] = $_POST['user'];
  header('location: user.php');
  exit();
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Utilizatori</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
      <div class="form-div">
        <?php
        // selectam utilizatorii din baza de date
        $query = "SELECT username FROM users;";
        $result = mysqli_query($conn, $query);
         ?>
         <div class="col-md-4 offset-md-4">
           <!-- afisam utilizatorii din baza de date -->
           <form action="users.php" method="post">
             <select name="user">
               <?php if ($result->num_rows > 0): ?>
                 <?php while ($row = $result->fetch_assoc()): ?>
                   <option value="<?php echo $row['username']; ?>"> <?php echo $row['username']; ?> </option>
                 <?php endwhile; ?>
               <?php endif; ?>
             </select>
             <br>
             <button name="submit_user" type="submit" class="btn btn-primary">Alege</button>
           </form>
         </div>
       </div
    </div>
  </body>
</html>
