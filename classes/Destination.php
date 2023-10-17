<?php 

class Destination {
    protected int $id;
    protected string $location;
    protected int $price;
    protected string $picture;
    protected int $tour_operator_id;

    public function __construct(int $id, string $location, int $price, string $picture, int $tour_operator_id) {
        $this->id = $id;
        $this->location = $location;
        $this->price = $price;
        $this->picture = $picture;
        $this->tour_operator_id = $tour_operator_id;
    }

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
     * Get the value of location
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Set the value of location
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Set the value of price
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;

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
     * Get the value of picture
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}