<?php

/* (c) Trakhtenberg Consulting Agency, TCA.by, 2021-2023
    Output the debugging information
        1) into developer's console in your browser (*2console methods)
        2) to a local file on the server (*2file methods)
*/
class TCALog
{
    /**
     * @return string
     */
    private static function get_logfilename()
    {
        // private internal function to generate a log file name, using the current date
        return ('tcalog_'.date("dmY").'.txt');
    }

    /**
     * @return string
     */
    private static function get_backtrace()
    {
        // returns the formatted result of the debug_backtrace function
        $arr_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backdata = $arr_backtrace[1];
        $out = 'Called from '.$backdata['file'].' at line '.$backdata['line'].' ';
        
        return $out;
    }
    

    /**
     * @param ...$data
     */
    public static function send2console(...$data)
    {
        /*
         * Function for outputting information to the browser console
         *  (upd 04.10.2021): The function accepts a variable list of arguments
         * Automatically recognizes the received parameter type, and outputs either a string or an array
         *  (upd 19.05.2022): Better handling of data types + minor fixes
         *  (upd 20.04.2023): For arrays and classes, use var_export() instead of json_encode
         */
        $count_data = count($data);
		for ( $i=0; $i < $count_data; $i++ ) {
            $var_type = gettype($data[$i]);
            switch ($var_type) {
                case 'array':
                    $log_output[] = 'php_' . $var_type . ': ' . var_export( $data[$i], true );
                    break;
                case 'object':
                    $log_output[] = 'php_class: ' . get_class( $data[$i] ) . ', php_' . $var_type . ': ' . var_export( $data[$i], true );
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
    
    public static function send_get_defined_vars2console()
    {
        /*
         * Outputs information to the browser console
         * Outputs all variables defined at the moment of call in the given scope
         */
        print_r("<script>console.log('".self::get_backtrace()."variables: ".json_encode(get_defined_vars())."');</script>");
    }
    
    public static function send_debug_backtrace2console()
    {
        /*
         * Outputs information to the browser console
         * Outputs the function call stack
         */
        print_r("<script>console.log('".self::get_backtrace()."variables: ".json_encode(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))."');</script>");
    }

    public static function send2file(...$data)
    {
        /*
         * Function for outputting information to the file tcalog_<cuttent_date>.txt (for example, tcalog_04102021.txt)
         *  (upd 04.10.2021): The function accepts a variable list of arguments
         * Automatically recognizes the received parameter type, and outputs either a string or an array
         *  (upd 19.05.2022): Better handling of data types + minor fixes
         *  (upd 20.04.2023): For arrays and classes, use var_export() instead of json_encode
         */
        date_default_timezone_set('Europe/Minsk');

        $count_data = count($data);
        for ( $i=0; $i < $count_data; $i++ ) {
            $var_type = gettype($data[$i]);
            switch ($var_type) {
                case 'array':
                    $log_output[] = 'php_' . $var_type . ': ' . var_export( $data[$i], true );
                    break;
                case 'object':
                    $log_output[] = 'php_class: ' . get_class( $data[$i] ) . ', php_' . $var_type . ': ' . var_export( $data[$i], true );
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

    public static function send_get_defined_vars2file()
    {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит все определенные к моменту вызова в данной области видимости переменные
         */
        date_default_timezone_set('Europe/Minsk');
        file_put_contents(self::get_logfilename(), print_r([json_encode(get_defined_vars()), date('H:i:s'), self::get_backtrace()], true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    public static function send_debug_backtrace2file()
    {
        /*
         * Функция вывода информации в файл tcalog.txt
         * Выводит стек вызова функции
         */
        date_default_timezone_set('Europe/Minsk');
        file_put_contents(self::get_logfilename(), print_r([debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), date('H:i:s'), self::get_backtrace()], true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}