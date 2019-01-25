<?php
    namespace model;

    use \JsonSerializable;

    /**
     *
     */
    class AudioBook implements JsonSerializable 
    {
        // La classe book ha come variabili d'istanza i parametri di un libro, ne possiamo aggiungere altri successivamente
        private $id;
        private $title;
        private $author;
        private $voice;
        private $img;
        private $link;
        private $recent;

        // Constructor
        public function __construct(
            string $title,
            string $author,
            string $voice,
            string $image,
            string $link,
            bool $recent = false
        )
        {
            $this->id = 0;
            $this->title = $title;
            $this->author = $author;
            $this->voice = $voice;
            $this->img = $image;
            $this->link = $link;
            $this->recent = $recent;
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

        public function getVoice(): string
        {
            return $this->voice;
        }

        public function getImg(): string
        {
            return $this->img;
        }

        public function getLink(): string
        {
            return $this->link;
        }

        public function isRecent(): bool
        {
            return $this->recent;
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

        public function setVoice(string $voice)
        {
            $this->voice = $voice;
        }

        public function setImg(string $img)
        {
            $this->img = $img;
        }

        public function setLink(string $link)
        {
            $this->link = $link;
        }

        public function setRecent(bool $recent)
        {
            $this->recent = $recent;
        }

        public function __toString(): string
        {
            $desc = "ID: $this->id";
            $desc .= "<br />Title: $this->title";
            $desc .= "<br />Author: $this->author";
            $desc .= "<br />Voice: $this->voice";
            $desc .= "<br />Imagine: $this->img";
            $desc .= "<br />Link: $this->link";
            $this->recent?
                $desc .= "<br /><strong>New!</strong>":
                null;
            $desc .= "<hr />";

            return $desc;
        }

        public function __clone()
        {
            $this->id = 0;
        }

        public function jsonSerialize()
        {
            $vars = get_object_vars($this);
    
            return $vars;
        }
    }
