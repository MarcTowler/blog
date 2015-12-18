<?php
/**
 * TODO:
 * Fix JS for spam/publish to allow multiple selections.
 * Fix PHP for above
 */
//include config
require_once('../includes/config.php');

//check if user is logged in else redirect
if(!$user->is_logged_in())
{
    header('Location: login.php');
}

//show messages from comments del/pub/unpub etc
if(isset($_GET['spam']))
{
    $stmt = $db->prepare('DELETE FROM blog_comments WHERE cid = :cid');
    $stmt->execute(array(':cid' => $_GET['spam']));

    header('Location: comments.php?action=marked%20as%20spam');
    exit;
}

if(isset($_GET['pub']))
{
    $stmt = $db->prepare('UPDATE blog_comments SET post_date = :postDate, published = 1 WHERE cid = :cid');
    $stmt->execute(array(':cid' => $_GET['pub'], ':postDate' => date("Y-m-d H:i:s")));

    header('Location: comments.php?action=published');
    exit;
}

if(isset($_GET['del']))
{
    $stmt = $db->prepare('DELETE FROM blog_comments WHERE cid = :cid');
    $stmt->execute(array(':cid' => $_GET['del']));

    header('Location: comments.php?action=deleted');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Comments Admin</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
        <script language="JavaScript" type="text/javascript">
        function spam(id, title)
        {
            if (confirm("Are you sure you want to mark the comment by '" + title + "' as a spam comment - note this cannot be undone"))
            {
                window.location.href = 'comments.php?spam=' + id;
            }
        }

        function pub(id, title)
        {
            if (confirm("Are you sure you want to publish the comment by '" + title + "'"))
            {
                window.location.href = 'comments.php?pub=' + id;
            }
        }

        function toggle(source) {
            checkboxes = document.getElementsByName('comment[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
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
        echo '<h3>Comment '.$_GET['action'].'.</h3>';
    }
    ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
            <th>Post</th>
        </tr>
        <?php
        try {

            $stmt = $db->query('SELECT c.cid, c.name, c.email, c.comment, c.post_date, c.published, p.postTitle,
                                p.postSlug FROM blog_comments c INNER JOIN blog_posts_seo p ON c.pid = p.postID WHERE
                                c.published = 0 ORDER BY post_date DESC');

            while($row = $stmt->fetch()){

                echo '<tr>';
//                echo '<td><input type="checkbox" name = "comment[]" value = "' . $row['cid'] . '">';
                echo '<td>'.$row['name'].'</td>';
                echo '<td>'.$row['email'].'</td>';
                echo '<td>'.$row['comment'].'</td>';
                echo '<td>'.date('jS M Y', strtotime($row['post_date'])).'</td>';
                ?>

                <td>
                    <a href="javascript:spam('<?php echo$row['cid'];?>','<?php echo $row['name'];?>')">Spam</a> |
                    <a href="javascript:pub('<?php echo $row['cid'];?>','<?php echo $row['name'];?>')">Publish</a>
                </td>

                <?php
                echo '<td><a href="../'.$row['postSlug'].'.html">'.$row['postTitle'].'</a></td>';
                echo '</tr>';
            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        ?>
    </table>

</div>

</body>
</html>
