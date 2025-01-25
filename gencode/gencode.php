<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    function transformmc($mc){
        //echo $mc;
        //$mc = str_replace($mc,"-","");
        $mret = "";
        for($i=0;$i<8;$i++)
        {
            switch($mc[$i])
            {
            case '3': $mret .= '0'; break;
            case '6': $mret .= '1'; break;
            case '8': $mret .= '2'; break;
            case 'q':
            case 'Q': $mret .= '3'; break;
            case '2': $mret .= '4'; break;
            case '9': $mret .= '5'; break;
            case '7': $mret .= '6'; break;
            case '4': $mret .= '7'; break;
            case 'g':
            case 'G': $mret .= '8'; break;
            case '5': $mret .= '9'; break;
            case 'n':
            case 'N': $mret .= 'A'; break;
            case 'z':
            case 'Z': $mret .= 'B'; break;
            case 'k':
            case 'K': $mret .= 'C'; break;
            case 'l':
            case 'L': $mret .= 'D'; break;
            case '0': $mret .= 'E'; break;
            case '1': $mret .= 'F'; break;
            }
        }
        return $mret;
    }

    function transformld($ndate){
        $nret = 26112;
        if($ndate>0){
            $sdate = strval($ndate);
            $sret = '';
           for($i=0;$i<4;$i++)
           {
              switch($sdate[$i])
              {
                 case '0': $sret .= '6';break;
                 case '1': $sret .= '4';break;
                 case '2': $sret .= '8';break;
                 case '3': $sret .= '1';break;
                 case '4': $sret .= '9';break;
                 case '5': $sret .= '5';break;
                 case '6': $sret .= '0';break;
                 case '7': $sret .= '3';break;
                 case '8': $sret .= '2';break;
                 case '9': $sret .= '7';break;
              }
           }
           $nret = str_pad($sret, 4, "0", STR_PAD_LEFT);
        }
        return $nret;
    }    

    if($data){
        $amc = explode("-", $data['mc']);
        $mc = str_pad(strtoupper($amc[0]), 4, '3', STR_PAD_LEFT);
        $mc.= str_pad(strtoupper($amc[1]), 4, '3', STR_PAD_LEFT);


        $mc = transformmc($mc);
        //echo $mc . "\n";
        $dwMid = hexdec('0x' . $mc);
        //echo $dwMid . "\n";
        $w1 = $dwMid >> 16;
        //echo $w1 . "\n";
        $w2 = $dwMid & 0xffff;
        //echo $w2 . "\n";
        $wTmp = $w1 ^ $w2;
        //echo $wTmp . "\n";
        $w1 = $wTmp & 0xff;
        //echo $w1 . "\n";
        $w2 = $wTmp >> 8;
        //echo $w2 . "\n";
        $dwMid = ($w2 << 16)+$w1;
        //echo $dwMid . "\n";
        $ld = transformld($data['ld']);
        $dwTmp = hexdec('0x' . $ld[0] . $ld[1] . '00' . $ld[2] . $ld[3] . '00');
        $dwMid = $dwMid + $dwTmp;
        $msg = "หมายเลขเครื่อง : ";
        if($data['mc'] == "274-846"){
            $msg.= "A274-B846\n";
        }else{
            $mc = strtoupper($data['mc']);
            $msg.= "{$mc}\n";
        }
        $msg.= "License : {$dwMid}\n";
        $msg.= "😜 โปรดจดหมายเลขเครื่องและLicenseไว้เป็นหลักฐาน\n";
        $msg.= "🚩!! ถ้าหายซื้อใหม่อย่างเดียวหรือติดต่อ 089-694-7553 !!";

        exit($msg);
    }
?>
<html lang="th">
    <head>
       <meta charset="UTF-8" />
	   <meta name="viewport" content="width=device-width, initial-scale=1" />
	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
       <style>
        body{
            line-height:1.8;
        }
        input[type="text"]{
            margin-left:20px;
        }
        #mc{
            text-transform:uppercase;
        }
       </style>
    </head>
    <body>
        <label for="mc">หมายเลขเครื่อง</label><input type="text" id="mc" name="mc" value="274-846" /><br/>
        <label for="ld">License Date</label><input type="text" id="ld" name="ld" /><br/>
        <label for="msg">Message</label><textarea id="msg" name="msg" cols="80" rows="6"></textarea><br/>
        <input type="button" id="btnGen" value="Generate" />
        <script type="text/javascript">
            $(document).ready(function(){
                let dt = new Date();
                let d = dt.getDate();
                if(d>16){
                    dt.setMonth(dt.getMonth() + 1);
                }
                let y = (dt.getYear()+543) % 100;
                let m = dt.getMonth()+1;

                $(ld).val(String(y).padStart(2, '0')+String(m).padStart(2, '0'));

                $('#btnGen').click(e=>{
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: window.location,
                        data: JSON.stringify({"mc":$("#mc").val(),"ld":$("#ld").val()}),
                        contentType: "application/json",
                        success: function(res){
                            navigator.clipboard.writeText(res);
                            $('#msg').text(res);
                        }
                    });                    
                });
            })
        </script>
    </body>
</html>