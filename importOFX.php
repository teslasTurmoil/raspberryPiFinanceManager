 <?php
function paragraph($code) {
    global $text, $paragraph ;
    $start = strpos($text, '<'.$code.'>') ;
    if ($start>0) {
        $end = strpos($text, '</'.$code.'>')+strlen($code)+3 ;
        $paragraph = substr($text, $start, $end-$start) ;
        $text = substr($text, $end) ;
    } else $paragraph = '' ;
}

function value($parameter) {
    global $paragraph ;
    $start = strpos($paragraph, '<'.$parameter.'>') ;
    if ($start>0) {
        $start = $start+strlen($parameter)+2 ;
        $end = strpos($paragraph, '<',$start+1) ;
        return trim(substr($paragraph, $start, $end-$start)) ;
    } else return '' ;
}


$_file_ = $_FILES['file_ofx'];
if(is_uploaded_file($_file_['tmp_name']) && $_file_['error'] == 0){
    $ofx_msg = "";
    if($_size_ > 3200000) $ofx_msg = "Error:  The file is too large. (max 3M)";
    if(empty($errStr)){
        $file = $_file_['tmp_name'] ;
        if (file_exists($file)) {
            $file = fopen($file, "r") ;
            $text = '';
            while (!feof($file)) $text .= fread($file, 8192);
            fclose($file);    
            paragraph('BANKACCTFROM') ;
            if (strlen($paragraph)>0) {
                $code_bank = value('BANKID') ;
                $code_branch = value('BRANCHID') ;
                $num_account = value('ACCTID') ;
                mysql_select_db($database_locations, $locations);
                $query_rs_account = "SELECT id, libelle FROM tbl_account WHERE code_bank='$code_bank' AND code_branch='$code_branch' AND num_account='$num_account'";
                $rs_account = mysql_query($query_rs_account, $locations) or die(mysql_error());
                $row_rs_account = mysql_fetch_assoc($rs_account);
                $account_id = $row_rs_account['id'] ;
                $totalRows_rs_account = mysql_num_rows($rs_account);    
                if ($totalRows_rs_account == 1) {
                    $values = '' ;
                    $a_supprimer = array("'", ".") ;
                    paragraph('STMTTRN') ;
                    $i = 0 ;
                    while (strlen($paragraph)>0) {
                        $i += 1 ;
                        $type = value('TRNTYPE') ;
                        $date = value('DTPOSTED') ;
                        $amount = value ('TRNAMT') ;
                        $reste = $amount ;
                        $bank_transaction_id = $account_id.' - '.value('FITID') ;
                        $libelle = ucwords(strtolower(str_replace($a_supprimer, ' ', value('NAME')))) ;
                        $info = ucwords(strtolower(str_replace($a_supprimer, ' ', value ('MEMO')))) ;
                        $values .= "(".$account_id.",'".$type."',".$date.",".$amount.",".$reste.
                            ",'".$bank_transaction_id."','".$libelle."','".$info."'), " ;
                        paragraph('STMTTRN') ;
                    }
                    $values = substr($values, 0, strlen($values)-2) ;
                    mysql_select_db($database_locations, $locations);
                    $query_insert = "INSERT IGNORE INTO tbl_transaction (account_id, type, `date`, amount, reste, bank_transaction_id, libelle, info) VALUES $values";
                    if (mysql_query($query_insert, $locations) == 1)
                        $ofx_msg = "Successful import of transactions in the $ i account ".$row_rs_account['libelle'].'<br />'.mysql_info($locations) ;
                    else $ofx_msg = "Error in inserting transactions" ;
                } else $ofx_msg = "Error: bank account $code_bank / $code_branch / $num_account does not exist" ;
            } else $ofx_msg = "Error: The file does not appear to be a valid OFX file" ;
        } else $ofx_msg = "Error: Failure when opening file $file" ;
    } else $ofx_msg = "Error: the file was not downloaded" ;
} else $ofx_msg = "Error: you have not chosen a file" ;
?> 
