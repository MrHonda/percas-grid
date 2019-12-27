<?php

declare(strict_types=1);


namespace Percas\Grid\Renderer;


use Percas\Grid\Grid;
use Percas\Grid\Header;
use Percas\Grid\Pagination;
use Percas\Grid\Row;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonRenderer implements RendererInterface
{
    /**
     * @inheritDoc
     */
    public function render(Grid $grid): string
    {
        $json = json_encode(
            [
                'headers' => $this->parseHeaders($grid->getHeaders()),
                'rows' => $this->parseRows($grid->getRows()),
                'pagination' => $this->parsePagination($grid->getPagination())
            ]
        );

        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $json;
    }

    /**
     * @param Header[] $headers
     * @return array
     */
    private function parseHeaders(array $headers): array
    {
        $result = [];
        $serializer = new Serializer([new ObjectNormalizer()]);

        try {
            foreach ($headers as $header) {
                $normalized = $this->validateNormalizedValue($serializer->normalize($header, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['filters']]));
                $normalized['filters'] = $this->validateNormalizedValue($serializer->normalize($header->getFilters(), null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['dataFilter']]));

                $result[] = $normalized;
            }
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param Row[] $rows
     * @return array
     */
    private function parseRows(array $rows): array
    {
        $result = [];
        $serializer = new Serializer([new ObjectNormalizer()]);

        try {
            foreach ($rows as $row) {
                $result[] = $this->validateNormalizedValue($serializer->normalize($row, null));
            }
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param Pagination $pagination
     * @return array
     */
    private function parsePagination(Pagination $pagination): array
    {
        $serializer = new Serializer([new ObjectNormalizer()]);

        try {
            return $this->validateNormalizedValue($serializer->normalize($pagination, null));
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param mixed $normalized
     * @return array
     */
    private function validateNormalizedValue($normalized): array
    {
        if (!is_array($normalized)) {
            throw new \RuntimeException('Expected array, got ' . gettype($normalized));
        }

        return $normalized;
    }
}
