# lab2

Задание

![image](https://user-images.githubusercontent.com/121386333/209470657-b8ab0736-f101-4fe1-81f1-e67f460de1c2.png)

Ход работы

![image](https://user-images.githubusercontent.com/121386333/209470672-50b5c30b-5e27-4e13-b450-767f76a36f86.png)

Интерфейс
. Пользовательский сценарий работы

Пользовательский сценарий

    Пользователь попадает на страницу index.php и нажимает оставить сообщение. На экране появляется уведомление "Заполните это поле". Тогда он набирает текст и нажимает оставить сообщение и оно появляется на экране.
    Пользователь видит сообщение которое ему понравилось и нажимает кнопку Like. Счетчик лайков на данном посте увеличивается на 1.

API сервера

В основе приложения использована клиент-серверная архитектура. Обмен данными между клиентом и сервером осуществляется с помощью HTTP POST запросов. В теле POST запроса отправки поста используются следующие поля: comment. Для увеличения счётчика реакции используется форма с POST запросом. В теле POST запроса реакции используются следующие поля: cid, likes.
Хореография

    Отправка сообщения. Принимается введенное сообщение. Если поле оказалось пустым, то сайт просит запольнить его. Иначе отправляется запрос на добавление сообщения в базу данных, так же туда добавляется дата, название картинки и время написания сообщения. Затем происходит перенаправление на страницу index.php. Из базы данных выводится данное сообщение с датой и временем его написания, а также картинкой если она была добавлена.
    Просмотр и оценка сообщений. Кнопка like вызывает отправление запроса в базу данных на изменение количества лайков на определенном id сообщения.


Хореография
 Структура базы данных


    "cid" типа int с автоинкрементом для выдачи уникальных id всем сообщениям
    "uid" типа varchar для хранения никнеймов пользователей
    "date" типа datetime для хранения даты и времени отправления сообщения
    "message" типа text для хранения сообщений
    "likes" типа int для хранения количества лайков
    "dislikes" типа int для хранения количества дизлайков
    "image_id" типа varchar для хранения картинок


 Алгоритм

![image](https://user-images.githubusercontent.com/121386333/209470683-91748f1f-c7ac-4ebd-8eac-1c2d8ce00c50.png)


 HTTP запрос/ответ

Запрос

![image](https://user-images.githubusercontent.com/121386333/209470704-f071c34c-423a-4816-a987-c9376dc364cc.png)


![image](https://user-images.githubusercontent.com/121386333/209470719-74726c0f-a422-479b-8865-02dfd4c3e0be.png)

 Значимые фрагменты кода


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
