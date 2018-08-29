<?php

namespace model;

/**
 *
 */
class Book
{
    // La classe book ha come variabili d'istanza i parametri di un libro, ne possiamo aggiungere altri successivamente
    private $id;
    private $title;
    private $author;
    private $price;
    private $img;
    private $link;
    private $editor;

    // Constructor
    public function __construct(
        string $title,
        string $author,
        float $price,
        string $image,
        string $link,
        string $editor
    )
    {
        $this->id = 0;
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->img = $image;
        $this->link = $link;
        $this->editor = $editor;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImg(): string
    {
        return $this->img;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getEditor(): string
    {
        return $this->editor;
    }

    // setters
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    public function setImg(string $img)
    {
        $this->img = $img;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }

    public function setEditor(string $editor)
    {
        $this->editor = $editor;
    }

    public function __toString(): string
    {
        $desc = "ID: $this->id";
        $desc .= "<br />Title: $this->title";
        $desc .= "<br />Author: $this->author";
        $desc .= "<br />Price: $this->price";
        $desc .= "<br />Imagine: $this->img";
        $desc .= "<br />Link: $this->link";
        $desc .= "<br />Editor: $this->editor";
        $desc .= "<hr />";

        return $desc;
    }

    public function __clone()
    {
        $this->id = 0;
    }
}
