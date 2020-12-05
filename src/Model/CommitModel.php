<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Model;

/**
 * Provides information about a commit
 *
 * @author Jonah Böther
 */
class CommitModel
{
    private string $commitId;
    private string $author;
    private string $email;
    private \DateTime $dateTime;
    private bool $isMerge;
    private string $message;

    public function getCommitId(): string
    {
        return $this->commitId;
    }

    public function setCommitId(string $commitId): self
    {
        $this->commitId = $commitId;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime ?? new \DateTime();
    }

    public function setDateTime(\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function isMerge(): bool
    {
        return $this->isMerge ?? false;
    }

    public function setIsMerge(bool $isMerge): self
    {
        $this->isMerge = $isMerge;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
