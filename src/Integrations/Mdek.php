<?php

namespace App\Integrations;

use DateTime;
use App\AbstractIntegrator;
use App\Interfaces\IntegrationInterface;

class Mdek extends AbstractIntegrator implements IntegrationInterface
{
    /**
     * Тип службы доставки
     * @var int
     */
    private int $delivery_type = self::DELIVERY_TYPE_SLOW;

    /**
     * Базовая стоимость
     * @var int
     */
    private int $baseCost = 150;

    /**
     * Ошибка
     * @var string
     */
    private string $integratorError = '';

    /**
     * @param null $methodName
     * @param array $properties
     */
    public function calculate(array $properties = [])
    {
        return $this->slowDelivery($properties["source"], $properties["to"], $properties["weight"]);
    }

    /**
     * @param null $methodName
     * @return string
     */
    public function getUrl($methodName = null): string
    {
        if ($methodName === 'with-auth') {
            return 'https://some-link/' . $methodName;
        } else {
            return 'https://some-else-link/' . $methodName;
        }
    }

    /**
     * @param null $response
     * @return bool
     */
    public function isError($response = null): bool
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

        return false;
    }

    /**
     * @param string $sourceKladr
     * @param string $targetKladr
     * @param float $weight
     * @return array|int
     */
    public function slowDelivery(string $sourceKladr, string $targetKladr, float $weight): array
    {
        $auth = $this->getUrl('with-auth');

        if (!$auth) {
            $this->setIntegratorError($this->isError());
        }

        $date = self::dateTimeCalc(new DateTime('now'));

        return [
            'price' => $this->getBaseCost(),
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
     * @return int
     */
    public function getBaseCost(): int
    {
        return $this->baseCost;
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