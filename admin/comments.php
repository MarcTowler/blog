<?php
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
    $stmt = $db->prepare('UPDATE blog_comments SET published = 1 WHERE cid = :cid');
    $stmt->execute(array(':cid' => $_GET['pub']));

    header('Location: comments.php?action=published');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Comments Admin</title>
    <link rel="stylesheet" href="../style/normalize.css">
    <link rel="stylesheet" href="../style/main.css">
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
        </tr>
        <?php
        try {

            $stmt = $db->query('SELECT cid, pid, name, email, comment, post_date, published FROM blog_comments WHERE published = 0 ORDER BY post_date DESC');
            while($row = $stmt->fetch()){

                echo '<tr>';
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
