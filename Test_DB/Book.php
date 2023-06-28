<?php

class Book
{

    private $user_id;
    private $book_id;
    private $book_name;
    private $author;
    private $publisher;
    private $remarks;
    private $image;
    private $lending_status;

    public function __construct($user_id, $book_id, $book_name, $author, $publisher, $remarks, $image, $lending_status)
    {
        $this->user_id = $user_id;
        $this->book_id = $book_id;
        $this->book_name = $book_name;
        $this->author = $author;
        $this->publisher = $publisher;
        $this->remarks = $remarks;
        $this->image = $image;
        $this->lending_status = $lending_status;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getBookId(): string
    {
        return $this->book_id;
    }
    public function getBookName(): string
    {
        return $this->book_name;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function getPublisher(): string
    {
        return $this->publisher;
    }
    public function getRemarks(): string
    {
        return $this->remarks;
    }
    public function getImage(): string
    {
        return $this->image;
    }
    public function getLendingStatus(): string
    {
        return $this->lending_status;
    }
}
