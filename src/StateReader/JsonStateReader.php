<?php

declare(strict_types=1);


namespace Percas\Grid\StateReader;


use Percas\Grid\GridState;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonStateReader implements StateReaderInterface
{
    /**
     * @inheritDoc
     */
    public function read(): ?GridState
    {
        if (isset($_POST['grid'])) {
            $data = $_POST['grid'];
        } else if (isset($_GET['grid'])) {
            $data = $_GET['grid'];
        } else {
            return null;
        }

        $result = (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]))->deserialize($data, GridState::class, 'json');

        if (!$result instanceof GridState) {
            throw new \UnexpectedValueException('Expected GridState, got ' . gettype($result));
        }

        return $result;
    }
}
