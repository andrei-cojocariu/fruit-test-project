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
    public function importFruits(): array
    {
        $fruitsObj = $this->client->request(
            'GET',
            'https://fruityvice.com/api/fruit/all'
        );
        $statusCode = $fruitsObj->getStatusCode();
        $contentType = $fruitsObj->getHeaders()['content-type'][0];

        return $fruitsObj->toArray();
    }

    /**
     * @param array $fruit
     * @return void
     */
    public function saveFruit(array $fruit): void
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

    /**
     * @return array
     */
    public function showCommandMenu(): array
    {
        $menuItem[] = "1. Show Fruits";
        $menuItem[] = "2. Sort by";
        $menuItem[] = "3. Show Favorite Fruits";
        $menuItem[] = "4. Add to favorites";
        $menuItem[] = "5. Check For New Fruits";
        $menuItem[] = "6. Redo DataBase";

        return $menuItem;
    }

    /**
     * @return array
     */
    public function getFruitTableHeader(): array
    {
        $headerData[] = "ID.";
        $headerData[] = "Fruit Name";
        $headerData[] = "Fruit Family";
        $headerData[] = "Fruit Order";
        $headerData[] = "Fruit Genus";
        $headerData[] = "Nutrition Data";
        $headerData[] = "Updated At";
        $headerData[] = "Created At";
        $headerData[] = "Status";

        return $headerData;
    }

    /**
     * @param $fruitNutrition
     * @return string
     */
    private function getFruitTableNutritionData($fruitNutrition) : string
    {
        $nutritionData  = "Carbohydrates " . $fruitNutrition->getCarbohydrates() . "\n";
        $nutritionData .= "      Protein " . $fruitNutrition->getProtein() . "\n";
        $nutritionData .= "          Fat " . $fruitNutrition->getFat() . "\n";
        $nutritionData .= "     Calories " . $fruitNutrition->getCalories() . "\n";
        $nutritionData .= "        Sugar " . $fruitNutrition->getSugar() . "\n";

        return $nutritionData;
    }

    /**
     * @return array
     */
    public function getFruitTableData(): array
    {
        $fruits = $this->em->getRepository(Fruits::class)->findAll();
        $fruitsData = array();

        foreach ($fruits as $fruit) {
            $fruitsData[] = array(
                $fruit->getID(),
                $fruit->getName(),
                $fruit->getFamily(),
                $fruit->getFruitOrder(),
                $fruit->getGenus(),
                $this->getFruitTableNutritionData($fruit->getFnId()),
                $fruit->getDateUpdated()->format('Y-m-d H:i:s'),
                $fruit->getDateCreated()->format('Y-m-d H:i:s'),
                $fruit->getStatus()
            );
        }

        return $fruitsData;
    }
}