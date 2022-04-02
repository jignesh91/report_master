@extends('admin.layouts.app')

<?php
$auth_id = Auth::guard('admins')->user()->id;
$userid = $formObj->user_id;
if(!empty($userid))
    $user_id = $userid;
else
    $user_id = $auth_id;
?>

@section('styles')
 
@endsection
<?php
$auth_id = Auth::guard('admins')->user()->id;
?>
@section('content')

<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>
                           {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $back_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                           {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm1']) !!} 
                           
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">User <span class="required">*</span></label>                                        
                                        {!! Form::select('user_id',[''=>'Select User Name']+$users,$user_id,['class' => 'form-control', 'data-required' => false,'id'=>'country_list']) !!}
                                    </div>
                                   
                                    <div class="col-md-4">
                                        <label class="control-label">Leave From Date:</label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('from_date',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Select From Date','id'=>'start_date']) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            {!! Form::select('from_date_leave',['0'=>'Full','1'=>'Half'],$is_half_first,['class' => 'form-control font-blue', 'data-required' => true]) !!}
                                        </div>
                                    </div>                                    
                                    <div class="col-md-4">
                                        <label class="control-label">Leave To Date:</label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('to_date',null,['class' => 'form-control','placeholder' => 'Select To Date','id'=>'end_date']) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            {!! Form::select('to_date_leave',['0'=>'Full','1'=>'Half'],$is_half_last,['class' => 'form-control font-blue', 'data-required' => true]) !!}                                            
                                        </div>
                                    </div>                                                 
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Description: <span class="required">*</span></label>                            
                                        {!! Form::textarea('description',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Type Description','rows'=>4]) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Status: <span class="required">*</span></label>                            
                                        {!! Form::select('status',['1'=>'Accepted','0'=>'Pending','2'=>'Rejected'],null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" value="Save" class="btn btn-success pull-right" />
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts') 

<script type="text/javascript">
    $(document).ready(function () { 
        $('#main-frm1').submit(function () {
            
            if ($(this).parsley('isValid'))
            {
                $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function (result)
                    {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        if (result.status == 1)
                        {
                            $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                            window.location = result.goto;    
                        }
                        else
                        {
                            $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                        }
                    },
                    error: function (error) {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
                    }
                });
            }
            return false;
        });
    });
</script>

@endsection

