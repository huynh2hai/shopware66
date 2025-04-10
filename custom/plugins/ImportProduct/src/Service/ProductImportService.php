<?php

namespace ImportProduct\Service;

class ProductImportService
{
    private array $requiredFields = [
        'product_number',
        'name',
        'description',
        'price',
        'category_name',
        'category_id',
        'parent_category_id',
        'variant',
        'manufacturer',
        'tag'
    ];


    public function validateCsv(string $filePath): array
    {
        $errors = [];
        $error = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [[
                'row' => 1,
                'error' => 'Invalid file'
            ]];
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return [[
                'row' => 1,
                'errors' => ['Empty or invalid CSV file']
            ]];
        }

        foreach ($this->requiredFields as $requiredField) {
            if(!in_array($requiredField, $headers)) {
                $error[] = $requiredField;
            }
        }

        if(!empty($error)) {
            return [[
                'row' => 1,
                'errors' => "Required field '" . implode(',', $error) . "' are missing in header"
            ]];
        }

        return $errors;
    }
}
