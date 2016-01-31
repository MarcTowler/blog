<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

if(isset($_POST['submit']))
{
    $count = count($_FILES['upload']['name']);

    if($count > 0)
    {
        for($i=0; $i < $count; $i++)
        {
            $tmp = $_FILES['upload']['tmp_name'][$i];

            if($tmp != "")
            {
                $shortname = $_FILES['upload']['name'][$i];

                $path = "../img/" . $_FILES['upload']['name'][$i];

                if(move_uploaded_file($tmp, $path))
                {
                    $files[] = $shortname;
                }
            }
        }
    }
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Admin - Add Category</title>
        <link rel="stylesheet" href="../css/normalize.css">
        <link rel="stylesheet" href="../css/main.css">
    </head>
<body>

<div id="wrapper">

<?php include('menu.php');?>

    <h2>Add new image:</h2>

    <form action="" enctype="multipart/form-data" method="post">
        Add Images:
        <input name="upload[]" type="file" multiple="multiple" />
        <br />
        <input type="submit" name="submit" value="Submit">
    </form>

<?php
if ($handle = opendir('../img')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != ".." && $entry != ".gitignore") {

            echo "<img src='../img/$entry' /><br />";
            echo "<input type='text' value='" . URL . 'img/' . "$entry' /><br
            />";
        }
    }

    closedir($handle);
}
