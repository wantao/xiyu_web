<?php
if ($_FILES["up_file"]["size"] < 20000)
  {
  if ($_FILES["up_file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["up_file"]["error"] . "<br />";
    }
  else
    {
    echo "Upload: " . $_FILES["up_file"]["name"] . "<br />";
    echo "Type: " . $_FILES["up_file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["up_file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["up_file"]["tmp_name"] . "<br />";

    if (file_exists("upload/" . $_FILES["up_file"]["name"]))
      {
      echo $_FILES["up_file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["up_file"]["tmp_name"],
      "upload/" . $_FILES["up_file"]["name"]);
      echo "Stored in: " . "upload/" . $_FILES["up_file"]["name"];
      }
    }
  }
else
  {
  echo "Invalid file";
  }
?>