<?php

namespace model;

/**
 *
 */
class Book
{
    // La classe book ha come variabili d'istanza i parametri di un libro, ne possiamo aggiungere altri successivamente
    private $id;
    private $titolo;
    private $autore;
    private $prezzo;
    private $img;
    private $link;
    private $editore;

    // Costruttore
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
        $this->titolo = $title;
        $this->autore = $author;
        $this->prezzo = $price;
        $this->img = $image;
        $this->link = $link;
        $this->editore = $editor;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->titolo;
    }

    public function getAuthor(): string
    {
        return $this->autore;
    }

    public function getPrice(): float
    {
        return $this->prezzo;
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
        return $this->editore;
    }

    // setters
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitle(string $title)
    {
        $this->titolo = $title;
    }

    public function setAuthor(string $author)
    {
        $this->autore = $author;
    }

    public function setPrice(float $price)
    {
        $this->prezzo = $price;
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
        $desc .= "<br />Titolo: $this->titolo";
        $desc .= "<br />Autore: $this->autore";
        $desc .= "<br />Prezzo: $this->prezzo";
        $desc .= "<br />Immmagine: $this->img";
        $desc .= "<br />Link: $this->link";
        $desc .= "<br />Editore: $this->editore";
        $desc .= "<hr />";

        return $desc;
    }

    public function __clone()
    {
        $this->id = 0;
    }
}
