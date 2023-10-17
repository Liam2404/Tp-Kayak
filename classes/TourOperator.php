<?php 

class TourOperator {
    protected int $id;
    protected string $name;
    protected string $link;
    protected bool $is_premium;

    public function __construct(int $id, string $name, string $link, bool $is_premium) {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->is_premium = $is_premium;
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
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of link
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Set the value of link
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get the value of is_premium
     */
    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    /**
     * Set the value of is_premium
     */
    public function setIsPremium(bool $is_premium): self
    {
        $this->is_premium = $is_premium;

        return $this;
    }
}