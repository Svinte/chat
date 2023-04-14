<?php
    if ($_GET["index"]) {
        function username($userId) {
            $database = file_get_contents("database.json");
            $database = json_decode($database);
            $users = $database->users;
            foreach ($users as $key) {
                if (array_values($key)[1] == $userId) {
                    return array_values($key)[0];
                }
            }
            return "Deleted user";
        }
        $database = file_get_contents("database.json");
        $database = json_decode($database);
        $users = $database->users;
        $messages = $database->messages;
        $index = $_GET["index"];
        $loop = $index;
        $data = [];
        while ($loop < count($messages)) {
            $item = $messages[$loop];
            $item->Id=username($item->Id);
            array_push($data, $item);
            $loop++;
        }
        echo json_encode($data);
    } else {
        echo "error";
    }
?>