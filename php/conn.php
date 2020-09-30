<?php

namespace conn;

use DateTime;

class db
{
    private $DB_HOST = "localhost";
    private $DB_USER = "root";
    private $DB_PASSWORD = "";
    private $DB_NAME = "hustmaths";
    private $DB_PORT = 3306;
    private $_db;

    private static $instance = null;
    private static $private = null;

    private function __construct()
    {

        $this->_db = new \mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME, $this->DB_PORT);

        if ($this->_db->connect_error) {
            echo $this->_db->connect_error;
        }
    }


    private function __clone()
    {
        return false;
    }


    public static function getInstance()
    {

        self::$private = openssl_pkey_get_private(file_get_contents("D:/wamp64/www/mooncake/php/rsa_private_key.pem"));
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function rsaDecrypt($data)
    {
        return $data = (openssl_private_decrypt(base64_decode($data), $data, self::$private)) ? $data : null;
    }

    public function signUp($nick, $password, $uid = '', $name = '', $tel = '')
    {
        $stmt = $this->_db->prepare("INSERT INTO user (uid, name, telphone, nick, password, id, lastPlayTime) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sssssss", $uid, $name, $tel, $nick, $password, $id, $lastPlayTime);
        $id = md5($uid . $nick);
        $lastPlayTime = time();
        $password = md5("S&T" . $password . "hustmaths");
        $res = $stmt->execute();
        if (!$res) {
            if (strpos($this->_db->error, "PRIMARY") !== false) {
                echo json_encode(['msg' => "请检查学号或昵称是否重复", 'status' => 201]);
            } else if (strpos($this->_db->error, "nick") !== false) {
                echo json_encode(['msg' => "昵称重复", 'status' => 201]);
            } else {
                echo $this->_db->error;
            }
        } else {
            echo json_encode(['status' => 200]);
        }
    }

    public function signIn($nick, $pwd)
    {
        $sql = "SELECT password FROM user WHERE nick = '$nick'";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        if ($res->num_rows == 0) {
            echo json_encode(['msg' => "该用户不存在", 'status' => 201]);
            return;
        }
        $pwd = md5("S&T" . $pwd . "hustmaths");
        while ($row = $res->fetch_assoc()) {
            $password = $row["password"];
        }
        if ($password !== $pwd) {
            echo json_encode(['msg' => "密码错误", 'status' => 201]);
        } else {
            echo json_encode(['status' => 200]);
        }
    }

    public function authen($name, $uid, $tel, $nick)
    {
        $sql = "UPDATE user SET name='$name' , uid = '$uid' , telphone='$tel' WHERE nick = '$nick'";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        echo json_encode(['status' => 200]);
    }

    public function myRank($nick)
    {
        $sql = "SELECT credit FROM user WHERE nick = '$nick'";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $credit = $row["credit"];
        }
        $sql = "SELECT count(credit) as rank FROM user WHERE credit > (SELECT credit FROM user WHERE nick = '$nick');";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $rank = $row["rank"] + 1;
        }
        $sql = "SELECT count(credit) as rank FROM user WHERE credit = (SELECT credit FROM user WHERE nick = '$nick') AND lastPlayTime > (SELECT lastPlayTime FROM user WHERE nick = '$nick');";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $rank += $row["rank"];
        }
        echo json_encode(["rank" => $rank, "credit" => $credit, "status" => 200]);
    }

    public function getRankList($begin, $length = 20)
    {
        $sql = "SELECT credit,nick,uid FROM user ORDER BY credit DESC, lastPlayTime DESC LIMIT $begin ,$length";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
        }
        if ($res->num_rows == 0) {
            echo json_encode(["status" => 404]);
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }
        echo json_encode(['res' => $result, 'status' => 200]);
    }

    public function updateGame($nick, $score, $totalTime)
    {
        $time = time();
        $sql = "UPDATE user SET credit = credit+$score , totalTime = totalTime+$totalTime , lastPlayTime=$time WHERE nick='$nick';";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        echo 1;
    }
}
