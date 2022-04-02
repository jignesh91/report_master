<?php

/**
 * Website General Functions
 *
 */
function getPaidLeaveDays($leave_id)
{
    $leave_req = App\Models\LeaveRequest::find($leave_id);

    $from_date = date("Y-m-d", strtotime($leave_req->from_date));
    $to_date = date("Y-m-d", strtotime($leave_req->to_date));

    $query = App\Models\LeaveDetail::where("leave_id", $leave_req->id)
            ->whereBetween('date', [$from_date, $to_date])
            ->get();

    $days = 0;
    foreach ($query as $q) {
        if($q->is_half == 1)
            $day =0.5;
        else
            $day =1;
        $days +=$day;
    }
    return $days;
}
function calUserPaidLeave($user_id)
{
    $msg = 'Not taken any leaves in this month !';

    $month_leave =  App\Custom::usermothleave($user_id);

    $user_detail = App\Models\User::find($user_id);
    if(!empty($user_detail) && $month_leave>0)
    {
        $current_balance_leave = $user_detail->balance_paid_leave;
        $type = 'debit';
        $days = $month_leave;

        $new_balance_leave = $current_balance_leave - $month_leave;
        //$total_leave = $current_balance_leave - $new_balance_leave;
        
        if($new_balance_leave < 0)
        {
            $new_balance_leave = 0;
        }

        $user_detail->balance_paid_leave = $new_balance_leave;
        $user_detail->save();

        $log = new App\Models\LeaveEmtitlementLog();

        $log->user_id = $user_id;
        $log->credit_debit_type = $type;
        $log->total_leaves = $days;
        $log->old_balance_leave = $current_balance_leave;
        $log->new_balance_leave = $new_balance_leave;
        $log->remark = 'Admin calculated leave from users list';
        $log->save();

        $msg = "Leave calculated successfully ! <br />Total leaves:  <b>$days</b>, Old Balance Leaves: <b>$current_balance_leave</b>, New Balance Leaves: <b>$new_balance_leave</b>";
    }

    return $msg;
}

function getFilename($fullpath, $uploaded_filename) {
    $count = 1;
    $new_filename = $uploaded_filename;
    $firstinfo = pathinfo($fullpath);

    while (file_exists($fullpath)) {
        $info = pathinfo($fullpath);
        $count++;
        $new_filename = $firstinfo['filename'] . '(' . $count . ')' . '.' . $info['extension'];
        $fullpath = $info['dirname'] . '/' . $new_filename;
    }

    return $new_filename;
}

function humanTiming($time) {

    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;

    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}

function removeDir($dir) {
    foreach (glob($dir . "/*.*") as $filename) {
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    if (is_dir($dir . "feature")) {

        foreach (glob($dir . "feature/*.*") as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }

        rmdir($dir . "feature");
    }

    rmdir($dir);
}

function sendHtmlMail($params) {
    $files = isset($params['files']) ? $params['files'] : array();

    if(isset($params['from']))
        $from = $params['from'];
    else
        $from = "reports.phpdots@gmail.com";

    $params['from'] = $from;
    
    $toEmails[] = $params['to'];   

    if(isset($params['ccEmails']))
    {
        foreach($params['ccEmails'] as $em)
        {
            $toEmails[] =  $em;
        }
    }
    
    $params['to_emails'] = $toEmails;
    

    /*\Mail::send('emails.index', $params, function($message) use ($params, $files) {
		
		$fromName = "PHPDots";
		if(isset($params['from_name']))
		{
			$fromName = $params['from_name'];
		}	

        if(isset($params['from']))
        {
            $message->from($params['from'], $fromName);
            $message->sender($params['from'], $fromName);
        }

        $message->to($params['to_emails'], '')->subject($params['subject']);

        if (count($files) > 0) 
        {
            foreach ($files as $file) {
                $message->attach($file['path']);
            }
        }
    }); */

    $dataToInsert = [
            'to_email' => $params['to'],
            'cc_emails' => '',
            'bcc_emails' => '',
            'from_email' => $from,
            'email_subject' => $params['subject'],
            'email_body' => $params['body'],
            'mail_response' => '',
            'status' => 1,
            'ip_address' => '.ip',
            'is_mandrill' => 1,
            'created_at' => \DB::raw('NOW()'),
            'updated_at' => \DB::raw('NOW()')
        ];

        \DB::table(TBL_EMAIL_SENT_LOG)->insert($dataToInsert);
}

// to generate random string
function getRandomNum($len = 6) {
    //$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    $chars = "0123456789";
    $r_str = "";
    for ($i = 0; $i < $len; $i++)
        $r_str .= substr($chars, rand(0, strlen($chars)), 1);

    if (strlen($r_str) != $len) {
        $r_str .= getRandomNum($len - strlen($r_str));
    }

    return $r_str;
}

// to generate random string number
function getRandomStringNumber($len = 30) {
    // $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $chars = "0123456789";
    $r_str = "";
    for ($i = 0; $i < $len; $i++)
        $r_str .= substr($chars, rand(0, strlen($chars)), 1);

    if (strlen($r_str) != $len) {
        $r_str .= getRandomStringNumber($len - strlen($r_str));
    }

    return $r_str;
}

// for table heading sorting link
function getSortingLink($link, $heading, $field, $curSortBy = '', $curSortOrder = 'asc', $search_field = '', $search_val = '', $extra_params = '') {

    $qs = '?';
    if (strpos($link, '?') != false) {
        $qs = '&';
    }



    if ($field != $curSortBy) {
        $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
    } elseif ($field == $curSortBy) {
        if ($curSortOrder == "asc") {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=desc';
        } elseif ($curSortOrder == "desc") {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
        } else {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
        }
    }

    if ($search_field != "" && $search_val != "") {
        $link .= '&search_field=' . $search_field . "&search_text=" . $search_val;
    }

    if ($extra_params != "") {
        $link .= "&" . $extra_params;
    }


    return '<a href="' . $link . '">' . $heading . '</a>';
}

function dateFormat($date, $format = '', $withTime = false) {


    if ($date == "0000-00-00 00:00:00" || $date == "0000-00-00" || $date == "0000-00-00 00:00:00000000" || $date == "" || is_null($date)) {
        return '-';
    }

    $temp = '';
    if ($withTime) {
        $temp = ' H:i a';
    }

    if ($format == '') {
        return date(env('APP_DATE_FORMAT', 'Y-m-d') . $temp, strtotime($date));
    } else {
        return date($format, strtotime($date));
    }
}

function downloadFile($filename, $filepath) {
    $fsize = filesize($filepath);
    header('Pragma: public');
    header('Cache-Control: public, no-cache');
    header('Content-Type: ' . mime_content_type($filepath));
    header('Content-Length: ' . $fsize);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    readfile($filepath);
    exit;
}

function isDigits($quantity) {
    return preg_match("/[^0-9]/", $quantity);
}

function displayPrice($price, $with_symbol = 1) {
    if ($with_symbol == 1)
        return "$" . number_format($price, 2);
    else
        return number_format($price, 2);
}

function makeDir($path) {
    if (!is_dir($path)) {
        mkdir($path);
        chmod($path, 0777);
    }
}

function get_month_name($month) {
    $months = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );

    return $months[$month];
}

function NVPToArray($NVPString) {
    $proArray = array();

    while (strlen($NVPString)) {
        // name
        $keypos = strpos($NVPString, '=');
        $keyval = substr($NVPString, 0, $keypos);
        // value
        $valuepos = strpos($NVPString, '&') ? strpos($NVPString, '&') : strlen($NVPString);
        $valval = substr($NVPString, $keypos + 1, $valuepos - $keypos - 1);
        // decoding the respose
        $proArray[$keyval] = urldecode($valval);
        $NVPString = substr($NVPString, $valuepos + 1, strlen($NVPString));
    }

    return $proArray;
}

/**
 * Website General Model Functions
 *
 */
function getRecordsFromSQL($sql, $returnType = "array") {
    $result = \DB::select($sql);

    if ($returnType == "array") {
        $result = json_decode(json_encode($result), true);
    } else {
        return $result;
    }
}

function getRecords($table, $whereArr, $returnType = "array") {
    $result = \DB::table($table)->from($table);

    if (is_array($whereArr) && count($whereArr) > 0) {
        foreach ($whereArr as $field => $value) {
            $result->where($field, $value);
        }
    }

    $result = $result->get();


    if ($returnType == "array") {
        $result = json_decode(json_encode($result), true);
    } else {
        return $result;
    }
}

function GetUserIp() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    /* If Local IP */
    if ($ipaddress == "UNKNOWN" || $ipaddress == "127.0.0.1")
        $ipaddress = '72.229.28.185'; //NY

        /* if($ipaddress == '203.88.138.46') { //GJ
          $ipaddress = '128.101.101.101'; //MN
          $ipaddress = '24.128.151.64'; //Adrian
          $ipaddress = '66.249.69.245'; //CA
          $ipaddress = '72.229.28.185'; //NY
          $ipaddress = '127.0.0.1'; //UNKNOWN
          $ipaddress = '2603:300a:f05:a000:2970:d9ff:9da:ccd6'; //Patrick mobile IPv6
          } */

    return $ipaddress;
}

function getAdminUserTypes()
{
    $array = array();
    
    $rows = \DB::table("admin_user_types")->get();

    foreach($rows as $row)
    {
        $array[$row->id] = $row->title;    
    }    

    return $array;
}

function superAdmin($current_login_user_id){

    if($current_login_user_id == SUPER_ADMIN_ID || $current_login_user_id == 1)
        return 1;
    else
        return 0;
}
function sendSms($mobileNumber,$message,$member_id,$auth_id){

    //Send Message
    $username ='Jrathod';
    $password ='966143';
    $senderId = "DGENIT";
	
	;
    
    //API URL
    $url = 'http://45.114.143.11/api.php?username='.$username.'&password='.$password.'&sender='.$senderId.'&sendto='.$mobileNumber.'&message='.urlencode($message);

    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec ($curl);
    curl_close ($curl);

    $dataToInsert = [
            'login_user_id' => $auth_id,
            'member_id' => $member_id,
            'mobile' => $mobileNumber,
            'sms_body' => $message,
            'sms_response' => $result,
            'created_at' => \DB::raw('NOW()'),
            'updated_at' => \DB::raw('NOW()')
        ];

        return \DB::table(TBL_SMS_SENT_LOG)->insertGetId($dataToInsert);
     
}

function numberToWord($number)
{
    //$number = 190908100.25;
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }

    $str = array_reverse($str);
    $result = implode('', $str);

  return $result . " only";
}
function getMonths()
{
    $months = ['01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December']; 
    return $months;
}
function getYear()
{
    $years = ['2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025','2026'=>'2026','2027'=>'2027','2028'=>'2028','2029'=>'2029','2030'=>'2030','2031'=>'2031','2032'=>'2032','2033'=>'2033','2034'=>'2034','2035'=>'2035','2036'=>'2036','2037'=>'2037','2038'=>'2038','2039'=>'2039','2040'=>'2040'];
    return $years;
}
function customDatatble($module)
{
    $orderArr = request()->get("order");
    \session()->put([$module.'_orderClm' => $orderArr[0]['column']]);
    \session()->put([$module.'_orderDir' => $orderArr[0]['dir']]);
    \session()->put([$module.'_length' => \request()->get("length")]);
    \session()->put([$module.'_start' => \request()->get("start")]);
}
function customIndexAttr($module, $data)
{
    $data['orderClm'] = session()->get($module.'_orderClm');
    $data['orderDir'] =  session()->get($module.'_orderDir');
    $data['length'] =  session()->get($module.'_length');
    $data['start'] =  session()->get($module.'_start');
    return $data;
}
function customSession($module,$data,$length = 25)
{
    $goto = session()->get($module.'_goto');
    $data['orderClm'] = '0';
    $data['orderDir'] = "desc";
    $data['length'] = $length;
    $data['start'] = 0;

    if(!empty($goto))
    {
        $data = customIndexAttr($module, $data);
    }
    return $data;
}
function customBackUrl($module, $list_url, $data)
{
    $goto = session()->get($module.'_goto');
    if(empty($goto))
    {
        $goto = $list_url;
    }
    $data["back_url"] = $goto;
    return $data;
}