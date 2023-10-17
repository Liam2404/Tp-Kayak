<?php

class Score {
    protected int $id;
    protected int $value;
    protected int $tour_operator_id;
    protected int $author_id;

    /**
     * Get the value of id
     */
    public function getScoreId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setScoreId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of value
     */
    public function getScoreValue(): int
    {
        return $this->value;
    }

    /**
     * Set the value of value
     */
    public function setScoreValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of tour_operator_id
     */
    public function getTourOperatorId(): int
    {
        return $this->tour_operator_id;
    }

    /**
     * Set the value of tour_operator_id
     */
    public function setTourOperatorId(int $tour_operator_id): self
    {
        $this->tour_operator_id = $tour_operator_id;

        return $this;
    }

    /**
     * Get the value of author_id
     */
    public function getScoreAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * Set the value of author_id
     */
    public function setScoreAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }
}