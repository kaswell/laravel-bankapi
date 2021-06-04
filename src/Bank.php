<?php

namespace Kaswell\Bank;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Class Bank
 * @package Kaswell\Bank
 */
class Bank
{
    /**
     * @var string
     */
    private const HOST = 'https://www.nbrb.by/api/exrates/';

    /**
     * @var array $result
     */
    private $result = EMPTY_ARRAY;

    /**
     * @var array $errors
     */
    private $errors = [];

    /**
     * @var \Illuminate\Http\Client\Response $response
     */
    private $response;

    /**
     * @param array $error
     * @return void
     */
    protected function setErrors(array $error)
    {
        $this->errors = array_merge($this->errors, [$error]);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return \Illuminate\Http\Client\Response|void
     */
    protected function getResponse()
    {
        if (!is_null($this->response)) return $this->response;
    }

    /*
     * @return array
     */
    public function getCurrencies(): array
    {
        $this->send('currencies');

        if ($this->response->ok()) $this->result = $this->response->json();
        if ($this->response->failed()) $this->setErrors(['code' => $this->response->status()]);

        return $this->result;
    }

    /**
     * @param int $cur_id
     * @return array
     *
     * Cur_ID – внутренний код
     * Cur_ParentID – этот код используется для связи, при изменениях наименования, количества единиц к которому устанавливается курс белорусского рубля, буквенного, цифрового кодов и т.д. фактически одной и той же валюты*.
     * Cur_Code – цифровой код
     * Cur_Abbreviation – буквенный код
     * Cur_Name – наименование валюты на русском языке
     * Cur_Name_Bel – наименование на белорусском языке
     * Cur_Name_Eng – наименование на английском языке
     * Cur_QuotName – наименование валюты на русском языке, содержащее количество единиц
     * Cur_QuotName_Bel – наименование на белорусском языке, содержащее количество единиц
     * Cur_QuotName_Eng – наименование на английском языке, содержащее количество единиц
     * Cur_NameMulti – наименование валюты на русском языке во множественном числе
     * Cur_Name_BelMulti – наименование валюты на белорусском языке во множественном числе*
     * Cur_Name_EngMulti – наименование на английском языке во множественном числе*
     * Cur_Scale – количество единиц иностранной валюты
     * Cur_Periodicity – периодичность установления курса (0 – ежедневно, 1 – ежемесячно)
     * Cur_DateStart – дата включения валюты в перечень валют, к которым устанавливается официальный курс бел. рубля
     * Cur_DateEnd – дата исключения валюты из перечня валют, к которым устанавливается официальный курс бел. рубля
     */
    public function getCurrencyById(int $cur_id): array
    {
        $this->send('currencies/' . $cur_id);

        if ($this->response->ok()) $this->result = $this->response->json();
        if ($this->response->failed()) $this->setErrors(['code' => $this->response->status()]);

        return $this->result;
    }


    /**
     * @param string|null $date
     * @param int $periodicity
     * @param int $paramMode
     * @return array
     */
    public function getCurrenciesRates($date = null, int $periodicity = 0, int $paramMode = 0): array
    {
        $path = 'rates?ondate=';
        $path .= (is_null($date)) ? $this->parseDate(now()) : $this->parseDate($date);

        if (in_array($periodicity, [0, 1])) $path .= '&periodicity=' . $periodicity;
        if (in_array($paramMode, [0, 1, 2])) $path .= '&parammode=' . $paramMode;

        $this->send($path);

        if ($this->response->successful()) $this->result = $this->response->json();
        if ($this->response->failed()) $this->setErrors(['code' => $this->response->status()]);

        return $this->result;
    }

    /**
     * @param int|string $cur_id
     * @param string|null $date
     * @param int $periodicity
     * @param int $paramMode
     * @return array
     *
     * Cur_ID – внутренний код
     * Date – дата, на которую запрашивается курс
     * Cur_Abbreviation – буквенный код
     * Cur_Scale – количество единиц иностранной валюты
     * Cur_Name – наименование валюты на русском языке во множественном, либо в единственном числе, в зависимости от количества единиц
     * Cur_OfficialRate – курс*
     */
    public function getCurrencyRate($cur_id, $date = null, int $periodicity = 0, int $paramMode = 0): array
    {
        $path = 'rates/' . $cur_id . '?ondate=';
        $path .= (is_null($date)) ? $this->parseDate(now()) : $this->parseDate($date);

        if (in_array($periodicity, [0, 1])) $path .= '&periodicity=' . $periodicity;
        if (in_array($paramMode, [0, 1, 2])) $path .= '&parammode=' . $paramMode;

        $this->send($path);

        if ($this->response->successful()) $this->result = $this->response->json();
        if ($this->response->failed()) $this->setErrors(['code' => $this->response->status()]);

        return $this->result;
    }

    /**
     * @param $path
     * @return void
     */
    protected function send($path)
    {
        try {
            $this->response = Http::retry(1, 5)->baseUrl(self::HOST)->get($path);
        } catch (\Exception $exception) {
            $this->setErrors(['exception' => $exception]);
        }
    }

    /**
     * @param $date
     * @return string
     */
    protected function parseDate($date)
    {
        return Carbon::parse($date)->format("Y-m-d");
    }
}