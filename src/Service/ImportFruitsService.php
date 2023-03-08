<?php

namespace App\Service;

use App\Entity\FruitNutritions;
use App\Entity\Fruits;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportFruitsService
{
    private EntityManagerInterface $em;
    private HttpClientInterface $client;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStoredFruits(): int
    {
        return $this->em->getRepository(Fruits::class)
            ->createQueryBuilder('fruits')
            ->select('count(fruits.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getFruits(): array
    {
        $fruitsObj = $this->client->request(
            'GET',
            'https://fruityvice.com/api/fruit/all'
        );
        $statusCode = $fruitsObj->getStatusCode();
        $contentType = $fruitsObj->getHeaders()['content-type'][0];

        return $fruitsObj->toArray();
    }

    public function saveFruit($fruit)
    {
        $fruitData = new Fruits();
        $nutritionData = new FruitNutritions();
        $nutritionArray = $fruit["nutritions"];

        $fruitData->setFvId($fruit["id"]);
        $fruitData->setName($fruit["name"]);
        $fruitData->setFamily($fruit["family"]);
        $fruitData->setFruitOrder($fruit["order"]);
        $fruitData->setGenus($fruit["genus"]);
        $fruitData->setStatus(1);
        $fruitData->setFnId($nutritionData);

        $nutritionData->setCarbohydrates($nutritionArray["carbohydrates"]);
        $nutritionData->setProtein($nutritionArray["protein"]);
        $nutritionData->setFat($nutritionArray["fat"]);
        $nutritionData->setCalories($nutritionArray["calories"]);
        $nutritionData->setSugar($nutritionArray["sugar"]);
        $nutritionData->setStatus(1);
        $nutritionData->setFruit($fruitData);

        $this->em->persist($fruitData);
        $this->em->persist($nutritionData);
        $this->em->flush();
    }
}