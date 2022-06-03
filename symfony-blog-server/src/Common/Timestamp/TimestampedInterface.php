<?php

namespace App\Common\Timestamp;

use DateTimeInterface;

interface TimestampedInterface
{
    public function getCreatedAt(): DateTimeInterface;
    public function setCreatedAt(DateTimeInterface $createdAt): self;
    public function getUpdatedAt(): DateTimeInterface;
    public function setUpdatedAt(DateTimeInterface $updatedAt): self;
}