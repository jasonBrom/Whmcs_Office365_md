<h2>创建账户：</h2>

{if $post_status_code eq '1'}
<div class="alert alert-danger">
    <strong>错误!</strong> {$error_message}
</div>
{/if}

{if $post_acc_code eq '1'}



    {if $post_sku_code eq '1'}
        <div class="alert alert-danger">
            <strong>错误!</strong> 分配许可证时遇到错误！请联系管理员手动分配许可证！   五秒后自动跳转...
        </div>
        <script>
            setTimeout(function(){
                window.location.href="clientarea.php?action=productdetails&id={$serviceid}";
            },5000);
        </script>
        {else}

        <div class="alert alert-success">
            <strong>账户开通成功!</strong> 正在跳转.....
        </div>
        <script>

                window.location.href="clientarea.php?action=productdetails&id={$serviceid}";

        </script>
    {/if}

<!-- BY:Jason_Brom -->
    {else}
    <form action="clientarea.php?action=productdetails" method="POST">
        <input type="hidden" name="id" value="{$serviceid}" />
        <input type="hidden" name="post_action" value="1" />

        <label for="exampleInputPassword1">用户名：</label>
        <div class="input-group mb-3">
            <input name="username" type="text" class="form-control" placeholder="请输入邮箱前缀" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">@{$domain}</span>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">密码：</label>
            <input type="password" class="form-control" name="password" placeholder="请输入密码">
        </div>

        <button type="submit" class="btn btn-primary">创建账户</button>
    </form>
{/if}






