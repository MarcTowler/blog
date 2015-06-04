<?php
//include config
require_once('../includes/config.php');



//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//show message from add / edit page
if(isset($_GET['delpost'])){

    $stmt = $db->prepare('DELETE FROM blog_posts_seo WHERE postID = :postID') ;
    $stmt->execute(array(':postID' => $_GET['delpost']));

    //delete post categories.
    $stmt = $db->prepare('DELETE FROM blog_post_cats WHERE postID = :postID');
    $stmt->execute(array(':postID' => $_GET['delpost']));

    header('Location: list-post.php?action=deleted');
    exit;
}

if(isset($_GET['unpub'])) {

    $stmt = $db->prepare('UPDATE blog_posts_seo SET published = 0 WHERE postID = :postID');
    $stmt->execute(array(':postID' => $_GET['unpub']));

    header('Location: list-post.php?action=unpublished');
    exit;
}

if(isset($_GET['pub'])) {

    $stmt = $db->prepare('UPDATE blog_posts_seo SET published = 1 WHERE postID = :postID');
    $stmt->execute(array(':postID' => $_GET['pub']));

    header('Location: list-post.php?action=published');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link rel="stylesheet" href="../style/normalize.css">
    <link rel="stylesheet" href="../style/main.css">
    <script language="JavaScript" type="text/javascript">
        function delpost(id, title)
        {
            if (confirm("Are you sure you want to delete '" + title + "'"))
            {
                window.location.href = 'list-post.php?delpost=' + id;
            }
        }

        function unpub(id, title)
        {
            if (confirm("Are you sure you want to unpublish '" + title + "'"))
            {
                window.location.href = 'list-post.php?unpub=' + id;
            }
        }

        function pub(id, title)
        {
            if (confirm("Are you sure you want to publish '" + title + "'"))
            {
                window.location.href = 'list-post.php?pub=' + id;
            }
        }
    </script>
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

    <p><a href='add-post.php'>Add Post</a></p>

    <table>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php
        try {

            //instantiate the class
            $pages = new Paginator('20','p');

            //collect all records fro the next function
            $stmt = $db->query('SELECT postID FROM blog_posts_seo');

//determine the total number of records
            $pages->set_total($stmt->rowCount());

            $stmt = $db->query('SELECT postID, postTitle, postDate, published FROM blog_posts_seo ORDER BY postID DESC '.$pages->get_limit());
            while($row = $stmt->fetch()){

                echo '<tr>';
                echo '<td>'.$row['postTitle'].'</td>';
                echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
                ?>

                <td>
                    <a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> |
                    <a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')">Delete</a>
                    -
                    <?php
                    if($row['published'])
                    {
                        ?>
                        <a href="javascript:unpub('<?php echo$row['postID'];?>','<?php echo $row['postTitle'];?>')">Unpublish</a>
                    <?php
                    } else {
                        ?>
                        <a href="javascript:pub('<?php echo$row['postID'];?>','<?php echo $row['postTitle'];?>')">Publish</a>
                    <?php
                    }
                    ?>
                </td>

                <?php
                echo '</tr>';

            }

            echo $pages->page_links();

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        ?>
    </table>

    <p><a href='add-post.php'>Add Post</a></p>

</div>

</body>
</html>