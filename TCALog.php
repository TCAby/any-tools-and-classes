<?php

/* (c) Trakhtenberg Consulting Agency, 2021-2022
    Вывод отладочной информации
        1) в консоль разработчика в браузере (*2console)
        2) в локальный файл на сервере (*2file)
*/
class TCALog
{
    private static function get_logfilename() {
        // внутренняя функция для генерации имени log-файла, с использованием текущей даты
        return ('tcalog_'.date("dmY").'.txt');
    }
    
    private static function get_backtrace() {
        // возвращается отформатированный результат функции debug_backtrace
        $arr_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backdata = $arr_backtrace[1];
        $out = 'Called from '.$backdata['file'].' at line '.$backdata['line'].' ';
        
        return $out;
    }
    
    public static function send2console(...$data){ 
        /*
         * Функция вывода информации в консоль браузера
         * (upd 11.03.2022): Функция принимает переменный список аргументов
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         * (upd 19.05.2022): Более тщательная отработка типов данных + мелкие исправления
         */
        $count_data = count($data);
		for ( $i=0; $i < $count_data; $i++ ) {
            $var_type = gettype($data[$i]);
            switch ($var_type) {
                case 'array':
                    $log_output[] = 'php_' . $var_type . ': ' . json_encode( $data[$i] );
                    break;
                case 'object':
                    $log_output[] = 'php_class: ' . get_class( $data[$i] ) . ', php_' . $var_type . ': ' . json_encode( $data[$i] );
                    break;
                case 'string':
                case 'integer':
                case 'float':
                case 'double':
                    $log_output[] = 'php_' . $var_type . ': ' . $data[$i];
                    break;
                case 'boolean':
                    $log_output[] = 'php_' . $var_type . ': ' . ($data[$i]?'true':'false');
                    break;
                case 'NULL':
                    $log_output[] = 'php_' . $var_type;
                    break;
            }
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
         * (upd 04.10.2021): Функция принимает переменный список аргументов
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         * (upd 19.05.2022): Более тщательная отработка типов данных + мелкие исправления
         */
        date_default_timezone_set('Europe/Minsk');

        $count_data = count($data);
        for ( $i=0; $i < $count_data; $i++ ) {
            $var_type = gettype($data[$i]);
            switch ($var_type) {
                case 'array':
                    $log_output[] = 'php_' . $var_type . ': ' . json_encode( $data[$i] );
                    break;
                case 'object':
                    $log_output[] = 'php_class: ' . get_class( $data[$i] ) . ', php_' . $var_type . ': ' . json_encode( $data[$i] );
                    break;
                case 'string':
                case 'integer':
                case 'float':
                case 'double':
                    $log_output[] = 'php_' . $var_type . ': ' . $data[$i];
                    break;
                case 'boolean':
                    $log_output[] = 'php_' . $var_type . ': ' . ($data[$i]?'true':'false');
                    break;
                case 'NULL':
                    $log_output[] = 'php_' . $var_type;
                    break;
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