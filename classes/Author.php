<?php

class Author {
    protected int $id;
    protected string $name;

    /**
     * Get the value of id
     */
    public function getAuthorId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setAuthorId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getAuthorName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setAuthorName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}