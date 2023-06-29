<?php

require_once("User.php");

class DAO
{

    private $user = "root";
    private $pwd = "pathSQL";
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
        $stmt = $this->conn->prepare('SELECT u.user_id, u.affiliation_id, a.affiliation_name, ut.user_type_name, u.user_type_id, u.user_name, u.password
         FROM `users` AS u
         LEFT JOIN affiliation AS a
         ON u.affiliation_id = a.affiliation_id
         LEFT JOIN user_type as ut
         ON u.user_type_id = ut.user_type_id
         WHERE u.user_id = :login_id');
        $stmt->bindValue(":login_id", $login_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new User($result["user_id"], $result["user_name"], $result["affiliation_id"], $result["affiliation_name"], $result["user_type_name"], $result["user_type_id"], $result["password"]);

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
        $stmt->bindValue(":user_id", $user->getUserId());
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId());
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // ログインしているユーザーが借りている本の情報を取得するためのSQL文
    public function getAllBooks(&$user)
    {
        $stmt = $this->conn->prepare("SELECT l.user_id, b.book_id, b.book_name, b.author, b.publisher, b.remarks, b.image, l.lending_status
                                        FROM book AS b
                                        LEFT JOIN lent AS l
                                        ON l.book_id = b.book_id
                                        WHERE b.affiliation_id = :affiliation_id");
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId());
        $res = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // ログインしているユーザーが所属しているクラスの全ての貸出中の本を取得するためのSQL文
    public function getLentNowBooks(&$user)
    {
        $stmt = $this->conn->prepare("SELECT l.user_id, b.book_id, b.book_name, b.author, b.publisher, b.remarks, b.image, l.lending_status
                                            FROM book AS b         
                                            LEFT JOIN lent AS l            
                                            ON l.book_id = b.book_id           
                                            WHERE b.affiliation_id = :affiliation_id AND l.lending_status = 'impossible'");
        $stmt->bindValue(":affiliation_id", $user->getAffiliationId());
        $res = $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        return $result;
    }
}
