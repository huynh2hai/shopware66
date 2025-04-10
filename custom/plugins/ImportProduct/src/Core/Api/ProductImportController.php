<?php

namespace ImportProduct\Core\Api;

use ImportProduct\Service\ProductImportService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
class ProductImportController extends AbstractController
{
    public function __construct(
        private readonly ProductImportService $productImportService,
        private readonly EntityRepository $productImportLogRepository
    ) {
    }
    #[Route(path: '/api/product-import/validate', name: 'product-import.validate', methods: ['POST'])]
    public function validate(Request $request): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');


        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }


        $errors = $this->productImportService->validateCsv($file);

        return new JsonResponse([
            'valid' => empty($errors),
            'errors' => $errors
        ]);
    }

    #[Route(path: '/api/product-import/import', name: 'product-import.import', methods: ['POST'])]
    public function import(Request $request, Context $context): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $tempPath = sys_get_temp_dir() . '/' . uniqid('import_') . '.csv';
        $file->move(sys_get_temp_dir(), basename($tempPath));

        $id = Uuid::randomHex();

        $data[] = [
            'id' => $id,
            'fileName' => $tempPath,
            'status' => 'init',
            'totalRecords' => 0,
            'successRecords' => 0,
            'failedRecords' => 0
        ];

        $this->productImportLogRepository->create($data, $context);

        return new JsonResponse(['id' => $id]);
    }
}
