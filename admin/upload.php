<?php
// from https://www.w3schools.com/php/php_file_upload.asp
$target_dir = "../images/";
$fileName = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $fileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// if no image exists and no image is uploaded then user must submit a file
if ($fileName == "" && $existingPicture == "") {
  print '<p class="mistake">You must submit an image for a new record.</p>';
  $uploadOk = 0;
}
// if an image already exists, then keep upload okay set to 1 but don't do anything further
else if ($fileName == "" and $existingPicture != "") {}
// if an image is uploaded, regardless of if one already exists, try to upload it
else {
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } 
    else {
      print '<p class="mistake">File is not an image.</p>';
      $uploadOk = 0;
    }
  }

    // Check if file already exists
    if (file_exists($target_file)) {
      print '<p class="mistake">File of that name already exists in directory.</p>';
      $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
      print '<p class="mistake">File Size is too large</p>';
      $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
      print '<p class="mistake">Only JPG, JPEG, or PNG files allowed.</p>';
      $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      print '<p class="mistake">Sorry, form was not submitted because file was invalid.</p>';
    // if everything is ok, try to upload file
    } 
    else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {} 
      else {
          print '<p class="mistake">Sorry, form was not submitted because file was invalid.</p>';
          $uploadOk = 0;
      }
    }
}
?>