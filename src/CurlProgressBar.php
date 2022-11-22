<?php

namespace LTL\Curl;

abstract class CurlProgressBar
{
    private static $previousPercent;

    public static function progress($resource, $download_size, $downloaded_size, $upload_size, $uploaded_size)
    {
        self::$previousPercent = 0;


        if ($upload_size > 0) {
            $percent = intval(1000*($uploaded_size/$upload_size))/10;

            if ($percent !== self::$previousPercent) {
                self::progressBar($uploaded_size, $upload_size);
            }
        }
    }

    private static function progressBar($done, $total)
    {
        $perc = $done/$total;

        $percInteger = floor(($done/$total) * 100);
        $leftInteger = 100 - $percInteger;

        $percRead = str_pad(number_format($perc * 100, 1), 4, '0', STR_PAD_LEFT);
        $doneMb = number_format($done/1024/1024, 2);
        $totalMb = number_format($total/1024/1024, 2);

        $write = sprintf("\033[0G\033[2K[%'={$percInteger}s>%-{$leftInteger}s] - {$percRead}%% - {$doneMb}Mb/{$totalMb}Mb", '', '');
        fwrite(STDERR, $write);
    }
}
