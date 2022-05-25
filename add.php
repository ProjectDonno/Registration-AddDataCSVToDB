<?php
    session_start();

    $updateCounter = 0;
    $addCounter = 0;

    /*
    function printResults($result) {
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<b>ID:</b> ".$row['id'].". ";
                echo "user_id: ".$row['user_id'].". ";
                echo "code: ".$row['code'].". ";
                echo "name: ".$row['name'].". ";
                echo "price: ".$row['price'].". ";
                echo "preview_text: ".$row['preview_text'].". ";
                echo "detail_text: ".$row['detail_text'].'<br><br>';
            }
        }
        echo "<hr>";
    } 
    */

    // главная функция, решает, что делать с поступившими данными
    function addNewRecords($mysql,$userId, $list)
    {
        $code = $list["code"];
        $name = $list["name"];
        $price = $list["price"];
        $preview_text = $list["preview_text"];
        $detail_text = $list["detail_text"];
        
        // $mysql = new mysqli("localhost", "root", "root", "shop");
        // $mysql->query("SET NAMES 'utf8'");

        if(!($mysql->connect_error)) {
            // если превью описание пустое, то берём 30 символов из детального описания, 
            // иначе проверяем не больше ли 30 символом имеющеяся строка
            if($preview_text == '') {
                $preview_text = mb_substr($detail_text, 0, 30); // обязательно mb_substr(), иначе сбивается кодировка (https://habr.com/ru/post/13969/)
            } else {
                if (strlen($preview_text) > 30) {
                    $preview_text = mb_substr($preview_text, 0, 30);
                }
            }            

            $result=$mysql->query("SELECT * FROM `products` WHERE `code` = $code");
            $row = $result->fetch_assoc();
            // если в таблице уже есть такой товар
            if($row['code'] == $code) {
                // если этот товар принадлежит текущему пользователю
                if($row['user_id'] == $userId) {
                    // обновляем имеющийся товар
                    $id = $row['id'];
                    $mysql->query("UPDATE `products` SET `name`='$name', `price`='$price', `preview_text`='$preview_text', `detail_text`='$detail_text' WHERE `id` = '$id'");
                    $GLOBALS["updateCounter"]+=1;
                } else {
                    // добавляем новый товар, хотя такой есть, добавленный другим пользователем
                    $mysql->query("INSERT INTO `products` (`user_id`, `code`, `name`, `price`, `preview_text`, `detail_text`) VALUES('$userId', '$code', '$name', '$price', '$preview_text', '$detail_text')");
                    $GLOBALS["addCounter"]+=1;
                }
            } else {
                // добавляем абсолютно новый товар
                $mysql->query("INSERT INTO `products` (`user_id`, `code`, `name`, `price`, `preview_text`, `detail_text`) VALUES('$userId', '$code', '$name', '$price', '$preview_text', '$detail_text')");
                $GLOBALS["addCounter"]+=1;
            }

        } else {
            echo 'Error Number: '.$mysql->connect_errno.'<br/>';
            echo 'Error '.$mysql->connect_error;
        }
        // $mysql->close();
    }

    // перемещаем выбранный файл в локальную папку "temp"
    if(move_uploaded_file($_FILES['file']['tmp_name'], "temp/".$_FILES['file']['name'])) {
        $row = 1;
        // подключение к бд
        $mysql = new mysqli("localhost", "root", "root", "shop");
        $mysql->query("SET NAMES 'utf8'");
        // создаем ассоциативный массив
        $list = array("code"=>"", "name"=>"", "price"=>"", "preview_text"=>"", "detail_text"=>"");
        // открываем файл, пропускаем первую строку, так как она содержить заголовки
        // и начинаем извлекать нужные данные
        if (($handle = fopen("temp/".$_FILES['file']['name'], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);

                if($row !== 1)
                {
                    for ($i=0; $i < $num; $i++) {
                        switch($i)
                        {
                            case 0: break;
                            case 1: $list["code"] = $data[$i]; break;
                            case 2: $list["name"] = $data[$i]; break;
                            case 3: $list["price"] = $data[$i]; break;
                            case 4: $list["preview_text"] = $data[$i]; break;
                            case 5: $list["detail_text"] = $data[$i]; break;
                        }            
                    }
                    // вызываем функцию записи данных в бд
                    addNewRecords($mysql, $_SESSION["user_id"], $list);
                    $row++;
                } else {
                    $row++;
                }
            }
            // закрываем файл
            fclose($handle);
            // закрывам бд
            $mysql->close();

            global $updateCounter;
            global $addCounter;

            echo "Обновлено: $updateCounter <br/>";
            echo "Добавлено: $addCounter <br/>";
        }
    } else {
        echo "Ошибка в копировании файла (скрипт add.php , условие)";
    }
?>