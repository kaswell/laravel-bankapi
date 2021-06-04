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
    public function getResponse()
    {
        if (!is_null($this->response))
            return $this->response;
    }

    /**
     * @param int $cur_id
     * @return array
     */
    public function getCurrency(int $cur_id = ZERO): array
    {
        $path = 'currencies';
        if ($cur_id !== ZERO) $path .= '/' . $cur_id;

        $this->send($path);

        if ($this->response->ok()) {
            $this->result = $this->response->json();
        }
        if ($this->response->failed()) {
            $this->setErrors(['code' => $this->response->status()]);
        }

        return $this->result;
    }

    /**
     * @param int $cur_id
     * @param string|null $date
     * @return array
     */
    public function getCurrencyRate(int $cur_id = ZERO, $date = null): array
    {
        $path = 'rates';
        if ($cur_id !== ZERO) $path .= '/' . $cur_id;
        if (!is_null($date)) $path .= '?ondate=' . $this->parseDate($date);

        $this->send($path);

        if ($this->response->successful()) {
            $this->result = $this->response->json();
        }
        if ($this->response->failed()) {
            $this->setErrors(['code' => $this->response->status()]);
        }

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