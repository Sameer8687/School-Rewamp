<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaving Certificate Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 20px;
            padding: 0;
            line-height: 1.5;
        }

        .certificate-form {
            width: 750px;
            margin: 0 auto;
            padding: 15px;
            border: 1px solid #fff;
            position: relative;
        }

        .header-disclaimer {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 20px;
        }

        .main-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sub-title {
            text-align: center;
            font-style: italic;
            font-size: 10pt;
            margin-bottom: 25px;
        }

        .field-group {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-end;
        }

        .field-label {
            white-space: nowrap;
            padding-right: 5px;
            font-weight: normal;
        }

        .input-line {
            flex-grow: 1;
            border: none;
            border-bottom: 0.5px solid #000;
            min-height: 1em;
            text-transform: uppercase;
            word-wrap: break-word;
            white-space: normal;
            font-weight: bold;
            line-height: 1;
            padding: 0 2px 2px 2px;
        }

        .input-char-boxes {
            flex-grow: 1;
            display: flex;
            flex-wrap: nowrap;
            align-items: flex-end;
            border: none;
            min-height: 1.2em;
        }

        .char-box {
            display: inline-block;
            width: 15px;
            height: 1.2em;
            line-height: 1.2em;
            text-align: center;
            border-bottom: 0.5px solid #fff;
            margin-right: 2px;
            font-weight: bold;
        }

        .char-box1 {
            display: inline-block;
            width: 15px;
            height: 1.2em;
            line-height: 1.2em;
            text-align: center;
            border-bottom: 0.5px solid #fff;
            margin-right: 2px;
            font-weight: normal;
        }

        .input-multiline {
            flex-grow: 1;
            position: relative;
            min-height: 1.2em;
            text-transform: uppercase;
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.5;
            padding-bottom: 4px;
        }

        .input-multiline .text-content {
            position: relative;
            z-index: 2;
            padding: 0 2px;
        }

        .input-multiline .underline {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            border-bottom: 1px solid #000;
            z-index: 1;
        }

        .passport-box {
            position: absolute;
            top: 65px;
            right: 15px;
            width: 100px;
            height: 128px;
            border: 1px solid #000;
            text-align: center;
            line-height: 120px;
            margin: 0;
            font-size: 10pt;
        }

        .separator {
            border: none;
            border-top: 1px double #000;
            margin: 5px 0;
            height: 3px;
        }

        .double-separator {
            border: none;
            border-top: 3px double #000;
            margin: 30px 0;
        }

        .footer-cert {
            text-align: center;
        }

        .date-line {
            display: flex;
            margin-top: 15px;
            margin-bottom: 60px;
        }

        .date-line .field-group {
            margin-bottom: 0;
            margin-right: 15px;
        }

        .date-input {
            width: 30px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 30%;
            text-align: center;
        }

        .signature-block p {
            margin: 0;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="certificate-form">

        <div class="header-disclaimer">
            (No change in any entry in this certificate shall be made except by the authority issuing it<br>
            & infringement of this requirement is liable to involve the imposition of a penalty such as<br>
            that of rustication.)
        </div>

        <div class="main-title">
            LEAVING CERTIFICATE
        </div>
        <div class="sub-title">
            (Prescribed Rules 14 & 30 in Chapter II Section II of the Grant-in-Aid Code)
        </div>

        <div class="passport-box">
            Passport Size
        </div>

        <div style="clear: both;">
            <div class="field-group">
                <span class="field-label">General Reg. No. : </span>
                <span class="input-line"> <?php
                $gr_number = '';

                if (isset($record['stvclas_standard']) && ($record['stvclas_standard'] == 'XI' || $record['stvclas_standard'] == 'XII')) {
                    $gr_number = isset($record['spriden_college_gr']) ? $record['spriden_college_gr'] : '';
                } else {
                    $gr_number = isset($record['spriden_gr_number']) ? $record['spriden_gr_number'] : '';
                }

                $gr_number = (string) $gr_number;
                $gr_arr = str_split($gr_number);
                if (!empty($gr_arr)) {
                    foreach ($gr_arr as $char) {
                        if (!empty($char) || $char === '0') {
                            echo '<span class="char-box">' . htmlspecialchars($char) . '</span>';
                        }
                    }
                }
                ?></span>
            </div>
            <div class="field-group">
                <span class="field-label">Student ID : </span>
                <span class="input-line"> <?php
                if (isset($record['SPRIDEN_reg_number'])) {
                    $str_reg = str_replace("ID:", '', $record['SPRIDEN_reg_number']);
                    $reg_arr = str_split($str_reg);

                    for ($i = 0; $i < 20; $i++) {
                        if (isset($reg_arr[$i])) {
                            echo '<span class="char-box">' . $reg_arr[$i] . '</span>';
                        } else {
                            echo '<span class="char-box">&nbsp;</span>';
                        }
                    }
                } else {
                    for ($i = 0; $i < 20; $i++) {
                        echo '<span class="char-box">&nbsp;</span>';
                    }
                }
                ?></span>
            </div>
            <div class="field-group">
                <span class="field-label">UID No. : </span>
                <span class="input-line"> <?php
                if (isset($record['SPRIDEN_aadhar_no']) && !empty($record['SPRIDEN_aadhar_no'])) {
                    $aadhar = $record['SPRIDEN_aadhar_no'];
                    $adh_arr = str_split($aadhar);

                    for ($i = 0; $i < 12; $i++) {
                        if (isset($adh_arr[$i])) {
                            echo '<span class="char-box">' . $adh_arr[$i] . '</span>';
                        } else {
                            echo '<span class="char-box">&nbsp;</span>';
                        }
                    }
                } else {
                    for ($i = 0; $i < 12; $i++) {
                        echo '<span class="char-box">&nbsp;</span>';
                    }
                }
                ?></span>
            </div>
            <div class="field-group">
                <span class="field-label">Name of School: </span>
                <span class="input-line">OLIVE ENGLISH MEDIUM SCHOOL & JR. COLLEGE DHULE</span>
            </div>
        </div>

        <hr class="separator">

        <div class="field-group">
            <span class="field-label">Student Full Name: </span>
            <span class="input-line"><?= $record['name'] ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Mother's Name: </span>
            <span class="input-line">
                <?php echo isset($record['SPRIDEN_mother_name']) ? $record['SPRIDEN_mother_name'] : ''; ?></span>
            <span class="field-label" style="padding-left: 20px;">Nationality:</span>
            <span class="input-line"> INDIAN</span>
        </div>

        <div class="field-group">
            <span class="field-label">Religion: </span>
            <span class="input-line short" style="width: 150px;">
                <?php echo isset($record['spriden_religion']) ? $record['spriden_religion'] : ''; ?></span>
            <span class="field-label" style="padding-left: 20px;">Caste: </span>
            <span class="input-line short" style="width: 150px;">
                <?php echo isset($record['cast']) ? $record['cast'] : ''; ?></span>
            <span class="field-label" style="padding-left: 20px;">Sub caste: </span>
            <span class="input-line"> <?php echo isset($record['sub_cast']) ? $record['sub_cast'] : ''; ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Place of Birth: </span>
            <span class="input-line">
                <?php echo isset($record['spriden_birth_place']) ? $record['spriden_birth_place'] : ''; ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Birth date according to Christian Era – in figures: </span>
            <span class="input-line"> <?php
            if (isset($record['spriden_birthdate']) && $record['spriden_birthdate'] != '0000-00-00') {
                $dob = date('d-m-Y', strtotime($record['spriden_birthdate']));
                $dob_arr = str_split($dob);

                for ($i = 0; $i < 10; $i++) {
                    if (isset($dob_arr[$i])) {
                        echo '<span class="char-box">' . $dob_arr[$i] . '</span>';
                    } else {
                        echo '<span class="char-box">&nbsp;</span>';
                    }
                }
            } else {
                for ($i = 0; $i < 10; $i++) {
                    echo '<span class="char-box">&nbsp;</span>';
                }
            }
            ?>
            </span>
        </div>

        <div class="field-group">
            <span class="field-label">In words: </span>
            <span class="input-line">
                <?php echo isset($record['birthdate_words']) ? $record['birthdate_words'] : ''; ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Last school attended: </span>
            <span class="input-line">
                <?php echo isset($record['spriden_last_school']) ? $record['spriden_last_school'] : ''; ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Date of admission: </span>
            <span class="input-line short" style="width: 150px;"> <?php

            $date_to_display = '00-00-0000';

            if (isset($record['SPRIDEN_date_created']) && $record['SPRIDEN_date_created'] != '0000-00-00' && !empty($record['SPRIDEN_date_created'])) {
                $date_to_display = date('d-m-Y', strtotime($record['SPRIDEN_date_created']));
            }

            $date_arr = str_split($date_to_display);
            for ($i = 0; $i < 10; $i++) {
                if (isset($date_arr[$i])) {
                    echo '<span class="char-box">' . $date_arr[$i] . '</span>';
                } else {
                    echo '<span class="char-box">&nbsp;</span>';
                }
            }

            ?></span>
            <span class="field-label" style="padding-left: 20px;">Std.: </span>
            <span class="input-line">
                <?php echo isset($record['spriden_admission_class']) ? $record['spriden_admission_class'] : ''; ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Progress: </span>
            <span class="input-line short" style="width: 120px;"> Good</span>
            <span class="field-label" style="padding-left: 20px;">Conduct:</span>
            <span class="input-line"> Good</span>
            <span class="field-label" style="padding-left: 20px;">Date of leaving school: </span>
            <span class="input-line">
                <?php
                $date_to_display = '00-00-0000';

                if (isset($record['leaving_date']) && $record['leaving_date'] != '0000-00-00' && !empty($record['leaving_date'])) {
                    $date_to_display = date('d-m-Y', strtotime($record['leaving_date']));
                }

                $date_arr = str_split($date_to_display);
                for ($i = 0; $i < 10; $i++) {
                    if (isset($date_arr[$i])) {
                        echo '<span class="char-box">' . $date_arr[$i] . '</span>';
                    } else {
                        echo '<span class="char-box">&nbsp;</span>';
                    }
                }
                ?>
            </span>
        </div>

        <div class="field-group">
            <span class="field-label">Std. in which studying & since when (in words & figures): </span>
            <span class="input-line"> <?php
            $std_info = '';
            if (isset($record['stvclas_standard']))
                $std_info .= $record['stvclas_standard'];
            if (isset($record['since_when']))
                $std_info .= ' / ' . $record['since_when'];
            echo $std_info;
            ?></span>
        </div>

        <div class="field-group">
            <span class="field-label">Reason of leaving school: </span>
            <span class="input-line"> AADHAAR UPDATE ONLY</span>
        </div>

        <div class="field-group">
            <span class="field-label">Remarks:</span>
            <span class="input-line"> FOR AADHAAR UPDATION</span>
        </div>

        <hr class="separator">

        <div class="footer-cert">
            Certified that the above information is in accordance with the School/College Register.
        </div>

        <div class="date-line">
            <span class="field-label">Date: - </span>
            <span class="field-value" style="display: inline-block; min-width: 100px;">
                <?php
                $date_to_display = date('d-m-Y');
                $date_arr = str_split($date_to_display);

                for ($i = 0; $i < 10; $i++) {
                    if (isset($date_arr[$i])) {
                        echo '<span class="char-box1">' . $date_arr[$i] . '</span>';
                    } else {
                        echo '<span class="char-box1">&nbsp;</span>';
                    }
                }
                ?>
            </span>
        </div>

        <div class="signatures">
            <div class="signature-block">
                <div style="height: 30px;"></div>
                <p>Class Teacher</p>
            </div>
            <div class="signature-block">
                <div style="height: 30px;"></div>
                <p>Head Master<br>(Seal & Signature)</p>
            </div>
        </div>
    </div>

</body>

</html>