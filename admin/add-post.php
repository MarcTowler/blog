<?php //include config

require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../includes/markitup/sets/markdown/style.css" />
    <link rel="stylesheet" href="../includes/markitup/skins/simple/style.css" />
    <script src="../includes/markitup/jquery.markitup.js"></script>
    <script src="../includes/markitup/sets/markdown/set.js"></script>
    <script type="text/javascript" >
        $(document).ready(function() {
            $("textarea").markItUp(mySettings);
        });
    </script>
	<script>
		function convertToSlug(Text) {
			return Text
				.toLowerCase()
				.replace(/[^\w ]+/g,'')
				.replace(/ +/g,'-');
		}
		function sync()
		{
			var n1 = document.getElementById('n1');
			var n2 = document.getElementById('n2');
			n2.value = convertToSlug(n1.value);
		}
	</script>
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="./">Blog Admin Index</a></p>

	<h2>Add Post</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}

		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

        if($publish == ''){
            $error[] = 'Please select if you wish to publish now.';
        }

		if(!isset($error)){

			try {

				$postSlug = slug($postTitle);

				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_posts_seo (poster,postTitle,postSlug,postDesc,postCont,postDate,published) VALUES (:poster,:postTitle, :postSlug, :postDesc, :postCont, :postDate, :published)') ;
				$stmt->execute(array(
					':poster'    => $poster,
					':postTitle' => $postTitle,
					':postSlug'  => $postSlug,
					':postDesc'  => $postDesc,
					':postCont'  => $postCont,
					':postDate'  => $postDate,
                    ':published' => $publish
				));
				$postID = $db->lastInsertId();

				//add categories
				if(is_array($catID)){
					foreach($_POST['catID'] as $catID){
						$stmt = $db->prepare('INSERT INTO blog_post_cats (postID,catID)VALUES(:postID,:catID)');
						$stmt->execute(array(
							':postID' => $postID,
							':catID' => $catID
						));
					}
				}

				//redirect to index page
				header('Location: index.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post'>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' id="n1" value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>' onkeyup="sync()"></p>

		<p><label>Slug</label><br />
			<?php echo $_SERVER['SERVER_NAME'] . '/'; ?>
		<input type='text' name='postSlug' id="n2" value='' size='50'></p>


		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont'  cols='60' rows='10' data-provide='markdown'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><label>Publish Time</label></p>
		<input type="text" name="postDate" value="<?php echo(date("Y-m-d H:i:s")); ?>" /></p>

        <p><label>Publish now?</label><br />
        <input type="radio" name="publish" value="1" />Yes <input type="radio" name="publish" value="0" />No</p>

		<input type="hidden" value="<?php echo($user->get_user()['memberID']); ?>" name="poster" />

		<fieldset>
			<legend>Categories</legend>

			<?php
            $checked = "";
			$stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
			while($row2 = $stmt2->fetch()){

				if(isset($_POST['catID'])){

					if(in_array($row2['catID'], $_POST['catID'])){
                       $checked="checked='checked'";
                    }
				}

			    echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
			}

			?>

		</fieldset>

		<p><input type='submit' name='submit' value='Submit'></p>

	</form>
</ div>
</body>
</html>
