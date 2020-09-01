<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Model;

/**
 * Class CommitModel
 *
 * @codeCoverageIgnore
 *
 * @author Jonah Böther
 */
class CommitModel
{
    /** @var string */
    private $commitId;
    /** @var string */
    private $author;
    /** @var string */
    private $email;
    /** @var \DateTime */
    private $dateTime;
    /** @var bool */
    private $isMerge;
    /** @var string */
    private $message;

    public function getCommitId(): string
    {
        return $this->commitId;
    }

    public function setCommitId(string $commitId): CommitModel
    {
        $this->commitId = $commitId;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): CommitModel
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CommitModel
    {
        $this->email = $email;

        return $this;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): CommitModel
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function isMerge(): bool
    {
        return $this->isMerge ?? false;
    }

    public function setIsMerge(bool $isMerge): CommitModel
    {
        $this->isMerge = $isMerge;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): CommitModel
    {
        $this->message = $message;

        return $this;
    }
}
