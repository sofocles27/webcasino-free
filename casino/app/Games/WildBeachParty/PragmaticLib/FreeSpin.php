<?php

namespace VanguardLTE\Games\WildBeachParty\PragmaticLib;

class FreeSpin
{
    public static function check($slotArea, $log, $gameSettings, $bet){
        // проверить сколько скаттеров на поле
        $freeSpins = false;
        $addFreeSpins = false;

        $scatterTmp = explode('~',$gameSettings['scatters']);
        $scatter = $scatterTmp[0];
        $scatterPayTable = array_reverse(explode(',', $scatterTmp[1]));
        $scatterFSTable = array_reverse(explode(',', $scatterTmp[2]));
        $scatterPositions = array_keys($slotArea, $scatter);
        $symbols = array_count_values($slotArea); // ключи - символы / значения - количество символов
        if (array_key_exists($scatter, $symbols)){ // если есть в поле скаттеры
            if ($log && array_key_exists('FreeSpinNumber', $log) && $log['FreeState'] != 'LastFreeSpin'){ // если уже есть фриспины
                if ($symbols[$scatter] >= $gameSettings['settings_needaddfs']){ // если скаттеров набирается нужное количество для добавления фриспинов
                    $addFreeSpins = 5;
                }
            }else{
                $pay = $scatterPayTable[$symbols[$scatter]-1]; // положить в pay сумму оплаты за количество скаттеров
                $win = round($pay * $bet, 2);
                if ($win > 0)
                    $freeSpins = $scatterFSTable[$symbols[$scatter]-1];
            }
        }
        if ($freeSpins) return ['FreeSpins' => $freeSpins, 'Pay' => $win, 'ScatterPositions' => $scatterPositions, 'Scatter' => $scatter];
        if ($addFreeSpins) return ['AddFreeSpins' => $addFreeSpins];
        return false;
    }

}
