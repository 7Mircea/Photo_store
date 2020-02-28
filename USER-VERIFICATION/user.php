<?php require_once 'controller/authController.php';

if(!isset($_SESSION['id'])) {
  header('location: login.php');
  exit();
}

//inseram imaginea
if (isset($_POST['insert']) && $_FILES["image"]["tmp_name"] != '') { //daca a fost apasat pe butonul de inserare dar
  // a si fost introdusa o poza
  $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));
  echo '<script>alert($file)</script>';
  $photo_time = date("Y-m-d H:i:s");
  //adaugam imaginea in tabel tbl_images
  $query = "INSERT INTO tbl_images(name, date) VALUES ('$file', '$photo_time');";
  if (mysqli_query($conn,$query)) {
    echo '<script>alert("Image Inserted into Database")</script>';
  }

  $id_image = $conn->insert_id;
  $query = "INSERT INTO user_image(id_user, id_image, like_btn) VALUES ({$_SESSION['id']}, $id_image, false);";
  $result = $conn->query($query);
  if (!$result) {
    echo 'eroare la inserarea in user_image rand 22(?) din user.php';
    echo $conn->error;
  }
  unset($_POST['insert']);
}

// extragem id-urile pozelor
$query = "SELECT id FROM tbl_images;";
$result = $conn->query($query);
$ids = array();
$i = 0;
while ($row = $result->fetch_assoc()) {
  $ids[$i] = $row['id'];
  ++$i;
}

/* functia getIDComments are rolul de a obtine idurile asociate
* imaginii cu id-ul id_image
*/
function getIDComments($id_image) {
  global $conn;
  $id_comments = array();
  $query = "SELECT id FROM comments C INNER JOIN user_image UI ON C.id_user_image == UI.id
  WHERE UI.id_image=$id_image";
  $result = $conn->query($query);
  $i = 0;
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $id_comments[$i] = $row['id'];
      ++$i;
    }
  }
  return $id_comments;
}

// functia getIDUserImage returneaza id-ul unei inregistrari din tabela id_user_image
// pe baza id_user si id_image
function getIDUserImage($id_user,$id_image) {
  global $conn;
  $query = "SELECT id FROM user_image WHERE id_user=$id_user AND id_image=$id_image;";
  $result= $conn->query($query);
  if ($result !== false) {
    $row = $result->fetch_assoc();
    return $row['id'];
  } else {
    echo 'eroare';
    return -1;
  }
  return -1;
}

function getIDImages() {
  global $conn;
  $query = "SELECT id FROM tbl_images;";
  $result = $conn->query($query);
  $ids_images = array();
  $i = 0;
  if ($result) {
    while($row = $result->fetch_assoc()) {
      $ids_images[$i++] = $row['id'];
    }
  }
  return $ids_images;
}

function getIDUsers() {
  global $conn;
  $query = "SELECT id FROM users;";
  $result = $conn->query($query);
  $ids_users = array();
  $i = 0;
  if ($result) {
    while($row = $result->fetch_assoc()) {
      $ids_users[$i++] = $row['id'];
    }
  }
  return $ids_users;
}

// daca exista o inregistrare cu $id_image si $id_user nu creaza inca una
function setUserImage($id_image, $id_user) {
  global $conn;
  $query = "UPDATE user_image SET like_btn = 0 WHERE id_image=$id_image AND id_user=$id_user";
  $conn->query($query);
  if ($conn->affected_rows == 0) {
    $query = "INSERT INTO user_image(id_user, id_image, like_btn) VALUES( $id_user, $id_image, 0)";
  }
}

// functia setAllUserImage are rolul de a asigura ca pentru fiecare utilizator
// si fiecare poza exista o inregistrare in id_user_image
// Acest lucru nu poate fi facut la salvarea unei imagini in baza de date
// deoarece se pot crea utilizari si dupa.
function setAllUserImage() {
  $id_images = array();
  $id_users = array();
  $id_images = getIDImages();
  $id_users = getIDUsers();
  foreach ($id_images as $id_image) {
    foreach ($id_users as $id_user) {
      setUserImage($id_image, $id_user);
    }
  }
}

setAllUserImage();

//modificam like si comentariu
foreach ($ids as $id) { //pentru fiecare poza
  if (isset($_POST["like_btn$id"])) {
    $query = "SELECT * FROM user_image WHERE id_image=".$id." AND id_user={$_SESSION['id']}";
    $result = $conn->query($query);
    if ($result != false && $conn->affected_rows > 0) { // daca sintaxa e corect si daca avem legatura dintre utilizator si poza
      $row = $result->fetch_assoc();
      //echo $row['like_btn'];
      $value = !($row['like_btn']);
      $value = $value ? 1 : 0;
      $query_2 = "UPDATE user_image SET like_btn=$value WHERE id_image='$id' AND id_user={$_SESSION['id']}";
      //echo $query_2;
      if (!$conn->query($query_2)) { // daca nu reusim inserarea
        echo $id.'eroare la actualizare like rand 84(?) in user.php';
        echo $conn->error;
      }
    } else {
      $query = "INSERT INTO user_image(id_user, id_image, like_btn) VALUES ({$_SESSION['id']}, $id, 1)";
      $result = $conn->query($query);
      if (!$result) {
        echo "eroare in user.php rand 94(?)";
        echo $conn->error;
      }
    }
  }
  if (isset($_POST["comment$id"])) { //daca a fost postat un comentariu
    $id_comments = getIDComments($id);
    $id_user_image = getIDUserImage($_SESSION['id'],$id);
    echo $id_user_image, $_POST["comment$id"];
    $query = "INSERT INTO comments(id_user_image, comment)
    VALUES ($id_user_image, '{$_POST["comment$id"]}');";
    //echo $query;
    if (!$conn->query($query)) {
      echo 'eroare la inserarea in comments rand 95(?) in user.php';
      echo $conn->error();
    }
  }
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Utilizator <?php echo $_SESSION['user_checked'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" ><!--pt container-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" async></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" async></script>
  </head>
  <body>

    <div class="container">
      <div class="form-div">
        <p class="text-center"><?php echo $_SESSION['user_checked']; ?></p>
        <!-- Intoarcere pentru alegere alt utilizator -->
        <a class="text-center" href="users.php">Alege alt utilizator</a>
        <br>
        <!-- Logout -->
        <a href="user.php?logout=1" class="logout">Logout</a>
      </div>
    </div>




    <!-- inserare imagini in baza de date -->
    <?php if ($_SESSION['username'] === $_SESSION['user_checked']): ?>
      <div class="container" style="width: 500px;">
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="image" id="image"/>
          <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-primary"/>
        </form>
        <br>
        <br>

      </div>
    <?php endif; ?>

    <!-- afisare imagini -->
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <?php
            $query = '
            SELECT T.id, T.name FROM tbl_images T INNER JOIN user_image UI ON T.id = UI.id_image
            WHERE T.id IN (SELECT id_image FROM user_image WHERE id_user IN
            (SELECT id FROM users WHERE username=\''.$_SESSION['user_checked'].'\'))
            GROUP BY T.id
            ORDER BY SUM(UI.like_btn) DESC, T.date DESC
';

            $result = mysqli_query($conn, $query);
            $i = 0;
            if ($result) {
              while($row = mysqli_fetch_array($result)) {
                echo "
                      <div class=\"form-div\">
                      <img src=\"data:image/jpeg;base64,".base64_encode($row['name'])."\" style=\"max-width: 100%;\"/>
                      <form method=\"post\">
                        <button onclick=\"myFunction(this)\" class=\"fa fa-thumbs-up\" name=\"like_btn".$row['id']."\"></button>
                      </form>
                      <form method=\"post\">
                        <input type=\"text\" name=\"comment".$row['id']."\" placeholder=\"Comentariul meu :)\" size=\"100%\" />
                        <br>
                      </form>
                      </div>
                ";

              }
            }
           ?>
           <br>
           <br>
           Aceste imagini sunt preluate de pe Wikipedia sub următoare licență <a href="https://creativecommons.org/licenses/by/2.0/">CC BY 2.0</a>
           <br>
           <br>
        </div>
      </div>
   </div>
   <script>
   		function myFunction(x) {
   			x.classList.toggle("mu");
   		}
   </script>
  </body>
</html>
