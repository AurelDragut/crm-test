<?php

namespace App\Factory;

use App\Entity\Grund;
use App\Repository\GrundRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Grund>
 *
 * @method static Grund|Proxy createOne(array $attributes = [])
 * @method static Grund[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Grund|Proxy find(object|array|mixed $criteria)
 * @method static Grund|Proxy findOrCreate(array $attributes)
 * @method static Grund|Proxy first(string $sortedField = 'id')
 * @method static Grund|Proxy last(string $sortedField = 'id')
 * @method static Grund|Proxy random(array $attributes = [])
 * @method static Grund|Proxy randomOrCreate(array $attributes = [])
 * @method static Grund[]|Proxy[] all()
 * @method static Grund[]|Proxy[] findBy(array $attributes)
 * @method static Grund[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Grund[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GrundRepository|RepositoryProxy repository()
 * @method Grund|Proxy create(array|callable $attributes = [])
 */
final class GrundFactory extends ModelFactory
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
            'Inhalt' => self::faker()->text(),
            'ZielEmail' => self::faker()->text(),
            'Titel' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Grund $grund): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Grund::class;
    }
}
