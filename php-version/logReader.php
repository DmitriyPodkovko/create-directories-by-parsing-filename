<?php

$logs = array();
include ('config.php');
$fileList = explode(',',LOG_NAME);
for($k = 0; $k < count($fileList); $k++) {
    if (file_exists(LOG_PATH . $fileList[$k])) {
        if ($logs = file(LOG_PATH . $fileList[$k])) {
            $reports = array();
            $counter = 0;
            for ($i = 0; $i < count($logs); $i++) {
                if ($len = stripos($logs[$i], '>	GET')) {
                    /*echo "GET";
                    echo "<BR>";
                    echo getAdress($logs[$i], $len+5);
                    echo "<BR>";*/
                    $counter += getAdress($logs[$i], $len + 5);
                } elseif ($len = stripos($logs[$i], '>	POST')) {
                    /*echo "POST";
                    echo "<BR>";
                    echo getAdress($logs[$i], $len+6);
                    echo "<BR>";*/
                    $counter += getAdress($logs[$i], $len + 6);
                }
            }
            print 'Add ' . $counter . ' folders<BR>';
            if ($counter > 0) {
                if (!is_dir(REPORT_PATH)) {
                    mkdir(REPORT_PATH, 0700, true);
                }
                array_unshift($reports, 'Add new ' . $counter . ' folders' . chr(13) . chr(10));
                array_unshift($reports, 'Log-file: ' . $fileList[$k] . chr(13) . chr(10));
                array_push($reports, chr(13) . chr(10));
                file_put_contents(REPORT_PATH . REPORT_FILE, $reports, FILE_APPEND | LOCK_EX);
            }
            //print_r($reports);
        } else {
            print 'Unable to open the "' . $fileList[$k] . '" file!<BR>';
        }
    }
    else
    {
        print 'File "' . $fileList[$k] . '" not found!<BR>';
    }
}

function getAdress($str, $len1)
{
    global $reports;
    $len2 = stripos($str, '	HTTP/');
    $str = substr($str, $len1, ($len2 - $len1));
    $str = RES_DIR_NAME . str_replace('	', '', $str);
    $character_mask = " \t\n\r\0\x0B";
    $str = trim($str, $character_mask);
    if ($len = stripos($str, '?')) {
        $str = substr($str, 0, $len);
        if (!is_dir($str)) {
            mkdir($str, 0700, true);
            array_push($reports, date("Y-m-d G:i:s") . ' -> ' . $str . chr(13) . chr(10));
            return 1;
        } else {
            return 0;
        }
    } else {
        if (!is_dir($str)) {
            mkdir($str, 0700, true);
            array_push($reports, date("Y-m-d G:i:s") . ' -> ' . $str . chr(13) . chr(10));
            return 1;
        } else {
            return 0;
        }
    }
}