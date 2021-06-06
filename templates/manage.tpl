<h2>Office 365 账户信息</h2>

<p>在这里你可以查看和管理你的office365账户</p>



<hr>

<div class="row">
    <div class="col-sm-5">
        {$LANG.orderproduct}
    </div>
    <div class="col-sm-7">
        {$groupname} - {$product}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        订阅(sku_id)
    </div>
    <div class="col-sm-7">
        {$sku_id}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        用户名
    </div>
    <div class="col-sm-7">
        {$extraVariable1}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        初始密码
    </div>
    <div class="col-sm-7">
        {$extraVariable2}
    </div>
</div>

<hr>
<a href="https://www.office.com/login?es=Click&amp;ru=%2F">
    <button type="submit" class="btn btn-success">
                
                登陆 Office 365
</a>

<div class="row">
    <div class="col-sm-4">
        <form method="post" action="clientarea.php?action=productdetails">
            <input type="hidden" name="id" value="{$serviceid}" />
            <button type="submit" class="btn btn-default btn-block">
                <i class="fa fa-arrow-circle-left"></i>
                返回产品页面
            </button>
        </form>
    </div>
</div>
