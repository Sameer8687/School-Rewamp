<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Receipt - </title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .container {
            margin-bottom: 15px;
            height: 30%;
            padding-top: 20px;
            padding-left: 3px;
            margin-left: 5px;
            /* margin-right: 5px; */
            /* padding-right: 5px; */
        }

        /* .container2 {
        margin-bottom: 15px;
        height: 25%;
        padding-top: 20px;
        padding-left: 2px;
        margin-left: 2px;
    } */


        .receipt {
            padding-left: -70%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
            padding: 2px;
        }

        .font-normal {
            font-weight: normal;
        }

        .olive {
            padding-left: 100px;
            padding-bottom: 15px;
            width: 100%;
            border-bottom: 2px solid silver;
            padding-right: 10px;
        }

        /* .image {
        padding-left: 100px;
    padding-bottom: 15px;
    width: 100%;
    } */


        /* .col-first-head {
        width: 15%;
    }

    .col-second-head {
        width: 85%;
    } */

        .mt-2 {
            margin-top: 20px;
        }

        .mt-1 {
            margin-top: 10px;
        }

        .bordered tr td {
            border: 1px solid #77777710;
            font-size: 12px;
        }

        .dive {
            padding-left: -70%;
            padding-bottom: 10px;

        }

        .Ofee {
            padding-left: -70%;
        }


        .no,
        .receipt,
        .date {
            padding-bottom: 15px;
            padding-top: 20px;
        }

        .name {
            padding-bottom: 10px;
        }

        .sum {

            padding-bottom: 10px;
        }

        .Irupees {
            padding-bottom: 10px;
        }

        .std,
        .term {
            padding-bottom: 10px;
        }

        .pp {
            padding-top: 30px;
        }

        .res {
            padding-top: 30px;
        }

        .u {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 100%;

        }

        /* .main-box {

            display: flex;

            flex-direction: row;

            justify-content: center;

            width: 85mm;

            height: 54mm;

            border: 1px solid #77777750;

        } */

        img {
            height: 50px;
            /* padding-top: 10px; */
            padding-left: -80px;
            padding-bottom: -30px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div style="width: 100%;" class="row">
        <?php $i = 0;
        function words($number = 0)
        {
            $no = floor($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array(
                '0' => '', '1' => 'one', '2' => 'two',
                '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                '7' => 'seven', '8' => 'eight', '9' => 'nine',
                '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                '13' => 'thirteen', '14' => 'fourteen',
                '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
                '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                '60' => 'sixty', '70' => 'seventy',
                '80' => 'eighty', '90' => 'ninety'
            );
            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str[] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . $plural . " " . $hundred
                        :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . $plural . " " . $hundred;
                } else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ?
                "." . $words[$point / 10] . " " .
                $words[$point = $point % 10] . 'Paise' : '';

            return $result . "Rupees  " . $points;
        }
        foreach ($all_records as $record) : ?>
            <div class="container" style="border: 1px solid #777777; width:49%; float: left;">
                <table class="table ">
                    <tr>

                        <td class="olive">
                            <img src="https://school.oliveschool.org.in/assets/images/logoforpdf.jpg" style="height: 50px;" alt="" srcset="">
                            <h4 style="font-size:18px; font-weight: bold;">Olive English School & Junior College</h4>
                            <h6 class="font-normal" style="font-size:14px ;">National Highway No.3 Behind
                                Traffic
                                Police
                                Station, Dhule.</h6>

                        </td>

                    </tr>

                    <tr>
                        <td class="no" style="font-size:15px; font-weight: bold;">Receipt No : <?= ++$i ?></td>
                        <td class="receipt">
                            <h5 class="text-left" style="font-size:15px;font-weight: bold">Receipt</h5>
                        </td>
                        <td class="date" style="font-size:14px; font-weight: bold;">Date :
                            <?= $record['sprfees_feesdate']  == '' || $record['sprfees_feesdate'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($record['sprfees_feesdate'])) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="name" style="font-size:16px; font-weight: bold;">
                            <p> Name : <span class="u" style="font-size:18px; font-weight: bold;"><?= $record['name']  ?></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="sum" style="font-size:16px; font-weight: bold;">
                            <p>The sum of : <span class="u" style="font-size:18px; font-weight: bold;">
                                    <?= $record['sprfees_amount']  ?></span></p>
                        </td>

                    </tr>
                    <tr>
                        <td class="Irupees" style="font-size:16px; font-weight: bold;">
                            <h7>In word Rupees : <span class="u" style="font-size:18px; font-weight: bold;"><?= ucwords(words($record['sprfees_amount']));  ?></span>
                            </h7>

                        </td>
                    </tr>
                    <tr>
                    <tr>

                        <td class="std" style="font-size:16px; font-weight: bold;">
                            <p>STD : <span class="u" style="font-size:18px; font-weight: bold;">
                                    <?= $record['sprfees_standard']  ?></span></p>
                        </td>


                        <td class="dive" style="font-size:16px; font-weight: bold; ">
                            <p>Div : <span class="u" style="font-size:18px; font-weight: bold;"> -
                                </span></p>
                        </td>
                        <td class="term" style="font-size:16px; font-weight: bold; ">
                            <p>Term Fees : <span class="u" style="font-size:18px; font-weight: bold;"> -
                                </span></p>
                        </td>

                    </tr>
                    <tr>
                        <td class="Tfees" style="font-size:16px; font-weight: bold; ">
                            <p>Tuition Fee : <span class="u" style="font-size:18px; font-weight: bold;">
                                    <?= $record['sprfees_amount']  ?></span></p>
                        </td>
                        <td class="Ofee" style="font-size:16px; font-weight: bold; ">
                            <p>Other Fee : <span class="u" style="font-size:18px; font-weight: bold;"> -
                                </span></p>
                        </td>

                    </tr>
                    <tr>
                        <td class="pp" style="font-size:12px; font-weight: bold; ">
                            <p>. Fees once paid will not be refunded. Thanks</p>
                        </td>

                    </tr>
                    <!-- <tr>
                    <td style="padding-top:30px;">
                        <table class="table"> -->
                    <tr>
                        <td style="width: 100%; text-align: right;font-size:12px; font-weight: bold;">
                            <p>---------------------------</p>
                            <h6 style="font-size:12px; font-weight: bold;">School Stamp</h6>
                        </td>
                    </tr>
                    <!-- </table>
                    </td>
                </tr> -->

                    </tr>


                </table>

            </div>
        <?php endforeach; ?>

    </div>
</body>

</html>
