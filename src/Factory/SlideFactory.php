<?php

namespace App\Factory;

use App\Entity\Slide;
use App\Repository\SlideRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Slide>
 *
 * @method static Slide|Proxy createOne(array $attributes = [])
 * @method static Slide[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Slide|Proxy find(object|array|mixed $criteria)
 * @method static Slide|Proxy findOrCreate(array $attributes)
 * @method static Slide|Proxy first(string $sortedField = 'id')
 * @method static Slide|Proxy last(string $sortedField = 'id')
 * @method static Slide|Proxy random(array $attributes = [])
 * @method static Slide|Proxy randomOrCreate(array $attributes = [])
 * @method static Slide[]|Proxy[] all()
 * @method static Slide[]|Proxy[] findBy(array $attributes)
 * @method static Slide[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Slide[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static SlideRepository|RepositoryProxy repository()
 * @method Slide|Proxy create(array|callable $attributes = [])
 */
final class SlideFactory extends ModelFactory
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
            'Bild' => self::faker()->text(),
            'Text' => self::faker()->text(),
            'Reihenfolge' => self::faker()->randomNumber(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Slide $slide): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Slide::class;
    }
}
