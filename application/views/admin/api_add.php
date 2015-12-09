<div class="content">
    <div class="header">
      <h1 class="page-title">新增接口</h1>
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo base_url(); ?>">主页</a>
        </li>
        <li>
          <a href="<?php echo site_url('c=coupon&m=show_coupons'); ?>">所在目录</a>
        </li>
        <li class="active">新增接口</li>
      </ul>
    </div>
  <div class="main-content">
      <?php if(isset($info)) {  ?>
          <div class="alert alert-<?php echo $info_type; ?>">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <?php echo $info; ?>
          </div>
      <?php } ?>
    <!--添加接口 start-->
    <div style="border:1px solid #ddd">
      <div style="background:#f5f5f5;padding:20px;position:relative">
        <h4><span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
        <div style="margin-left:20px;">
          <form action ="<?php echo site_url('c=api&m=api_add').'&cid='.$cid?>" method="post">
            <h5>基本信息</h5>
            <div class="form-group has-error">
              <div class="input-group" id="api_number">
                <div class="input-group-addon">
                  接口编号
                </div>
                <input type="text" class="form-control" name="number" id="num" placeholder="接口编号" required="required"  onblur="getNum()">
              </div>
            </div>
            <div class="form-group has-error">
              <div class="input-group">
                <div class="input-group-addon">接口名称</div>
                <input type="text" class="form-control" name="name" placeholder="接口名称" required="required">
              </div>
            </div>
            <div class="form-group has-error">
              <div class="input-group">
                <div class="input-group-addon" onclick="tabUrl()">请求地址</div>
                <input type="text" class="form-control" name="url_name"  id = "url_name" placeholder="请求地址" required="required">
              </div>
            </div>
            <div class="form-group has-error" >
              <div class="input-group" >
                <div class="input-group-addon" >所在目录</div>
                <select class="form-control"  name="cid" required="required" disabled>
                    <option value="<?php echo $cid;?>" selected><?php echo $category_name;?></option>'
                    <input type="hidden" value="<?php echo $cid;?>" name="parent_id">
                </select>
              </div>
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" placeholder="描述"></textarea>
            </div>
            <div class="form-group">
              <select class="form-control" name="type" required = "required">
                <option value="1">POST</option>
                <option value="2">GET</option>
              </select>
            </div>
            <div class="form-group">
              <h5><button type="button" class="btn btn-success" onclick="getParams()">获取参数</button></h5>
              <div class="form-group has-error" >
                <div class="input-group">
                  <div class="input-group-addon" onclick="getParams()">参数获取地址前缀</div>
                  <input type="text" class="form-control" name="prefix"  id = "prefix" value="<?php
                    if ($this->session->userdata('custom_id') == 0){
                        echo "http://test.api.popdr.gi4t.com";
                    }else{
                        $this->load->model('custom_model');
                        $row = $this->custom_model->get_custom_row($this->session->userdata('custom_id'));
                        echo $row->url_name;
                    }
                    ?>">
                </div>
              </div>
              <table class="table" id="param_table" >
                <thead>
                <tr>
                  <th class="col-md-2">参数名</th>
                  <th class="col-md-1">必传</th>
                  <th class="col-md-1">缺省值</th>
                  <th class="col-md-3">描述</th>
                  <th class="col-md-4">校验规则</th>
                  <th class="col-md-1">
                    <button type="button" class="btn btn-success" onclick="add()">新增</button>
                  </th>
                </tr>
                </thead>
                <tbody id="parameter">
                </tbody>
              </table>
            </div>
            <div class="form-group">
              <h5><button type="button" class="btn btn-success" onclick="test()">测试结果</button></h5>
              <h5><a id="various1" href="index.php?c=api&m=show_api_add&cid=<?=$cid?>#inline1" onclick="test()">测试结果</a></h5>
              <textarea name="res" rows="3" class="form-control" placeholder="返回结果"></textarea>
            </div>
            <div class="form-group">
              <h5>备注</h5>
              <textarea name="remark" rows="3" class="form-control" placeholder="备注"></textarea>
            </div>
            <button class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    </div>
      <div id="inline1">
      </div>
    <!--添加接口 end-->
  </div>
    <link rel="stylesheet" href="lib/jquery.fancybox.css">
    <script src="lib/jquery.fancybox.js"></script>
    <script>
        function getParams(){
            var dr_url = document.getElementById("url_name").value;
            var prefix = document.getElementById("prefix").value;
            $.ajax({
                url : "index.php?c=test&m=get_params&uri="+dr_url+"&prefix="+prefix,
                "contentType": "application/x-www-form-urlencoded; charset=utf-8",
                data: {

                },
                type: 'get',
                dataType: 'json',
                success: function(d){
                    $('#num').html("");
                    var $html = "";
                    if (d != null ){
                        $('#parameter').html("");
                        for (i=0;i< d.length;i++){
                            var $y ="";
                            var $n ="";
                            if (d[i].is_require == 'Y'){
                                $y = "selected";
                            }else {
                                $n = "selected";
                            }
                            $html = $html +
                                '<tr>' +
                                '<td class="form-group has-error" >' +
                                '<input type="text" class="form-control has-error" name="p[name][]" placeholder="参数名" required="required" value="'+d[i].name+'"></td>'+
                                '<td>' +
                                '<select class="form-control" name="p[type][]">' +
                                '<option value="Y" '+$y+'>Y</option> <option value="N" '+$n+'>N</option>' +
                                '</select >' +
                                '</td>' +
                                '<td>' +
                                '<input type="text" class="form-control" name="p[default][]" placeholder="缺省值"></td>' +
                                '<td>' +
                                '<input type="text" class="form-control" name="p[des][]" rows="1" class="form-control" placeholder="描述">' +
                                '</td>' +
                                '<td>' +
                                '<input type="text" class="form-control" name="p[rules][]" rows="1" class="form-control" placeholder="检验规则" value= "'+ d[i].rules +'">' +
                                '</td>' +
                                '<td>' +
                                '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                                '</td>' +
                                '</tr >';
                        }
                        $('#parameter').append($html);
                    }
                }
            });
        }
        function getNum(){
            var api_num = document.getElementById("num").value;
            $.ajax({
                url : "index.php?c=test&m=get_num",
                "contentType": "text/html; charset=utf-8",
                data: {
                    num : api_num
                },
                type: 'get',
                dataType: 'text',
                success: function (d){
                    if(d != null){
                        $('#api_number input').val(d);
                    }
                }
            });
        }
        function add(){
            var $html ='<tr>' +
                '<td class="form-group has-error" ><input type="text" class="form-control has-error" name="p[name][]" id="p[name][]" placeholder="参数名" required="required"></td>'+
                '<td>' +
                '<select class="form-control" name="p[type][]">' +
                '<option value="Y">Y</option> <option value="N">N</option>' +
                '</select >' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="p[default][]" placeholder="缺省值"></td>' +
                '<td>' +
                '<textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea>' +
                '</td>' +
                '<td>' +
                '<textarea name="p[rules][]" rows="1" class="form-control" placeholder="检验规则"></textarea>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                '</td>' +
                '</tr >';
            $('#parameter').append($html);
        }
        function del(obj){
            $(obj).parents('tr').remove();
        }
        function test(){
            var count = $("#param_table").find("tr").length;
            var $html = "";
            for (var i=1;i<count;i++){
                var p_name = $("#param_table").find("tr").eq(i).find("td").eq(0).find("input").eq(0).val();
                var isNeed = $('select[name="p[type][]"]').eq(i-1).val();
                var req = "";
                if (isNeed == "Y"){
                    req = ' required="required" ';
                }
                $html += '<input type="text" id="'+p_name+'" placeholder ="'+p_name+ '"' +req+'><br>';
            }
            $('#inline1').html($html);
//            $('#test_form').append($html);
//            $('#test_field').show();
        }

        $("#various1").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'beforeShow': function(){
                var count = $("#param_table").find("tr").length;
                var uri_val = document.getElementById('url_name');
                var $html = '<div style="margin-left:20px;" id="test_field"> \
                <form action ="<?php echo site_url('c=test&m=test_result')?>" method="post"> \
                    <div class="form-group"> \
                    <div class="input-group" id="test_form">';
                $html += '<input type="hidden" value ="'+url_val+'" id="uri">';
                for (var i=1;i<count;i++){
                    var p_name = $("#param_table").find("tr").eq(i).find("td").eq(0).find("input").eq(0).val();
                    var isNeed = $('select[name="p[type][]"]').eq(i-1).val();
                    var req = "";
                    if (isNeed == "Y"){
                        req = ' required="required" ';
                    }
                    $html += '<input type="text" id="'+p_name+'" placeholder ="'+p_name+ '"' +req+'><br>';
                }
                $html += '                          </div> \
                    </div> \
                    <button class="btn btn-success btn-xs" onclick="">Submit</button> \
                    </form> \
                    </div>';
                $('#inline1').html($html);
            }
        });

    </script>

</div>

