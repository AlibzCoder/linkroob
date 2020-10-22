<?php /* Template Name: payment */
if(is_user_logged_in()){ 
	$user = wp_get_current_user();
    get_header();
?>
<div id="article">
	<div id="continer">
    <style>
        .message-box{display: table;margin: 2em auto;text-align:center;}
        .message-box h3{color: #2888d2;direction:rtl;}
        .message-box a{color: #848484;}
        .message-box a:hover{color:#2888d2;}
    </style>
    <?php

        function echoMessage($message){
            echo "<div class='message-box'><h3>{$message}</h3><a href='".home_url('user-panel')."'>بازگشت به پنل کاربری</a></div>";
        }

        $offerId = $wpdb->escape(trim($_GET['offerId']));

        if(!empty($offerId)){
            $offer =  get_offer($offerId);
            $offer->price = (int) $offer->price;
            if($offer->currency=="r"){
                $offer->price /= 10; // Convert to Toman if the currency is rial
            }

            $Description = null;
            if($offer->type=="vip"){
                $type = null;
                if($offer->time_type=="day"){$type = "روزه";}
                else if($offer->time_type=="month"){$type = "ماهه";}
                else if($offer->time_type=="year"){$type = "ساله";}
                $Description = "خرید وی ای پی";
                $Description.= " {$offer->count} {$type} ";
            }else if($offer->type=="upgrade"){
                $Description = "خرید ارتقا ";
            }
            
            $MerchantID = '704c9f26-e437-4bbe-ad7e-aca441225ce7';
            $Amount = $offer->price; //Amount will be based on Toman
            $Authority = $_GET['Authority'];

            if(!empty($Authority)){
                if ($_GET['Status'] == 'OK') {
                    // URL also can be ir.zarinpal.com or de.zarinpal.com
                    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

                    $result = $client->PaymentVerificationWithExtra([
                        'MerchantID'     => $MerchantID,
                        'Authority'      => $Authority,
                        'Amount'         => $Amount,
                    ]);
                    if ($result->Status == 100) {
                        $chanel = get_chanel(json_decode($result->ExtraDetail)->chanel_id);
                        if($offer->type=="vip"){
                            getVIP($chanel->chanel_id,$offer->id);
                        }else if($offer->type=="upgrade"){
                            upgrade($chanel->chanel_id);
                        }
                        $Description .= "برای کانال '{$chanel->post_title}' ";
                        echoMessage($Description." با موفقیت انجام شد"." <br/> {$result->RefID}");
                    }else{
                        echoMessage("خرید ناموفق <br/> {$result->Status}");
                    }
                } else {
                    echoMessage('خرید توسط کاربر لغو شد');
                }
            }else{wp_redirect(home_url());}
        }else{wp_redirect(home_url());}
 
 
    get_footer();
}else{
	wp_redirect(home_url('log-in'));
	exit;
} ?>