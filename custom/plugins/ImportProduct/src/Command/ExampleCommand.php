<?php declare(strict_types=1);

namespace ImportProduct\Command;

use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerEntity;
use Shopware\Core\Content\Product\ProductVariationBuilder;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Tag\TagEntity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'import:product',
    description: 'Add a short description for your command',
)]
class ExampleCommand extends Command
{
    private array $products = [];
    private array $manufacturers = [];

    private array $tags = [];
    private array $categories = [];
    private array $properties = [];
    private array $propertyOptions = [];

    private array $idMapping = [];

    private array $batch = [];

    private array $productVariants = [];


    public function __construct(
        private readonly EntityRepository $productManufacturerRepository,
        private readonly EntityRepository $productRepository,
        private readonly EntityRepository $tagRepository,
        private readonly EntityRepository $categoryRepository,
        private readonly EntityRepository $propertyGroupRepository,
        private readonly EntityRepository $propertyGroupOptionRepository,
        private readonly ProductVariationBuilder $productVariationBuilder,
        private readonly EntityRepository $productConfiguratorSettingRepository
    ) {
        parent::__construct();
    }


    // Provides a description, printed out in bin/console
    protected function configure(): void
    {
        $this->setDescription('Does something very special.');
        $this->addArgument('filePath', InputArgument::REQUIRED);
    }

    // Actual code executed in the command
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');
        $progressBar = new ProgressBar($output);

        $productUpdated = 0;
        $productCreated = 0;

        $this->preloadData($filePath);

        $file = fopen($filePath, 'r');
        if ($file !== false) {
            // Read and ignore the header if your CSV has one
            fgetcsv($file);

            $count = 0;
            while (($row = fgetcsv($file)) !== false) {

                $count++;
                $progressBar->advance(1);

                if ($count % 1300 == 0) {
                    $this->processBatch();
                }

                if(isset($this->products[$row[0]])) {
                    $productId = $this->products[$row[0]];
                    $productUpdated++;
                } else {
                    $productId = Uuid::randomHex();
                    $productCreated++;
                }


                $this->batch[] = [
                    'id' => $productId,
                    'productNumber' => $row[0],
                    'name' => $row[1],
                    'description' => $row[2],
                    'price' => [[
                        'gross' => $row[3],
                        'net' => $row[3],
                        'linked' => true,
                        'currencyId' => Defaults::CURRENCY,
                    ]],
                    'categories' => [[
                        'id' => $this->categories[$row[4]]
                    ]],
                    'manufacturer' => [
                        'id' => $this->createManufacturer($row[8]),
                    ],
                    'tags' => $this->createTags(explode(';', $row[9])),
                    'taxId' => '019517c1f64b73d886b3299e2d16183d',
                    'active' => false,
                    'stock' => 0,
                    'options' => $this->getPropertyids($row[7]),
                    'properties' => $this->getPropertyids($row[7])
                ];
            }
            fclose($file);
            $this->processBatch();


            $products = $this->productRepository->search(new Criteria(), Context::createDefaultContext());

            foreach ($products as $product) {
                $this->productVariationBuilder->build($product, Context::createDefaultContext());
            }


            $progressBar->finish();
        } else {
            echo "Could not open the file.";
        }


        $output->writeln('');

        $output->writeln("Created: $productCreated; Updated: $productUpdated");

        return 0;
    }

    private function processBatch()
    {
        $this->productRepository->upsert($this->batch, Context::createDefaultContext());


        $this->batch = [];
        $this->productVariants = [];
    }


    private function createManufacturer(string $name): string
    {
        if(isset($this->manufacturers[$name])) {
            return $this->manufacturers[$name];
        }

        $id = Uuid::randomHex();
        $this->productManufacturerRepository->create([[
            'id' => $id,
            'name' => $name
        ]], Context::createDefaultContext());

        $this->manufacturers[$name] = $id;

        return  $id;
    }

    private function createTags(array $names): array
    {
        $results = [];

        foreach ($names as $name) {
            $results[] = [
                'id' => $this->createTag($name)
            ];
        }

        return $results;
    }

    private function createTag(string $name): string
    {
        if(isset($this->tags[$name])) {
            return $this->tags[$name];
        }

        $id = Uuid::randomHex();
        $this->tagRepository->create([[
            'id' => $id,
            'name' => $name
        ]], Context::createDefaultContext());

        $this->tags[$name] = $id;

        return  $id;
    }

    private function preloadData(string $filePath)
    {
        $this->preloadProduct();
        $this->preloadProperty();
        $this->preloadPropertyOption();
        $this->preloadCategory($filePath);
        $this->preloadManufacturer();
        $this->preloadTag();
    }

    private function preloadManufacturer()
    {
        $results = $this->productManufacturerRepository->search(new Criteria(), Context::createDefaultContext());

        /** @var ProductManufacturerEntity $result */
        foreach ($results as $result) {
            $this->manufacturers[$result->getName()] = $result->getId();
        }
    }

    private function preloadProduct()
    {
        $results = $this->productRepository->searchIds(new Criteria(), Context::createDefaultContext());

//        dd($results);

        /** @var array $result */
        foreach ($results->getData() as $result) {
//            dd($result);
            $this->products[$result['productNumber']] = $result['id'];
        }

    }

    private function preloadTag()
    {
        $results = $this->tagRepository->search(new Criteria(), Context::createDefaultContext());

        /** @var TagEntity $result */
        foreach ($results as $result) {
            $this->tags[$result->getName()] = $result->getId();
        }
    }

    private function preloadProperty(): void
    {
        $results = $this->propertyGroupRepository->search(new Criteria(), Context::createDefaultContext());

        /** @var TagEntity $result */
        foreach ($results as $result) {
            $this->properties[$result->getName()] = $result->getId();
        }
    }

    private function preloadPropertyOption(): void
    {
        $results = $this->propertyGroupOptionRepository->search(new Criteria(), Context::createDefaultContext());

        /** @var TagEntity $result */
        foreach ($results as $result) {
            $this->propertyOptions[$result->getName()] = $result->getId();
        }
    }

    private function getPropertyids(string $variant)
    {
        $data = [];
        $properties = explode(';', $variant);

        foreach ($properties as $property) {
            $options = explode(':', $property);

            if(!isset($this->properties[$options[0]])) {
                $id = Uuid::randomHex();
                $this->propertyGroupRepository->create([[
                    'id' => $id,
                    'name' => $options[0]
                ]], Context::createDefaultContext());

                $this->properties[$options[0]] = $id;
            }

            if(!isset($this->propertyOptions[$options[1]])) {
                $optionId = Uuid::randomHex();
                $this->propertyGroupOptionRepository->create([[
                    'id' => $optionId,
                    'groupId' => $this->properties[$options[0]],
                    'name' => $options[1]
                ]], Context::createDefaultContext());

                $this->propertyOptions[$options[1]] = $optionId;

                $data[] = [
                    'id' => $optionId

                ];
            } else {
                $data[] = [
                    'id' => $this->propertyOptions[$options[1]],
                ];
            }
        }

        return $data;
    }

    private function getPropertyids2(string $variant)
    {
        $data = [];
        $properties = explode(';', $variant);

        foreach ($properties as $property) {
            $options = explode(':', $property);

            if(!isset($this->properties[$options[0]])) {
                $id = Uuid::randomHex();
                $this->propertyGroupRepository->create([[
                    'id' => $id,
                    'name' => $options[0]
                ]], Context::createDefaultContext());

                $this->properties[$options[0]] = $id;
            }

            if(!isset($this->propertyOptions[$options[1]])) {
                $optionId = Uuid::randomHex();
                $this->propertyGroupOptionRepository->create([[
                    'id' => $optionId,
                    'groupId' => $this->properties[$options[0]],
                    'name' => $options[1]
                ]], Context::createDefaultContext());

                $this->propertyOptions[$options[1]] = $optionId;

                $data[] = [
                    'id' => $optionId,
                    'expressionForListings' => true,
                    'representation' => 'box',
                ];
            } else {
                $data[] = [
                    'id' => $this->propertyOptions[$options[1]],
                    'expressionForListings' => true,
                    'representation' => 'box',
                ];
            }
        }

        return $data;
    }

    private function preloadCategory(string $filePath)
    {
        $context = Context::createDefaultContext();
        $categoriesToCreate = [];

        if (($file = fopen($filePath, 'r')) === false) {
            throw new \Exception('Could not open CSV file: ');
        }

        $headers = fgetcsv($file, 0, ',');

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($headers, $row);

            $name = $data['category_name'];
            if (isset($this->categories[$name])) {
                continue;
            }

            $legacyCategoryId = $data['category_id'];
            $legacyParentCategoryId = $data['parent_category_id'] ?? null;

            if (isset($this->idMapping[$legacyCategoryId])) {
                continue;
            }

            $existingCategory = $this->findCategory($name, $context);

            if ($existingCategory) {
                $this->categories[$name] = $existingCategory->getId();
                $this->idMapping[$legacyCategoryId] = $existingCategory->getId();
            } else {
                $newCategoryId = Uuid::randomHex();

                $this->idMapping[$legacyCategoryId] = $newCategoryId;
                $this->categories[$name] = $newCategoryId;

                $categoryData = [
                    'id' => $newCategoryId,
                    'name' => $name,
                    'active' => true
                ];

                $categoriesToCreate[] = [
                    'data' => $categoryData,
                    'legacyCategoryId' => $legacyCategoryId,
                    'legacyParentCategoryId' => $legacyParentCategoryId,
                ];
            }

        }

        if (!empty($categoriesToCreate)) {
            $categoryDataBatch = array_column($categoriesToCreate, 'data');
            $this->categoryRepository->create($categoryDataBatch, $context);
        }

        // Step 2: Second pass - Update parentIds using the mapping
        $categoriesToUpdate = [];
        foreach ($categoriesToCreate as $category) {
            $legacyParentCategoryId = $category['legacyParentCategoryId'];
            $categoryId = $this->idMapping[$category['legacyCategoryId']];

            if ($legacyParentCategoryId && isset($this->idMapping[$legacyParentCategoryId])) {
                // Map the legacy parent ID to the new Shopware ID
                $parentId = $this->idMapping[$legacyParentCategoryId];

                if ($categoryId !== $parentId) {
                    $categoriesToUpdate[] = [
                        'id' => $categoryId,
                        'parentId' => $parentId,
                    ];
                }
            }
        }

        // Update parentIds in bulk
        if (!empty($categoriesToUpdate)) {
            $this->categoryRepository->update($categoriesToUpdate, $context);
        }

        fclose($file);
    }

    private function findCategory(string $name, Context $context): ?CategoryEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));

        return $this->categoryRepository->search($criteria, $context)->first();
    }
}
