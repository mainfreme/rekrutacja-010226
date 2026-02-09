<?php

declare(strict_types=1);

namespace App\Catalog\Application\Service;

use App\Catalog\Domain\Service\Import\ImportStrategyInterface;
use App\Identity\Domain\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;


class DataImporter
{
    private iterable $strategies;

    public function __construct(
        // Pobieramy wszystkie strategie oznaczone tagiem z warstwy Infrastructure
        #[TaggedIterator('app.import_strategy')] iterable $strategies
    ) {
        $this->strategies = $strategies;
    }

    /**
     * @param User $user
     * @param string $sourceName
     * @return array
     */
    public function execute(User $user, string $sourceName): array
    {
        $strategy = $this->findStrategy($sourceName);

        return $strategy->import($user);
    }

    private function findStrategy(string $source): ImportStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($source)) {
                return $strategy;
            }
        }

        throw new \InvalidArgumentException(sprintf('Niestety, źródło "%s" nie jest obsługiwane.', $source));
    }
}
