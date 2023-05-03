<?php

namespace App\Factory;

use App\Entity\MenuePunkt;
use App\Repository\MenuePunktRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<MenuePunkt>
 *
 * @method static MenuePunkt|Proxy createOne(array $attributes = [])
 * @method static MenuePunkt[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static MenuePunkt|Proxy find(object|array|mixed $criteria)
 * @method static MenuePunkt|Proxy findOrCreate(array $attributes)
 * @method static MenuePunkt|Proxy first(string $sortedField = 'id')
 * @method static MenuePunkt|Proxy last(string $sortedField = 'id')
 * @method static MenuePunkt|Proxy random(array $attributes = [])
 * @method static MenuePunkt|Proxy randomOrCreate(array $attributes = [])
 * @method static MenuePunkt[]|Proxy[] all()
 * @method static MenuePunkt[]|Proxy[] findBy(array $attributes)
 * @method static MenuePunkt[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static MenuePunkt[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static MenuePunktRepository|RepositoryProxy repository()
 * @method MenuePunkt|Proxy create(array|callable $attributes = [])
 */
final class MenuePunktFactory extends ModelFactory
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
            'position' => self::faker()->randomNumber(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(MenuePunkt $menuePunkt): void {})
        ;
    }

    protected static function getClass(): string
    {
        return MenuePunkt::class;
    }
}
