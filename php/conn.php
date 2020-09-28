<?php

namespace conn;

class db
{
    private $DB_HOST = "localhost";
    private $DB_USER = "root";
    private $DB_PASSWORD = "";
    private $DB_NAME = "hustmaths";
    private $DB_PORT = 3306;
    private $_db;

    private static $instance = null;


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

        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function signUp($uid, $name, $tel, $nick, $password)
    {
        $stmt = $this->_db->prepare("INSERT INTO user (uid, name, telphone, nick, password, id) VALUES (?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssssss", $uid, $name, $tel, $nick, $password, $id);
        $id = md5($uid . $nick);
        $password=md5("S&T".$password."hustmaths");
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
        $pwd=md5("S&T".$pwd."hustmaths");
        while ($row = $res->fetch_assoc()) {
            $password = $row["password"];
        }
        if ($password !== $pwd) {
            echo json_encode(['msg' => "密码错误", 'status' => 201]);
        } else {
            echo json_encode(['status' => 200]);
        }
    }

    public function myRank($nick)
    {
        $sql = "SELECT count(intergral) as rank FROM user WHERE intergral > (SELECT intergral FROM user WHERE nick = '$nick');";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $rank = $row["rank"] + 1;
        }
        $sql = "SELECT intergral FROM user WHERE nick = '$nick'";
        $res = $this->_db->query($sql);
        if (!$res) {
            echo $this->_db->error;
            return;
        }
        while ($row = $res->fetch_assoc()) {
            $intergral = $row["intergral"];
        }
        echo json_encode(["rank" => $rank, "intergral" => $intergral, "status" => 200]);
    }

    public function getRankList($begin, $length = 20)
    {
        $sql = " SELECT intergral,nick,uid FROM user ORDER BY intergral DESC, uid ASC LIMIT $begin ,$length";
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
}
