<?php

namespace model;

/**
 *
 */
class Review
{
    private $title;
    private $author;
    private $plot;
    private $text;
    private $avg;
    private $style;
    private $content;
    private $pleasantness;

    // Constructor
    public function __construct(
        string $title,
        string $author,
        string $plot,
        string $text,
        float $avg,
        float $style,
        float $content,
        float $pleasantness
    )
    {
        $this->title = $title;
        $this->author = $author;
        $this->plot = $plot;
        $this->text = $text;
        $this->avg = $avg;
        $this->style = $style;
        $this->content = $content;
        $this->pleasantness = $pleasantness;
    }

    // getters
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPlot(): string
    {
        return $this->plot;
    }

    public function getText(): float
    {
        return $this->text;
    }

    public function getAvg(): float
    {
        return $this->avg;
    }

    public function getStyle(): float
    {
        return $this->style;
    }

    public function getContent(): float
    {
        return $this->content;
    }

    public function getPleasantness(): float
    {
        return $this->pleasantness;
    }

    // setters
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }
    public function setPlot(string $plot)
    {
        $this->plot = $plot;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function setAvg(float $avg)
    {
        $this->avg = $avg;
    }

    public function setStyle(float $style)
    {
        $this->style = $style;
    }

    public function setContent(float $content)
    {
        $this->content = $content;
    }

    public function setPleasantness(float $pleasantness)
    {
        $this->pleasantness = $pleasantness;
    }

    public function __toString(): string
    {
        $desc = "<br />Title: $this->title";
        $desc .= "<br />Author: $this->author";
        $desc .= "<br />Plot: $this->plot";
        $desc .= "<br />Text: $this->text";
        $desc .= "<br />Avg: $this->avg";
        $desc .= "<br />Style: $this->style";
        $desc .= "<br />Content: $this->content";
        $desc .= "<br />Pleasentness: $this->pleasantness";
        $desc .= "<hr />";

        return $desc;
    }
}
