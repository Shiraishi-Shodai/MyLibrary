<?php

require_once("User.php");

class DAO
{

    private $host = "localhost";
    private $user = "root";
    private $pwd = "pathSQL";
    private $dbname = "library";
    private $dsn = "mysql:host=localhost;port=3306;dbname=library;";
    private $conn;

    // このクラスをインスタンス化時にデータベースに接続
    public function __construct()
    {
        $this->conn = new PDO($this->dsn, $this->user, $this->pwd);
    }

    // ログインしたユーザーの情報を取得(User型で返す)
    public function getUser($login_id): User
    {
        $stmt = $this->conn->prepare('SELECT u.user_id, u.affiliation_id, a.affiliation_name, u.user_type_id, u.user_name, u.password
                                        FROM `users` as u
                                        JOIN affiliation as a
                                        ON u.affiliation_id = a.affiliation_id
                                        WHERE user_id = 2;');
        $stmt->bindValue(":login_id", $login_id);
        $res = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new User($result["user_id"], $result["user_name"], $result["affiliation_id"], $result["affiliation_name"], $result["user_type_id"], $result["password"]);

        return $user;
    }

    // ログインしているユーザーが借りている本の情報を取得するためのSQL文
    public function getMyBooks(&$user)
    {
        $stmt = $this->conn->prepare("SELECT l.user_id, b.book_id, b.book_name, b.author, b.publisher, b.remarks, b.image, l.lending_status
                                        FROM book AS b
                                        INNER JOIN lent AS l
                                        ON l.book_id = b.book_id
                                        WHERE b.affiliation_id = :affiliation_id AND l.user_id = :user_id AND l.lending_status = 'impossible'");
        $stmt->bindValue(":login_id", $user->getUserId);
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId);
        $res = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $myBooks = new User($result["user_id"], $result["user_id"], $result["book_id"], $result["book_name"], $result["author"], $result["publisher"], $result["remarks"], $result["image"], $result["lending_status"]);

        return $myBooks;
    }

    // ログインしているユーザーが借りている本の情報を取得するためのSQL文
    public function getAllBooks(&$user)
    {
        $stmt = $this->conn->prepare("SELECT l.user_id, b.book_id, b.book_name, b.author, b.publisher, b.remarks, b.image, l.lending_status
                                        FROM book AS b
                                        LEFT JOIN lent AS l
                                        ON l.book_id = b.book_id
                                        WHERE b.affiliation_id = :affiliation_id");
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId);
        $res = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $AllBooks = new User($result["user_id"], $result["user_id"], $result["book_id"], $result["book_name"], $result["author"], $result["publisher"], $result["remarks"], $result["image"], $result["lending_status"]);

        return $AllBooks;
    }

    // ログインしているユーザーが所属しているクラスの全ての貸出中の本を取得するためのSQL文
    public function getLentNowBooks(&$user)
    {
        $stmt = $this->conn->prepare("SELECT l.user_id, b.book_id, b.book_name, b.author, b.publisher, b.remarks, b.image, l.lending_status
                                            FROM book AS b         
                                            LEFT JOIN lent AS l            
                                            ON l.book_id = b.book_id           
                                            WHERE b.affiliation_id = :affiliation_id AND l.lending_status = 'impossible'");
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId);
        $res = $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $lentNowBooks = new User($result["user_id"], $result["user_id"], $result["book_id"], $result["book_name"], $result["author"], $result["publisher"], $result["remarks"], $result["image"], $result["lending_status"]);

        return $lentNowBooks;
    }
}
