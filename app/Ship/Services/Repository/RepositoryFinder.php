<?php

declare(strict_types=1);

namespace App\Ship\Services\Repository;

use Illuminate\Support\Facades\File;
use SplFileInfo;

final class RepositoryFinder
{
    /**
     * @return array
     */
    public function getContractsToRepository(): array
    {
        $result = [];
        $allFiles = File::allFiles(app_path('Containers'));
        foreach ($allFiles as $file) {
            if ($this->isContractFile($file) && $this->hasContractLinkToRepository($file, $allFiles)) {
                $NamespaceWithClassWithoutContract = str_replace('Contract', '', $this->getNamespaceWithClassForClassFile($file));
                $result[$this->getNamespaceWithClassForClassFile($file)] = $NamespaceWithClassWithoutContract;
            }
        }

        return $result;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return bool
     */
    private function isContractFile(SplFileInfo $file): bool
    {
        return str_contains($file->getFilename(), 'Contract.php');
    }

    /**
     * @param SplFileInfo $needFile
     * @param array $files
     *
     * @return bool
     */
    private function hasContractLinkToRepository(SplFileInfo $needFile, array $files): bool
    {
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $fileNameWithoutContact = str_replace('Contract', '', $needFile->getFilename());

            if ($needFile->getPath() === $file->getPath() && $fileNameWithoutContact === $file->getFilename()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    private function getNamespaceWithClassForClassFile(SplFileInfo $file): string
    {
        $namespace = str_replace([app_path(), '/'], ['App', '\\'], $file->getPath()) . '\\';
        $nameWithoutExtension = str_replace('.' . $file->getExtension(), '', $file->getFilename());

        return $namespace . $nameWithoutExtension;
    }
}
