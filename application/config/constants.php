<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Aliyun OSS Params
|--------------------------------------------------------------------------
|
|
|
*/
defined('OSS_BUCKET')            OR define('OSS_BUCKET', "zijin-garden");
defined('OSS_ACCESS_KEY')        OR define('OSS_ACCESS_KEY', "LTAIRe8BXV2H1oOy");
defined('OSS_SECRET_KEY')        OR define('OSS_SECRET_KEY', "sqlUotFyEH6SCcgDbycBnBzH8gizZt");
defined('OSS_ENDPOINT')          OR define('OSS_ENDPOINT', "http://oss-cn-shanghai.aliyuncs.com");
defined('OSS_PIC_URL')           OR define('OSS_PIC_URL', "http://zijin-garden.oss-cn-shanghai.aliyuncs.com/");



defined('REQUEST_SUCCESS')             OR define('REQUEST_SUCCESS', 0); // 接口验证成功
defined('RESPONSE_ERROR')              OR define('RESPONSE_ERROR', 400000);//接口返回失败
defined('LACK_REQUIRED_PARAMETER')     OR define("LACK_REQUIRED_PARAMETER", 400001);//缺少必要参数
defined('USER_NOT_FOUND')              OR define("USER_NOT_FOUND", 404002);//查找不到此用户
defined('TRANS_NOT_SUCCESS')           OR define("TRANS_NOT_SUCCESS", 404003);//数据没有变化
defined('RECORD_NOT_FOUND')            OR define("RECORD_NOT_FOUND", 404004);//查找不到记录
defined('PARAMETER_WRONG')             OR define("PARAMETER_WRONG", 404005);//参数错误
defined('DATA_FORMAT_ERROR')           OR define("DATA_FORMAT_ERROR", 400010);//数据格式不符
defined('INSERT_DB_ERROR')             OR define("INSERT_DB_ERROR", 500001);//入库失败
defined('UPDATE_DB_ERROR')             OR define("UPDATE_DB_ERROR", 500002);//更新数据失败
defined('NOT_HAVE_PERMISSION')         OR define("NOT_HAVE_PERMISSION", 500003);//没有操作权限
defined('API_ERROR')                   OR define("API_ERROR", 500004);//api调用失败
defined('ALREADY_EXISTS')              OR define("ALREADY_EXISTS", 500005);//已经存在
defined('SIGN_ERROR')                  OR define("SIGN_ERROR", 500006);//签名错误
defined('DELETE_DB_ERROR')             OR define("DELETE_DB_ERROR", 500008);//删除数据失败
defined('WRITE_FILE_ERROR')            OR define("WRITE_FILE_ERROR", 403301);//文件写入失败
defined('OPERATE_DENY')                OR define('OPERATE_DENY', 403001);//无权操作
defined('VERIFY_FAILED')               OR define('VERIFY_FAILED', 403403);//仅仅用于token签名验证失败,不可用作其他用途

defined('PAGESIZE')                    OR define('PAGESIZE', 20);//页面数量
