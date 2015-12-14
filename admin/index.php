<?php
//include config
require_once('../includes/config.php');



//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin</title>
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">
</head>
<body>

	<div id="wrapper">

	<?php include('menu.php');?>

	<?php 
	//show message from add / edit page
	if(isset($_GET['action'])){ 
		echo '<h3>Post '.$_GET['action'].'.</h3>'; 
	}
	?>

    <h1>Welcome to the Admin Panel</h1>

	<table>
	<tr>
		<th>Blog Statistics</th>
		<th></th>
	</tr>
	<?php
		try {


            $stmt = $db->query("SELECT count(*) FROM blog_posts_seo WHERE published = 1");
            $stmt->execute();
            $number_of_rows = $stmt->fetchColumn();

            $sec = $db->query("SELECT count(*) FROM blog_posts_seo WHERE published = 0");
            $sec->execute();
            $unpublished = $sec->fetchColumn();

            $coa = $db->query("SELECT count(*) FROM blog_comments WHERE published = 1");
            $coa->execute();
            $app_comm = $coa->fetchColumn();

            $cop = $db->query("SELECT count(*) FROM blog_comments WHERE published = 0");
            $cop->execute();
            $pen_comm = $cop->fetchColumn();

            $top = $db->query("SELECT postTitle, views FROM blog_posts_seo order by views DESC limit 5");
            $top->execute();

            $catview;

            $views = $db->query("Select SUM(views) FROM blog_posts_seo");
            $views->execute();

            $pview = $views->fetchColumn();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
				?>
            <tr>
                <td>
                    Number of published blog posts
                </td>
                <td>
                    <?php echo $number_of_rows; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Number of unpublished blog posts
                </td>
                <td>
                    <?php echo $unpublished; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Total views of all blog posts
                </td>
                <td>
                    <?php echo $pview; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Number of approved comments
                </td>
                <td>
                    <?php echo $app_comm; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Number of comments pending approval
                </td>
                <td>
                    <?php echo $pen_comm; ?>
                </td>
            </tr>
	</table>
    <table>
        <th>Top Posts</th>
        <th>Number of Views</th>
        <?php
        while($tpost = $top->fetch()) {
            echo '<tr>';
            echo '<td>' . $tpost['postTitle'] . '</td>';
            echo '<td>' . $tpost['views'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>

</body>
</html>
