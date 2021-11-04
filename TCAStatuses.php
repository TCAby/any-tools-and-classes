<?php

class TCAStatuses
/*
 * (c) Trakhtenberg Consulting Agency, 2021
 * Класс содержит методы возврата статусов и иных константных значений
 */
{
    public static function requestStatus($code) {
        $status = array(
            200 => 'OK',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Authorizartion Failed',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    public static function responseJson($additional_text_status = '', $status = 500) {
        $data = [
            'response' => TCAStatuses::requestStatus($status),
            'code' => $status,
            'comments' => $additional_text_status
        ];
        return json_encode($data);
    }


}
