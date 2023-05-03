<?php

namespace App\Factory;

use App\Entity\Seite;
use App\Repository\SeiteRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Seite>
 *
 * @method static Seite|Proxy createOne(array $attributes = [])
 * @method static Seite[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Seite|Proxy find(object|array|mixed $criteria)
 * @method static Seite|Proxy findOrCreate(array $attributes)
 * @method static Seite|Proxy first(string $sortedField = 'id')
 * @method static Seite|Proxy last(string $sortedField = 'id')
 * @method static Seite|Proxy random(array $attributes = [])
 * @method static Seite|Proxy randomOrCreate(array $attributes = [])
 * @method static Seite[]|Proxy[] all()
 * @method static Seite[]|Proxy[] findBy(array $attributes)
 * @method static Seite[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Seite[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static SeiteRepository|RepositoryProxy repository()
 * @method Seite|Proxy create(array|callable $attributes = [])
 */
final class SeiteFactory extends ModelFactory
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
            'Titel' => self::faker()->text(),
            'MetaBeschreibung' => self::faker()->text(),
            'Inhalt' => self::faker()->text(),
            'Schnecke' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Seite $seite): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Seite::class;
    }
}
