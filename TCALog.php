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
        
    public static function send2console($data, $php_filename='', $php_callername=''){ 
        /*
         * Функция вывода информации в консоль браузера
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        $from = '';
        if ( $php_filename!= '' ) {
            $from .= ' Filename:'.$php_filename.' ';
        }
        if ( $php_callername!= '' ) {
            $from .= ' Caller:'.$php_callername.' ';
        }
        if(is_array($data) || is_object($data)){
            echo("<script>console.log('".$from."php_array: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('".$from."php_string: ".$data."');</script>");
        }
    }
    
    public static function send_get_defined_vars2console($php_filename='', $php_callername='') {
        /*
         * Функция вывода информации в консоль браузера
         * Выводит все определенные к моменту вызова в данной области видимости переменные
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        $from = '';
        if ( $php_filename!= '' ) {
            $from .= ' Filename:'.$php_filename.' ';
        }
        if ( $php_callername!= '' ) {
            $from .= ' Caller:'.$php_callername.' ';
        }
        echo("<script>console.log('".$from."variables: ".json_encode(get_defined_vars())."');</script>");
    }
    
    public static function send_debug_backtrace2console($php_filename='', $php_callername='') {
        /*
         * Функция вывода информации в консоль браузера
         * Выводит стек вызова функции
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        $from = '';
        if ( $php_filename!= '' ) {
            $from .= ' Filename:'.$php_filename.' ';
        }
        if ( $php_callername!= '' ) {
            $from .= ' Caller:'.$php_callername.' ';
        }
        echo("<script>console.log('".$from."variables: ".json_encode(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))."');</script>");
    }

    public static function send2file($data, $php_filename='', $php_callername='') {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Автоматически распознает полученный тип параметры, и выводит либо строку, либо массив
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        date_default_timezone_set('UTC+3');
        $log_output = '';
        if(is_array($data) || is_object($data)){
            $log_output .= 'php_array: '.json_encode($data);
        } else {
            $log_output .= 'php_string: '.$data;
        }
        file_put_contents(self::get_logfilename(), print_r([$php_filename, $php_callername, date('H:i:s'), $log_output], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }

    public static function send_get_defined_vars2file($php_filename='', $php_callername='') {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит все определенные к моменту вызова в данной области видимости переменные
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        date_default_timezone_set('UTC+3');
        file_put_contents(self::get_logfilename(), print_r([$php_filename, $php_callername, date('H:i:s'), json_encode(get_defined_vars())], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }
    
    public static function send_debug_backtrace2file($php_filename='', $php_callername='') {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит стек вызова функции
         * Необязательные параметры:
         *  @php_filename - имя файла, вызвавшего отладочное сообщение
         *  @php_callername - имя функции (скрипта), вызвавшей отладочную информацию
         */
        date_default_timezone_set('UTC+3');
        file_put_contents(self::get_logfilename(), print_r([$php_filename, $php_callername, date('H:i:s'), debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)], true).PHP_EOL, FILE_APPEND | LOCK_EX);        
    }
}