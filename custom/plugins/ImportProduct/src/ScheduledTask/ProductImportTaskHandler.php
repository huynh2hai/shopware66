<?php

namespace ImportProduct\ScheduledTask;

use ImportProduct\Core\Content\ProductImportLog\ProductImportLogEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: ProductImportTask::class)]
class ProductImportTaskHandler extends ScheduledTaskHandler
{
    private ?EntityRepository $productImportLogRepository = null;
    private ?Application $application = null;

    public function run(): void
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('status', 'init'));

        /** @var ProductImportLogEntity $item */
        $item = $this->productImportLogRepository->search($criteria, Context::createDefaultContext())->first();

        if (!$item) {
            return;
        }

        $this->productImportLogRepository->update([[
            'id' => $item->getUniqueIdentifier(),
            'status' => 'processing'
        ]], Context::createDefaultContext());

        // Prepare input for the command (e.g., cache:clear)
        $input = new ArrayInput([
            'command' => 'cache:clear', // The command name
            'filePath' => $item->getFileName(),      // Optional arguments/options
        ]);

        // Use BufferedOutput to capture the command output if needed
        $output = new BufferedOutput();

        // Find and run the command
        $command = $this->application->find('import:product');
        $command->run($input, $output);

        // Optionally, log the output or handle it
        $commandOutput = $output->fetch();


        $this->productImportLogRepository->update([[
            'id' => $item->getUniqueIdentifier(),
            'importDetails' => ['output' => $commandOutput],
            'status' => 'done'
        ]], Context::createDefaultContext());
    }

    public function setProductImportLogRepository(?EntityRepository $productImportLogRepository): void
    {
        $this->productImportLogRepository = $productImportLogRepository;
    }



    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }
}
