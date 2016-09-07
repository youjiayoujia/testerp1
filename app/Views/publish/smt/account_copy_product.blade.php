@section('js')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(function(){
        //全选
        $('#checkAll').click(function(){
        	this.checked ? $('.account_list').find(':checkbox').prop('checked', true) : $('.account_list').find(':checkbox').prop('checked', false);
        });
    })
</script>
@stop
<div class="row">
    <div class="col-xs-12">
        <h3 class="header small lighter blue">速卖通刊登-产品复制</h3>

        <div>
            <div class="form-group clearfix">
                <ul class="list-inline">
                    <li>
                        <label for="checkAll">
                            <input id="checkAll" type="checkbox"/>全选/全不选
                        </label>
                    </li>
                </ul>
            </div>

            <div class="form-group clearfix">                    
                <?php foreach($account_list as $account):?>
                 <div class="col-lg-2  account_list" >                           
                        <label>
                            <input type="checkbox" name = "single[]" class='single' value="<?php echo $account['id'];?>" />
                            <?php echo $account['account'];?>
                        </label>
                 </div>
                <?php endforeach;?>                
            </div>
        </div>
    </div>
</div>