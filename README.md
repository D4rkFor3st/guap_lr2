# lab2

Задание

![изображение](https://user-images.githubusercontent.com/90793439/205446032-19686273-b527-4051-9345-27d7a54485ed.png)

Ход работы

![изображение](https://user-images.githubusercontent.com/90793439/205446385-2727c064-e3bb-43de-b820-0996cef2fe04.png)

1. Пользовательский интерфейс
2. 
![изображение](https://user-images.githubusercontent.com/90793439/209466673-43805705-d6ad-4ebe-b2ab-62637efbc1cf.png)

Форма отправки сообщений

![изображение](https://user-images.githubusercontent.com/90793439/209467688-caf85cb0-7b29-497a-a1f7-efbc91e54e80.png)


Интерфейс
2. Пользовательский сценарий работы

Пользовательский сценарий

    Пользователь попадает на страницу index.php и нажимает оставить сообщение. На экране появляется уведомление "Заполните это поле". Тогда он набирает текст и нажимает оставить сообщение и оно появляется на экране.
    Пользователь видит сообщение которое ему понравилось и нажимает кнопку Like. Счетчик лайков на данном посте увеличивается на 1.

API сервера

В основе приложения использована клиент-серверная архитектура. Обмен данными между клиентом и сервером осуществляется с помощью HTTP POST запросов. В теле POST запроса отправки поста используются следующие поля: comment. Для увеличения счётчика реакции используется форма с POST запросом. В теле POST запроса реакции используются следующие поля: cid, likes.
Хореография

    Отправка сообщения. Принимается введенное сообщение. Если поле оказалось пустым, то сайт просит запольнить его. Иначе отправляется запрос на добавление сообщения в базу данных, так же туда добавляется дата, название картинки и время написания сообщения. Затем происходит перенаправление на страницу index.php. Из базы данных выводится данное сообщение с датой и временем его написания, а также картинкой если она была добавлена.
    Просмотр и оценка сообщений. Кнопка like вызывает отправление запроса в базу данных на изменение количества лайков на определенном id сообщения.


Хореография
4. Структура базы данных


    "cid" типа int с автоинкрементом для выдачи уникальных id всем сообщениям
    "uid" типа varchar для хранения никнеймов пользователей
    "date" типа datetime для хранения даты и времени отправления сообщения
    "message" типа text для хранения сообщений
    "likes" типа int для хранения количества лайков
    "dislikes" типа int для хранения количества дизлайков
    "image_id" типа varchar для хранения картинок


5. Алгоритм

![изображение](https://user-images.githubusercontent.com/90793439/209468036-7e53fa37-6eeb-4a67-be57-098cfd08ae0f.png)


6. HTTP запрос/ответ

Запрос

![изображение](https://user-images.githubusercontent.com/90793439/209468003-7662e49f-d44c-467f-bed6-8fd6683303c3.png)


![изображение](https://user-images.githubusercontent.com/90793439/209467962-c9b975b7-8655-4d7e-bb55-04e17cc7af73.png)

7. Значимые фрагменты кода


Отправка сообщения

```
function setComments($conn) {
    if (isset($_POST['commentSubmit'])) {
        $uid = $_POST['uid'];
        $date = $_POST['date'];
        $message = $_POST['message'];
        $likes = $_POST['likes'];
        $dislikes = $_POST['dislikes'];
        $file = $_FILES['img'];
        $name = gen_str(5).".png";
        (@copy($file["tmp_name"], __DIR__.'/img/'.$name));
        $sql = "INSERT INTO comments (uid, date, message, likes, dislikes, image_id) VALUES ('$uid', '$date', '$message', '$likes', '$dislikes', '$name')";
        $result = $conn->query($sql);
        header('Location: index.php' );
    }
}
```

Добавление лайка

``` 
function likeSubmit($conn,$row) {
    if(isset($_POST[$row['cid']])) {
        $cid = $row['cid'];
        $likes = $row['likes']+1;
        $query = "UPDATE comments SET likes = '$likes' WHERE cid = '$cid'";
        $result = mysqli_query($conn, $query);
    }
}
```

Вывод сообщений

``` 
function getComments($conn){
    $sql = "SELECT * FROM comments";
    $result = $conn->query($sql);
    $max_page_posts = 100;
    $last_post = mysqli_num_rows($result);
    $i = 0;
    while(($row = $result->fetch_assoc())){
        if (($last_post - $i) > $max_page_posts ){
        }
        else{
            echo "<div class='comment-box'><p>";
            echo $row['uid']."<br>";
            echo $row['date']."<br>";
            echo $row['message']."<br>";
            $img_id = $row['image_id'];
            echo "<br>";
            if ($img_id) {
                echo "<td><img style = 'width:390px;' src = 'img/".$img_id."' alt = 'Тут могло быть изображение'/> </td>";
                }
            echo "<div>  <form method='POST' action='".likeSubmit($conn,$row)."'>  <button type='submit' name='".$row['cid']."' class='likeSubmit'>Like</button>  Likes: ".$row["likes"]."  </form></div>";
            echo "<br>";
            echo "<div>  <form method='POST' action='".dislikeSubmit($conn,$row)."'>  <button type='submit' name='".$row['cid']."' class='dislikeSubmit' style='  background-color: #ff0000; color: white; border: none; cursor: pointer; opacity: 0.9;'>Dislike</button>  Dislikes: ".$row["dislikes"]."  </form></div>";
            echo "<hr>";
            echo "<p></div>";
        }
        $i++;
    }
}
```

Вывод

В ходе выполнения лабораторной работы спроектировали и разработали систему сайта на котором ты можешь переписываться сам с собой. отправлять картинки и поднимать себе настроение
