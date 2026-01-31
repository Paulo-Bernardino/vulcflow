<?php
 
class Settings
{

   
    const MONTH_LABEL = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro'
    );
 
   
    const B1_DIVISION = 1;
   
    const B2_DIVISION = 2;
   
    const CURED = 1;
    const GREEN = 2;
   
    const MONTHLY = 1;
    const DAILY   = 2;
   
    const HFPLT2   = 'HFPLT2';
    const NESTING  = 'NESTING';
   
    const LOG_TYPE_DOWMTIME = 'DOWNTIME';
    const LOG_TYPE_CHGMOLD  = 'RFBCMOLD';
   
    const RANGE_SALES_CODE = array(
        'START' => 120004,
        'FINISH'=> 124000
    );
   
    const CURED_SHIFT = array(
        1 => array('START' => '06:15:00', 'FINISH' => '14:44:59' ),
        2 => array('START' => '14:45:00', 'FINISH' => '22:59:59' ),
        3 => array('START' => '23:00:00', 'FINISH' => '06:14:59' )
    );
 
    const BUILDING_SHIFT = array(
        1 => array('START' => '06:45:00', 'FINISH' => '15:14:59' ),
        2 => array('START' => '15:15:00', 'FINISH' => '23:29:59' ),
        3 => array('START' => '23:30:00', 'FINISH' => '06:44:59' )
    );
 
    const OPERATIONAL_DEFECTS = [
        'C' => '1130,1670,1150,1181,1140,620,640,690,700,701,3020,640,630,701,91,1264,1314,1323,2423,142,1830,2542,480,690,692,699,750,710',
        'G' => '2542,632,2423'
    ];
 
    const CYCLE_TIME_BUILDING_GOAL = [
        'NG01' => 120,
        'NG02' => 114,
        'NG03' => 120,
        'NG04' => 120,
        'NG05' => 120,
        'NG06' => 120,
        'NG07' => 120,
        'NG08' => 120,
        'TBT01' => 124,
        'TBT02' => 124,
        'NG' => 120,
        'TBT' => 124
    ];
 
    const MACHINES_NG = [
        'NG01' ,
        'NG02' ,
        'NG03' ,
        'NG04' ,
        'NG05' ,
        'NG06' ,
        'NG07' ,
        'NG08' ,
    ];
 
    const MACHINES_TBT = [
        'TBT01',
        'TBT02',
    ];
   
    const MACHINES_MD = [
        'MA' ,
        'MD' ,
        'MF' ,
    ];
 
    const ALL_MACHINES = [
        'NG01' ,
        'NG02' ,
        'NG03' ,
        'NG04' ,
        'NG05' ,
        'NG06' ,
        'NG07' ,
        'NG08' ,
        'TBT01',
        'TBT02',
        'MA' ,
        'MD' ,
        'MF' ,
    ];
 
    const MONTHS = [
        1  => 'Janeiro',
        2  => 'Fevereiro',
        3  => 'Março',
        4  => 'Abril',
        5  => 'Maio',
        6  => 'Junho',
        7  => 'Julho',
        8  => 'Agosto',
        9  => 'Setembro',
        10  => 'Outubro',
        11  => 'Novembro',
        12  => 'Dezembro',
    ];
 
 
    const encryptSetting = [
        'ciphering' => "AES-128-CTR",
        'options' => 0,
        'encryption_iv' => '1234567891011121',
        'encryption_key' => "Goodam123"
    ];
 
    const INTERVAL_PRODUCTION_CUTTER = [
        'BC01' => 15,
        'AS01' => 17,
        'PC01' => 30,
        'APEX01' => 60,
        'APEX02' => 60,
        'APEX03' => 60
    ];
 
    const MATERIAL_LIST =[
        'BL' => ['Amortecedor',     5],
        'PX' => ['Apex',            5],
        'SW' => ['Costado',         5],
        'LN' => ['Liner',           5],
        'LS' => ['Tira',            5],
        'PL' => ['Lona',            5],
        'CR' => ['Reforço',         5],
        'OL' => ['Sheet Overlay',   5],
        'SP' => ['Spiral Overlay',  5],
        'BE' => ['Talão',           7],
        'TR' => ['Rodagem',         5]
    ];

    const VALORES_NAO_RETIDO = [
        'NÃO', 'NAO', 'N', 'NAO RETIDO', 'NÃO RETIDO'
    ];
 
}