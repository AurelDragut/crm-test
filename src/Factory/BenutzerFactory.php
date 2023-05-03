<?php

namespace App\Factory;

use App\Entity\Benutzer;
use App\Repository\BenutzerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Benutzer>
 *
 * @method static Benutzer|Proxy createOne(array $attributes = [])
 * @method static Benutzer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Benutzer|Proxy find(object|array|mixed $criteria)
 * @method static Benutzer|Proxy findOrCreate(array $attributes)
 * @method static Benutzer|Proxy first(string $sortedField = 'id')
 * @method static Benutzer|Proxy last(string $sortedField = 'id')
 * @method static Benutzer|Proxy random(array $attributes = [])
 * @method static Benutzer|Proxy randomOrCreate(array $attributes = [])
 * @method static Benutzer[]|Proxy[] all()
 * @method static Benutzer[]|Proxy[] findBy(array $attributes)
 * @method static Benutzer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Benutzer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BenutzerRepository|RepositoryProxy repository()
 * @method Benutzer|Proxy create(array|callable $attributes = [])
 */
final class BenutzerFactory extends ModelFactory
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
            'email' => self::faker()->text(),
            'roles' => ['ROLE_USER'],
            'password' => self::faker()->text(),
            'Name' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Benutzer $admin): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Benutzer::class;
    }
}
