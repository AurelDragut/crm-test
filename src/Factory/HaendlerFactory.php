<?php

namespace App\Factory;

use App\Entity\Haendler;
use App\Repository\HaendlerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Haendler>
 *
 * @method static Haendler|Proxy createOne(array $attributes = [])
 * @method static Haendler[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Haendler|Proxy find(object|array|mixed $criteria)
 * @method static Haendler|Proxy findOrCreate(array $attributes)
 * @method static Haendler|Proxy first(string $sortedField = 'id')
 * @method static Haendler|Proxy last(string $sortedField = 'id')
 * @method static Haendler|Proxy random(array $attributes = [])
 * @method static Haendler|Proxy randomOrCreate(array $attributes = [])
 * @method static Haendler[]|Proxy[] all()
 * @method static Haendler[]|Proxy[] findBy(array $attributes)
 * @method static Haendler[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Haendler[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static HaendlerRepository|RepositoryProxy repository()
 * @method Haendler|Proxy create(array|callable $attributes = [])
 */
final class HaendlerFactory extends ModelFactory
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
            'Name' => self::faker()->text(),
            'Bild' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Haendler $haendler): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Haendler::class;
    }
}
