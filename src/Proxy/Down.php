<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 19/10/19
 * Time: 10:43
 */

namespace App\Proxy;


use App\Model\Proxy;
use Symfony\Component\Process\Process;

class Down
{
    public static function is(Proxy $proxy ) {

        $process = new Process("ping ".$proxy->getHttp());
        $process->start();
        $up = false;
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                if(preg_match("/64 bytes from/", $data ) ) {
                    $up = true;
                    break;
                } else {
                    break;
                }
            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        }
        $process->stop();
        return !$up;
    }
}
