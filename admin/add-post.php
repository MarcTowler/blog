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
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script src="../js/ckeditor/ckeditor.js"></script>
  <script>
	  CKEDITOR.replaceAll();
	  CKEDITOR.editorConfig = function( config ) {
		  config.toolbarGroups = [
			  { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
			  { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
			  { name: 'links', groups: [ 'links' ] },
			  { name: 'insert', groups: [ 'insert' ] },
			  { name: 'forms', groups: [ 'forms' ] },
			  { name: 'tools', groups: [ 'tools' ] },
			  { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
			  { name: 'others', groups: [ 'others' ] },
			  '/',
			  { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			  { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
			  { name: 'styles', groups: [ 'styles' ] },
			  { name: 'colors', groups: [ 'colors' ] },
			  { name: 'about', groups: [ 'about' ] }
		  ];

		  config.removeButtons = 'Underline,Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord';
	  };
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
				$stmt = $db->prepare('INSERT INTO blog_posts_seo (postTitle,postSlug,postDesc,postCont,postDate,published) VALUES (:postTitle, :postSlug, :postDesc, :postCont, :postDate, :published)') ;
				$stmt->execute(array(
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
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><label>Publish Time</label></p>
		<input type="text" name="postDate" value="<?php echo(date("Y-m-d H:i:s")); ?>" /></p>

        <p><label>Publish now?</label><br />
        <input type="radio" name="publish" value="1" />Yes <input type="radio" name="publish" value="0" />No</p>

		<input type="hidden" value="<?php echo($_SESSION['uid']);?>" name="poster" />

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

</div>
