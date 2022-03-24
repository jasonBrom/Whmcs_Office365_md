<?php
include 'api.php';
use WHMCS\Database\Capsule;


/**
 * BY:Jason_Brom
 */

/**
 * WHMCS SDK Sample Provisioning Module
 *
 * Provisioning Modules, also referred to as Product or Server Modules, allow
 * you to create modules that allow for the provisioning and management of
 * products and services in WHMCS.
 *
 * This sample file demonstrates how a provisioning module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Provisioning Modules are stored in the /modules/servers/ directory. The
 * module name you choose must be unique, and should be all lowercase,
 * containing only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "Office_md" and therefore all
 * functions begin "Office_md_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _ConfigOptions
 * function is required.
 *
 *
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/provisioning-modules/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license https://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related abilities and
 * settings.
 *
 * @see https://developers.whmcs.com/provisioning-modules/meta-data-params/
 *
 * @return array
 */
function Office_md_MetaData()
{
    return array(
        'DisplayName' => 'Office_md',
        'APIVersion' => '1.2', // Use API Version 1.1
        'RequiresServer' => true, // Set true if module requires a server to work
        'DefaultNonSSLPort' => '1111', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '1112', // Default SSL Connection Port
        'ServiceSingleSignOnLabel' => 'Login to Panel as User',
        'AdminSingleSignOnLabel' => 'Login to Panel as Admin',
    );
}

/**
 * Define product configuration options.
 *
 * The values you return here define the configuration options that are
 * presented to a user when configuring a product for use with the module. These
 * values are then made available in all module function calls with the key name
 * configoptionX - with X being the index number of the field from 1 to 24.
 *
 * You can specify up to 24 parameters, with field types:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each and their possible configuration parameters are provided in
 * this sample function.
 *
 * @see https://developers.whmcs.com/provisioning-modules/config-options/
 *
 * @return array
 */
function Office_md_ConfigOptions($params)
{


    $api_acc = array();


    $api_acc_sqls = Capsule::table('Office_md_admin')->get();

    foreach ($api_acc_sqls as $api_acc_sql){
        $api_acc[$api_acc_sql->id] = '('.$api_acc_sql->id.')'.$api_acc_sql->name;

    }


    return array(
        // a text field type allows for single line text input
        'SKU_ID' => array(
            'Type' => 'text',
            'Size' => '25',
            'Description' => '订阅的SKU_ID',
        ),
        'API' => array(
            'Type' => 'dropdown',
            'Options' => $api_acc,
            'Description' => '选择全局API'

        ),
        '域名' => array(
            'Type' => 'text',
            'Size' => '25',
            'Description' => '目前只支持一个域名'
        ),
    );
}

/**
 * 获取域名
 */


/**
 * BY:Jason_Brom
 */

function Office_md_gets_domain(array $params){
    $domain = array();
    $office_accs = Capsule::table('Office_md_admin')->where('id',$params['configoption2'])->first();

    foreach ($office_accs as $office_acc){
        $client_id = $office_acc->client_id;
        $client_secret = $office_acc->client_secret;
        $tenant_id = $office_acc->tenant_id;

        $token = Office_md_get_token($client_id,$client_secret,$tenant_id);

        $domain_array = Office_md_get_domains($token);

        foreach ($domain_array['value'] as $domains){
            //$domain[$domains['id']] = $domains['id'];
            $domain['null'] = $params['configoption2'];
            $domain['cd'] = 'dsfs';


        }

        return $domain;

    }

}

/**
产品开通函数
 */
function Office_md_CreateAccount(array $params)
{
    try {

        /**
         *
         * 写入初始数据
         */

        Capsule::table('Office_md')->insert([
            'serviceid' => $params['serviceid'],
            'status' => '1',
            'organization_id' => $params['configoption2'],
            'pid' => $params['pid']
        ]);


        // Call the service's provisioning function, using the values provided
        // by WHMCS in `$params`.
        //
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     'domain' => 'The domain of the service to provision',
        //     'username' => 'The username to access the new service',
        //     'password' => 'The password to access the new service',
        //     'configoption1' => 'The amount of disk space to provision',
        //     'configoption2' => 'The new services secret key',
        //     'configoption3' => 'Whether or not to enable FTP',
        //     ...
        // )
        // ```
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'Office_md',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}




/**
 * 修改密码函数
 */
/*
function Office_md_ChangePassword(array $params)
{
    try {
        // Call the service's change password function, using the values
        // provided by WHMCS in `$params`.
        //
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     'username' => 'The service username',
        //     'password' => 'The new service password',
        // )
        // ```
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'Office_md',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}
*/


/**
 * BY:Jason_Brom
 */

/**
 * Execute actions upon save of an instance of a product/service.
 *
 * Use to perform any required actions upon the submission of the admin area
 * product management form.
 *
 * It can also be used in conjunction with the AdminServicesTabFields function
 * to handle values submitted in any custom fields which is demonstrated here.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see Office_md_AdminServicesTabFields()
 */
//b_y=J.a.s.o.n_B.r.o.m
function Office_md_AdminServicesTabFieldsSave(array $params)
{
    // Fetch form submission variables.
    $originalFieldValue = isset($_REQUEST['Office_md_original_uniquefieldname'])
        ? $_REQUEST['Office_md_original_uniquefieldname']
        : '';

    $newFieldValue = isset($_REQUEST['Office_md_uniquefieldname'])
        ? $_REQUEST['Office_md_uniquefieldname']
        : '';

    // Look for a change in value to avoid making unnecessary service calls.
    if ($originalFieldValue != $newFieldValue) {
        try {
            // Call the service's function, using the values provided by WHMCS
            // in `$params`.
        } catch (Exception $e) {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'Office_md',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            // Otherwise, error conditions are not supported in this operation.
        }
    }
}


/**
 * Client area output logic handling.
 *
 * This function is used to define module specific client area output. It should
 * return an array consisting of a template file and optional additional
 * template variables to make available to that template.
 *
 * The template file you return can be one of two types:
 *
 * * tabOverviewModuleOutputTemplate - The output of the template provided here
 *   will be displayed as part of the default product/service client area
 *   product overview page.
 *
 * * tabOverviewReplacementTemplate - Alternatively using this option allows you
 *   to entirely take control of the product/service overview page within the
 *   client area.
 *
 * Whichever option you choose, extra template variables are defined in the same
 * way. This demonstrates the use of the full replacement.
 *
 * Please Note: Using tabOverviewReplacementTemplate means you should display
 * the standard information such as pricing and billing details in your custom
 * template or they will not be visible to the end user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function Office_md_ClientArea(array $params)
{
    //获取订单数据

    $acc_states = Capsule::table('Office_md')->where('serviceid',$params['serviceid'])->first();
    //获取接口相关信息

    $api_acc = Capsule::table('Office_md_admin')->where('id',$params['configoption2'])->first();


    //初始化接口数据
    $client_id = $api_acc->client_id;
    $client_secret = $api_acc->client_secret;
    $tenant_id = $api_acc->tenant_id;


    $account_information = array();
    if($_POST['post_action'] == '1'){

        $account_information['password'] = $_POST['password'];
        $account_information['username'] = $_POST['username'];

        $token = Office_md_get_token($client_id,$client_secret,$tenant_id);
        if($token == false){
            $post_status_code = '1';
            $error_message = '获取Token出现错误,请联系管理员';

        }else{
            //创建用户
            $adduser = Office_md_adduser($account_information['username'],$params['configoption3'],$account_information['password'],$token);
            if($adduser['true'] == 'true'){
                //分配许可证
                $user_email = $account_information['username'].'@'.$params['configoption3'];

                $post_sku_arr = Office_md_Assign_licenses($user_email,$params['configoption1'],$token);

                if($post_sku_arr['true'] == 'true'){
                    $post_sku_code = '2';
                }else{
                    $post_sku_code = '1';
                }

                //更新订单数据

                Capsule::table('Office_md')->where('serviceid',$params['serviceid'])->update([
                    'status' => '2',
                    'email' => $user_email,
                    'password' => $account_information['password']
                ]);

                $acc_templates = 'templates/initialization.tpl';
                $post_acc_code = '1';

                return array(
                    'tabOverviewReplacementTemplate' => $acc_templates,
                    'templateVariables' => array(
                        'domain' => $params['configoption3'],
                        'params' => $params,
                        'email' => $acc_states->email,
                        'password' => $acc_states->password,
                        'state' => $acc_states->status,
                        'account_information' => $account_information,
                        'token' => $token,
                        'post_acc_code' => $post_acc_code,
                        'post_sku_code' => $post_sku_code
                    ),
                );

            }else{
                $post_status_code = '1';
                if($adduser['error'] == 'Another object with the same value for property userPrincipalName already exists.') {
                    $error_message = '邮箱前缀被占用，请修改您提交的信息！';
                }elseif ($adduser['error'] == 'The specified password does not comply with password complexity requirements. Please provide a different password.') {
                    $error_message = '密码强度不符合要求，请提供其他密码！';
                }elseif ($adduser['error'] == "Invalid value specified for property 'mailNickname' of resource 'User'." or $adduser['error'] = 'Property userPrincipalName is invalid.'){
                    $error_message = '用户名不符合规则/为空，请更改您提交的信息！';
                }else{
                    $error_message = '('.$adduser['error'].') 如果您不了解此错误信息，请联系管理员！';
                }

            }
        }

    }



    if($acc_states->status == '1') {
        $acc_templates = 'templates/initialization.tpl';
    }elseif($acc_states->status == '2'){
        $acc_templates = 'templates/account_information.tpl';
    }else{
        $acc_templates = 'templates/error.tpl';
    }




    // Determine the requested action and set service call parameters based on
    // the action.


    try {
        // Call the service's function based on the request action, using the
        // values provided by WHMCS in `$params`.
        $response = array();


        return array(
            'tabOverviewReplacementTemplate' => $acc_templates,
            'templateVariables' => array(
                'domain' => $params['configoption3'],
                'params' => $params,
                'email' => $acc_states->email,
                'password' => $acc_states->password,
                'state' => $acc_states->status,
                'account_information' => $account_information,
                'token' => $token,
                'error_message' => $error_message,
                'post_status_code' => $post_status_code
            ),
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'Office_md',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}
