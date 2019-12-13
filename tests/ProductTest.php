<?php
/**
 * This file is part of the write-core package.
 *
 * (c) Solvee
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

/**
 * Class ProductTest
 *
 * @author Paweł Zwoliński <p.zwolin@gmail.com>
 */
class ProductTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/games');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains(['@context' => '/api/contexts/games',
            '@context' => '/api/contexts/games',
            '@id' => '/api/games',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
            'hydra:view' => [
                '@id' => '/api/games?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/games?page=1',
                'hydra:last' => '/api/games?page=2',
                'hydra:next' => '/api/games?page=2',
            ],
        ]);

        $this->assertCount(3, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Product::class);
    }
}


