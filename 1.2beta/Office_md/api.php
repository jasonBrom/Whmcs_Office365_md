<?php

/**
 * API相关函数
 *
 */

/**
 * BY:Jason_Brom
 */



/**
 * 获取OAuth 2.0 客户端凭据
 *
POST /{tenant}/oauth2/v2.0/token
Host: login.microsoftonline.com
Content-Type: application/x-www-form-urlencoded

参数：client_id scope client_secret grant_type
 *
 */

function Office_md_get_token($client_id,$client_secret,$tenant_id){


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://login.microsoftonline.com/'.$tenant_id.'/oauth2/v2.0/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'client_id='.$client_id.'&scope=https%3A%2F%2Fgraph.microsoft.com%2F.default&client_secret='.$client_secret.'&grant_type=client_credentials',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    //解析数据
    $token_arr = json_decode($response, true);

    //print_r($token_arr);
    //var_dump($token_arr['dcsdc']);


    if($token_arr['access_token'] == NULL){
        return false;
    }else{
        return $token_arr['access_token'];
    }


}

/**
 *
 * 获取域名
 *
 */

function Office_md_get_domains($token){
    if($token == false){
        return false;
    }else{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.microsoft.com/v1.0/domains',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $domain_arrays = json_decode($response
        ,true);


        if(empty($domain_arrays['error'])){
            return array(
                'true' => 'true'
            );
        }else{

            return array(
                'error' => $domain_arrays['error']['message']
            );
        }



    }



}

/**
 * 创建用户
 *B_Y-Jason-Brom
 */
function Office_md_adduser($username,$domain,$password,$token){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/users',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "accountEnabled": true,
  "mailNickname": "'.$username.'",
  "displayName": "'.$username.'",
  "userPrincipalName": "'.$username.'@'.$domain.'",
  "usageLocation": "US",
  "passwordProfile" : {
    "forceChangePasswordNextSignIn": true,
    "password": "'.$password.'"
  }
}',
        CURLOPT_HTTPHEADER => array(
            'Content-type: application/json',
            'Authorization: Bearer '.$token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $user_responses = json_decode($response,true);

    if(empty($user_responses['error'])){
        return array(
            'true' => 'true'
        );
    }else{

        return array(
          'error' => $user_responses['error']['message']
        );
    }

}


/**
 * 分配许可证
 */


/**
 * BY:Jason_Brom
 */
function Office_md_Assign_licenses($user_email,$sku_id,$token){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/users/'.$user_email.'/assignLicense',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "addLicenses": [
    {
      "disabledPlans": [],
      "skuId": "'.$sku_id.'"
    }
  ],
  "removeLicenses": []
}',
        CURLOPT_HTTPHEADER => array(
            'Content-type: application/json',
            'Authorization: Bearer '.$token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $sku_arrs = json_decode($response,true);

    if(empty($sku_arrs['error'])){
        return array(
            'true' => 'true'
        );
    }else{

        return array(
            'error' => $sku_arrs['error']['message']
        );
    }


}

/**
 * 修改密码
 * 暂时不写
 */

function Office_md_change_Password($user_email,$password,$token){

}
