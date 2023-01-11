<?php


class PCNOrderSync_Curl
{
    /**
     * @param $maxResults
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getOrdersFromPeriod($startDate, $endDate)
    {
        // Store credentials and period in array for cURL
        $data = array(
            'cid' => get_option('pcn_settings_olsuserid'),
            'olsuser' => get_option('pcn_settings_olsusername'),
            'olspass' => get_option('pcn_settings_olspassword'),
            'maxresults' => "*",
            'period' => array(
                'startdate' => $startDate,
                'enddate' => $endDate
            )
        );

        // Instantiate PCN StockSync cURL
        $stockCurl = new PCNStockSync_Curl();

        // Send cURL request through sendCurl function
        $jsonData = $stockCurl->sendCurl('sentorderslist', $data);

        // Return array from json decoded data
        return json_decode($jsonData);
    }
}
