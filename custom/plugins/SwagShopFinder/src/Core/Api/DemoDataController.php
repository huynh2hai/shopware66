<?php

namespace SwagShopFinder\Core\Api;

use Faker\Factory;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Swag\PayPal\Pos\Setting\Exception\CountryNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

#[Route(defaults: ['_routeScope' => ['api']])]
class DemoDataController extends AbstractController
{
    public function __construct(
        private EntityRepository $countryRepository,
        private EntityRepository $swagShopFinderRepository,
    ) {
    }

    #[Route(path: '/api/v{version}/_action/swag-shop-finder/generate', name: 'api.custom.swag_shop_finder.generate', methods: ['POST'])]
    public function generate(Context $context): Response
    {
        $faker = Factory::create();
        $country = $this->getActiveCountry($context);
        $data = [];

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => Uuid::randomHex(),
                'active' => true,
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'url' => $faker->url,
                'telephone' => $faker->phoneNumber,
                'postCode' => $faker->postcode,
                'city' => $faker->city,
                'countryId' => $country->getId()
            ];
        }

        $this->swagShopFinderRepository->create($data, $context);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function getActiveCountry(Context $context): CountryEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(1);

        $country = $this->countryRepository->search($criteria, $context)->getEntities()->first();
        if ($country === null) {
            throw new CountryNotFoundException('');
        }

        return $country;
    }
}
