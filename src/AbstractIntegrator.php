<?php

namespace App;

use App\Interfaces\IntegrationInterface;
use DateTime;

abstract class AbstractIntegrator
{
    /**
     * Типы службы доставки
     */
    const DELIVERY_TYPE_FAST = 1;
    const DELIVERY_TYPE_SLOW = 2;

    /**
     * Параметры доступа - логин, пароль, токен
     * @var array
     */
    protected $properties = [];

    /**
     * Данные полученные после запроса
     * @var array
     */
    protected $data = [];

    /**
     * Ошибка
     * @var string
     */
    protected $error = '';

    /**
     * Ночные часы
     * @var array
     */
    protected $nightHours = [];

    /**
     * Тип передаваемых данных
     * @var string
     */
    public $contentType = 'application/x-www-form-urlencoded';

    /**
     * Расчета стоимости доставки
     * @param null $methodName
     * @return mixed
     */
    abstract public function calculate(array $properties = []);

    /**
     * Получение URL запроса
     * @param null $methodName
     * @return mixed
     */
    abstract public function getUrl(string $methodName = null);

    /**
     * Проверка на наличие ошибки в запросе
     * @param $response
     * @return mixed
     */
    abstract public function isError($response);

    /**
     * Получение данных
     * @param null $response
     * @return bool
     */
    public function getData($response = null)
    {
        if ($this->isError($response)) {
            return false;
        } else {
            $this->data = $response;
            return true;
        }
    }

    /**
     * Методы отправки запросов
     * Выполнение POST метода интегратора
     * @param $url
     * @param $content
     * @param false $binary
     * @return false|string|null
     */
    public function post($url, $content, $binary = false)
    {
        // TODO
        return '';
    }

    /**
     * Выполнение GET метода интегратора
     * @param $url
     * @param $attributes
     * @return false|string
     */
    public function get($url, $attributes)
    {
        // TODO
        return '';
    }

    /**
     * Подготовка атрибутов для URL
     * @param $attributes
     * @return string
     */
    public function getAttributes(string $attributes): string
    {
        if (is_string($attributes)) {
            return $attributes;
        }
        if (is_array($attributes)) {
            return http_build_query($attributes);
        }
        return '';
    }

    /**
     * @param DateTime $date
     * @return string
     */
    public function dateTimeCalc(DateTime $date): string
    {
        if ($date->format('H') > 18) {
            $date->modify('+1 day');
        }
        return $date->format('Y-m-d');
    }

    /**
     * Создание интеграции
     * @param IntegrationInterface $integration
     * @param array $properties
     */
    public function getIntegration(IntegrationInterface $integration, array $properties = [])
    {
        if ($properties["delivery"] === "calc-form-delivery-type-fast" && $integration->getDeliveryType() === 1) {
            return $integration->calculate($properties);
        } else if ($properties["delivery"] === "calc-form-delivery-type-slow" && $integration->getDeliveryType() === 2) {
            return $integration->calculate($properties);
        }

    }

}