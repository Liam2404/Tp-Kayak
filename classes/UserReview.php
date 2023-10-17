<?php 

class UserReview {

    protected int $id;
    protected string $message;
    protected int $tour_operator_id;
    protected int $author_id;
    


    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the value of message
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

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
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * Set the value of author_id
     */
    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }
}