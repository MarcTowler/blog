<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <title>Admin - Edit Post</title>
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
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="./">Blog Admin Index</a></p>

	<h2>Edit Post</h2>


	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($postID ==''){
			$error[] = 'This post is missing a valid id!.';
		}

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

		if($edit == '0' && $publish == '1')
		{
			//if it is a new post that we are editing then update the timestamp
			$postDate = date("Y-m-d H:i:s");
		}

		if(!isset($error)){

			try {

				$postSlug = slug($postTitle);

				//insert into database
				$stmt = $db->prepare('UPDATE blog_posts_seo SET postTitle = :postTitle, postSlug = :postSlug, postDesc = :postDesc, postCont = :postCont, postDate = :postDate, published = :publish WHERE postID = :postID') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postSlug'  => $postSlug,
					':postDesc'  => $postDesc,
					':postCont'  => $postCont,
					':postID'    => $postID,
					':postDate'  => $postDate,
                    ':publish'   => $publish
				));

				//delete all items with the current postID
				$stmt = $db->prepare('DELETE FROM blog_post_cats WHERE postID = :postID');
				$stmt->execute(array(':postID' => $postID));

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
				header('Location: index.php?action=updated');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	?>


	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}

		try {

			$stmt = $db->prepare('SELECT postID, postTitle, postSlug, postDesc, postCont, postDate, published FROM blog_posts_seo WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['id']));
			$row = $stmt->fetch(); 

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}

	?>

	<form action='' method='post'>
		<input type='hidden' name='postID' value='<?php echo $row['postID'];?>' />

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php echo $row['postTitle'];?>' size='50'></p><br />

		<p><label>Slug</label><br />
		<?php echo $_SERVER['SERVER_NAME'] . '/'; ?>
		<input type='text' name='postSlug' value='<?php echo $row['postSlug']; ?>' size='50'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php echo $row['postDesc'];?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php echo $row['postCont'];?></textarea></p>

		<p><label>Publish Time</label></p>
		<input type="text" name="postDate" value="<?php echo($row['postDate']); ?>" /></p>

		<p><label>Publish now?</label><br />
            <?php
            if($row['published'])
            {
                echo '<input type="radio" name="publish" value="1" checked="checked" />Yes <input type="radio" name="publish" value="0" />No</p>';
				echo '<input type="hidden" name="edit" value="1" />';
            } else {
                echo '<input type="radio" name="publish" value="1" />Yes <input type="radio" name="publish" value="0" checked="checked" />No</p>';
				echo '<input type="hidden" name="edit" value="0" />';
            }
            ?>


		<fieldset>
			<legend>Categories</legend>

			<?php

			$stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
			while($row2 = $stmt2->fetch()){

				$stmt3 = $db->prepare('SELECT catID FROM blog_post_cats WHERE catID = :catID AND postID = :postID') ;
				$stmt3->execute(array(':catID' => $row2['catID'], ':postID' => $row['postID']));
				$row3 = $stmt3->fetch(); 

				if($row3['catID'] == $row2['catID']){
					$checked = 'checked=checked';
				} else {
					$checked = null;
				}

			    echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
			}

			?>

		</fieldset>

		<p><input type='submit' name='submit' value='Update'></p>

		

	</form>

</div>
</body>
</html>	
