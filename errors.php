<?php
/**
 * Class PHPLangError
 */
class PHPLangError{
    static $domain = "PHPLangError";
    static $path;
    static $sub_list = array();
    static $lang_list = array();
    static $errorno_str = array();
    static $errorno_list = array();

    function  __construct(){
    }

    /**
     * @param string $locale
     */
    static function init($locale = ""){
        if(!self::$path){
            self::setLanguagesPath(dirname(__FILE__)."/languages");
        }
        // domain setting
        Localize::bindTextDomain(self::$domain, self::$path);
        // setting
        set_error_handler( array(get_class(),'error_handler'), error_reporting() );
        register_shutdown_function( array(get_class(),'shutdown_handler') );
    }

    /**
     * @param $path
     * @return mixed
     */
    static function setLanguagesPath($path){
        self::$path = $path;
        return $path;
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $errcontext
     */
    static function error_handler ( $errno, $errstr, $errfile, $errline, $errcontext ) {
        $default_domain = Localize::textDomain(NULL);
        Localize::textDomain(self::$domain);

        $format = "[%s] %s %s(%s)\n";
        $lang_sub_list = array(
            "array" => __("array"),
            "string" => __("string"),
            "object" => __("object"),
        );
        $lang_list = array(
            "Missing argument (\d+) for (.+), called in" => __("Missing argument $1 for $2, called in"),
            "Use of undefined constant (.+) - assumed '(.+)'" => __("Use of undefined constant $1 - assumed '$2'"),
            "(.+) to (.+) conversion" => __("$1 to $2 conversion"),
            "Illegal string offset '(.*)'" => __("Illegal string offset '$1'"),
            "Call to undefined method" => __("Call to undefined method"),
            "Non-static method (.+) should not be called statically" => __("Non-static method $1 should not be called statically"),
            "Call to protected method (.+) from context '(.+)'" => __("Call to protected method $1 from context '$2'"),
            "Cannot access private property" => __("Cannot access private property"),
            "Undefined variable:" => __("Undefined variable:"),
            "Undefined index:" => __("Undefined index:"),
            "Illegal offset type in isset or empty" => __("Illegal offset type in isset or empty"),
            "(.+\(\)) expects parameter (.+) to be (.+), (.+) given" => __("$1 expects parameter $2 to be $3, $4 given"),
            "Call to private method (.+) from context '(.+)'" => __("Call to private method $1 from context '$2'"),
            "Call to a member function (.+) on a non-object" => __("Call to a member function $1 on a non-object"),
            "Invalid argument supplied for (.+)" => __("Invalid argument supplied for $1"),
        );
        $errorno_str = array(
            E_ERROR => "ERROR",
            E_WARNING => "WARNING",
            E_PARSE => "PARSE",
            E_NOTICE => "NOTICE",
            E_CORE_ERROR => "CORE_ERROR",
            E_CORE_WARNING  => "CORE_WARNING",
            E_COMPILE_ERROR => "COMPILE_ERROR",
            E_COMPILE_WARNING  => "COMPILE_WARNING",
            E_USER_ERROR  => "USER_ERROR",
            E_USER_WARNING   => "USER_WARNING",
            E_USER_NOTICE   => "USER_NOTICE",
            E_STRICT  => "STRICT",
            E_RECOVERABLE_ERROR   => "RECOVERABLE_ERROR",
            E_DEPRECATED  => "DEPRECATED",
            E_USER_DEPRECATED   => "USER_DEPRECATED",
        );
        $errorno_list = array(
            E_ERROR => __("Fatal run-time errors"),
            E_WARNING => __("Run-time warnings (non-fatal errors)"),
            E_PARSE => __("Compile-time parse errors"),
            E_NOTICE => __("Run-time notices"),
            E_CORE_ERROR => __("Fatal errors that occur during PHP's initial startup"),
            E_CORE_WARNING  => __("Warnings (non-fatal errors)"),
            E_COMPILE_ERROR => __("Fatal compile-time errors"),
            E_COMPILE_WARNING  => __("Compile-time warnings (non-fatal errors)"),
            E_USER_ERROR  => __("User-generated error message"),
            E_USER_WARNING   => __("User-generated warning message"),
            E_USER_NOTICE   => __("User-generated notice message"),
            E_STRICT  => __("Enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code"),
            E_RECOVERABLE_ERROR   => __("Catchable fatal error"),
            E_DEPRECATED  => __("Run-time notices"),
            E_USER_DEPRECATED   => __("User-generated warning message"),
        );
        $errno_lg = "";
        if(isset($errorno_list[$errno])){
            $errno_lg = $errorno_list[$errno];
        }
        $errno = $errorno_str[$errno].":".$errno_lg;
        foreach($lang_list as $k => $v){
            if(preg_match("/".$k."/",$errstr,$mt)){
                $errstr = preg_replace("/".$k."/",__($v),$errstr,1);
                foreach($lang_sub_list as $kk => $vv){
                    $errstr = preg_replace("/([\b])".$kk."([\b])/i","$1".$vv."$2",$errstr);
                }
                break;
            }
        }

        echo sprintf($format,$errno,$errstr,$errfile,$errline,$errcontext);

        Localize::textDomain($default_domain);
    }

    /**
     *
     */
    static function shutdown_handler(){
        $isError = false;
        if ($error = error_get_last()){
            switch($error['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_ERROR:
                case E_COMPILE_WARNING:
                    $isError = true;
                    break;
            }
        }
        if ($isError){
            echo self::error_handler(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line'],
                null );
        }
    }
}

PHPLangError::init();
