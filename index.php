<?php
    date_default_timezone_set('Europe/Moscow');
    include 'connection.php';
    include 'comment.php';
?>

<!DOCTYPE html>
<html lang="ru">
<html>
<head>
    <meta charset="UTF-8">
    <title>Meow</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<h1> </h1>
<?php


echo "<form method='POST' action='".setComments($conn)."' enctype = 'multipart/form-data'>
    <input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
    <input type='hidden' name='likes' value='0'>
    <input type='hidden' name='dislikes' value='0'>
    <textarea name='message' required></textarea><br>
    <button type='submit' class='commentSubmit' name='commentSubmit'>Оставить сообщение</button>
    <br><input type='file' name='img' style='margin-left: 25px'>
    </form>";
?>

<hr class='hr'>
<div class="flex-container">

<?php

echo "<form method='POST' action='".getComments($conn)."'>
</form>";
?>
</div>

</body>

</html>