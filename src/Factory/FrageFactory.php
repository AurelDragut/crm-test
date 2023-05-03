<?php

namespace App\Factory;

use App\Entity\Frage;
use App\Repository\FrageRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Frage>
 *
 * @method static Frage|Proxy createOne(array $attributes = [])
 * @method static Frage[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Frage|Proxy find(object|array|mixed $criteria)
 * @method static Frage|Proxy findOrCreate(array $attributes)
 * @method static Frage|Proxy first(string $sortedField = 'id')
 * @method static Frage|Proxy last(string $sortedField = 'id')
 * @method static Frage|Proxy random(array $attributes = [])
 * @method static Frage|Proxy randomOrCreate(array $attributes = [])
 * @method static Frage[]|Proxy[] all()
 * @method static Frage[]|Proxy[] findBy(array $attributes)
 * @method static Frage[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Frage[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FrageRepository|RepositoryProxy repository()
 * @method Frage|Proxy create(array|callable $attributes = [])
 */
final class FrageFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'Frage' => self::faker()->text(),
            'Antwort' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Frage $frage): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Frage::class;
    }
}
