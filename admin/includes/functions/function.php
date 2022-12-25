<?php
function getAllFrom($select, $table, $where, $and, $orderfield, $order = "DESC")
{
    global $conn;
    $getAll = $conn->prepare("SELECT $select FROM $table $where $and ORDER BY $orderfield $order");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle) == 'true') {
        echo $pageTitle;
    } else
        echo "Default";
}


function redirectHome($msg, $url = null, $seconds = 3)
{
    if ($url == null) {
        $url = 'index.php';
    } elseif ($url == 'back') {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
            $url = $_SERVER['HTTP_REFERER'];
        } else
            $url = 'index.php';
    }
    echo $msg;

    echo "<div class='alert alert-info'>You will be directed after $seconds seconds.</div>";
    header("refresh:$seconds;$url");
    exit();
}

function countItem($item, $table, $value = null)
{
    global $conn;
    if (!isset($value)) {
        $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();
    } else {
        $stmt2 = $conn->prepare("SELECT $item FROM $table WHERE $item =?");
        $stmt2->execute(array($value));
        $count = $stmt2->rowCount();
        return $count;
    }
}

function getLatest($item, $table, $order, $limit)
{
    global $conn;
    $stmt2 = $conn->prepare("SELECT $item FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt2->execute();
    $row = $stmt2->fetchAll();
    return $row;
}
