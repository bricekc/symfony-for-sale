<?php

namespace App\Factory;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Faker\Factory;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;
use DateTimeImmutable;

/**
 * @extends ModelFactory<Advertisement>
 *
 * @method        Advertisement|Proxy                     create(array|callable $attributes = [])
 * @method static Advertisement|Proxy                     createOne(array $attributes = [])
 * @method static Advertisement|Proxy                     find(object|array|mixed $criteria)
 * @method static Advertisement|Proxy                     findOrCreate(array $attributes)
 * @method static Advertisement|Proxy                     first(string $sortedField = 'id')
 * @method static Advertisement|Proxy                     last(string $sortedField = 'id')
 * @method static Advertisement|Proxy                     random(array $attributes = [])
 * @method static Advertisement|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AdvertisementRepository|RepositoryProxy repository()
 * @method static Advertisement[]|Proxy[]                 all()
 * @method static Advertisement[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Advertisement[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Advertisement[]|Proxy[]                 findBy(array $attributes)
 * @method static Advertisement[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Advertisement[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AdvertisementFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $faker = Factory::create();
        return [
            'createdAt' => DateTimeImmutable::createFromMutable($faker->dateTime()),
            'description' => $faker->text(),
            'location' => $faker->text(100),
            'price' => $faker->randomNumber(),
            'title' => $faker->text(100),
            'category' => CategoryFactory::new(),
            'owner' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Advertisement $advertisement): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Advertisement::class;
    }
}
