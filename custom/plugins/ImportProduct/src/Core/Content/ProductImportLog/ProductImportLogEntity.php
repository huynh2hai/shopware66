<?php declare(strict_types=1);

namespace ImportProduct\Core\Content\ProductImportLog;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ProductImportLogEntity extends Entity
{
    use EntityIdTrait;

    protected string $fileName;
    protected string $status;
    protected ?string $errorMessage;
    protected int $totalRecords;
    protected int $successRecords;
    protected int $failedRecords;
    protected array $importDetails;

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
    }

    public function getSuccessRecords(): int
    {
        return $this->successRecords;
    }

    public function setSuccessRecords(int $successRecords): void
    {
        $this->successRecords = $successRecords;
    }

    public function getFailedRecords(): int
    {
        return $this->failedRecords;
    }

    public function setFailedRecords(int $failedRecords): void
    {
        $this->failedRecords = $failedRecords;
    }

    public function getImportDetails(): array
    {
        return $this->importDetails;
    }

    public function setImportDetails(array $importDetails): void
    {
        $this->importDetails = $importDetails;
    }
}
