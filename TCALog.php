<?php

/* (c) Trakhtenberg Consulting Agency, 2021
    Вывод отладочной информации
        1) в консоль разработчика в браузере (*2console)
        2) в локальный файл на сервере (*2file)
*/
class TCALog
{
    private function get_logfilename() {
        // внутренняя функция для генерации имени log-файла, с использованием текущей даты
        return ('tcalog_'.date("dmY").'.txt');
    }
    
    private function get_backtrace() {
        // возвращается отформатированный результат функции debug_backtrace
        $arr_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backdata = $arr_backtrace[1];
        $out = 'Called from '.$backdata['file'].' at line '.$backdata['line'].' ';
        
        return $out;
    }
    
    public static function send2console($data){ 
        /*
         * Функция вывода информации в консоль браузера
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         */
        if(is_array($data) || is_object($data)){
            echo("<script>console.log('".self::get_backtrace()."php_array: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('".self::get_backtrace()."php_string: ".$data."');</script>");
        }
    }
    
    public static function send_get_defined_vars2console() {
        /*
         * Функция вывода информации в консоль браузера
         * Выводит все определенные к моменту вызова в данной области видимости переменные
         */
        echo("<script>console.log('".self::get_backtrace()."variables: ".json_encode(get_defined_vars())."');</script>");
    }
    
    public static function send_debug_backtrace2console() {
        /*
         * Функция вывода информации в консоль браузера
         * Выводит стек вызова функции
         */
        echo("<script>console.log('".self::get_backtrace()."variables: ".json_encode(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))."');</script>");
    }

    public static function send2file(...$data) {
        /*
         * Функция вывода информации в файл tcalog_<cuttent_date>.txt (например, tcalog_04102021.txt)
         * Функция принимает переменный список аргументов (upd 04.10.2021)
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         */
        date_default_timezone_set('Europe/Minsk');
        
        for ($i=0; $i<count($data); $i++) {
            if(is_array($data[$i]) || is_object($data[$i])){
                $log_output[] = 'php_array: '.json_encode($data[$i]); 
            } else {
                $log_output[] = 'php_string: '.$data[$i];               
            }
        }        
        file_put_contents(self::get_logfilename(), print_r([$log_output, date('H:i:s e'), self::get_backtrace()], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }

    public static function send_get_defined_vars2file() {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит все определенные к моменту вызова в данной области видимости переменные
         */
        date_default_timezone_set('Europe/Minsk');
        file_put_contents(self::get_logfilename(), print_r([json_encode(get_defined_vars()), date('H:i:s'), self::get_backtrace()], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }
    
    public static function send_debug_backtrace2file() {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит стек вызова функции
         */
        date_default_timezone_set('Europe/Minsk');
        file_put_contents(self::get_logfilename(), print_r([debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), date('H:i:s'), self::get_backtrace()], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }
}