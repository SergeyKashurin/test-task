<?php

namespace App\Integrations;

use App\AbstractIntegrator;
use App\Interfaces\IntegrationInterface;
use DateTime;

class MyWay extends AbstractIntegrator implements IntegrationInterface
{
    /**
     * Тип службы доставки
     * @var int
     */
    private int $delivery_type = self::DELIVERY_TYPE_FAST;

    /**
     * Ошибка
     * @var string
     */
    private string $integratorError = '';

    public function calculate(array $properties = [])
    {
        return $this->fastDelivery($properties["source"], $properties["to"], $properties["weight"]);
    }

    /**
     * @param null $methodName
     * @return string
     */
    public function getUrl($methodName = null)
    {
        if ($methodName == 'calculate') {
            return 'https://some-link/' . $methodName;
        } else {
            return 'https://some-else-link/' . $methodName;
        }
    }

    /**
     * @param $response
     * @return bool|void
     */
    public function isError($response)
    {
        if (!$response) {
            $this->error = 'Ошибка сервиса';
            return true;
        }

        if ($attributes = $response->attributes()) {
            if (isset($attributes['ErrorCode'])) {
                $this->error = $attributes['Msg'];
                return true;
            }
        }
    }

    /**
     * @param string $sourceKladr
     * @param string $targetKladr
     * @param float $weight
     * @return array
     */
    public function fastDelivery(string $sourceKladr, string $targetKladr, float $weight): array
    {
        $auth = $this->getUrl('with-auth');

        if (!$auth) {
            $this->setIntegratorError($this->isError());
        }

        $date = self::dateTimeCalc(new DateTime('now'));
        $price = rand(100, 1000);

        return [
            'price' => $price,
            'date'  => $date,
            'error' => $this->getIntegratorError() ?? '',
        ];
    }

    /**
     * @return int
     */
    public function getDeliveryType(): int
    {
        return $this->delivery_type;
    }

    /**
     * @return string
     */
    public function getIntegratorError(): string
    {
        return $this->integratorError;
    }

    /**
     * @param string $integratorError
     */
    public function setIntegratorError(string $integratorError): void
    {
        $this->integratorError = $integratorError;
    }
}