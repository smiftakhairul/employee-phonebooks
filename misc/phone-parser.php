<?php require_once('common-helper.php') ?>
<?php
    class PhoneParser {
        public static function analysisPhoneNumber($number)
        {
            $output = [];

            if (substr($number, 0, 1) == '+') {
                //
            } elseif (substr($number, 0, 2) == '00') {
                $number = '+' . substr($number, 2);
            } elseif (substr($number, 0, 2) == '07' || substr($number, 0, 2) == '02' || substr($number, 0, 2) == '03') {
                $number = '+4' . $number; // Romanian
            } else {
                $number = '+49' . $number; // German
            }

            foreach (getCountryCodes() as $code => $country) {
                if (substr(str_replace('+', '', $number), 0, strlen($code)) == $code) {
                    $output['prefix'] = '+' . $code;
                    $output['country'] = $country;
                    $output['number'] = str_replace($output['prefix'], '', str_replace(['-', ' '], ['', ''], $number));
                    break;
                }
            }

            return $output;
        }
    }
?>