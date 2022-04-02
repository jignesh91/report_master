@extends('admin.layouts.app')
<?php
$profile_pic =$formObj->image;
$user_id =$formObj->id;
?>
@section('styles')
<link href="{{ asset("themes/admin/assets/")}}/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
@endsection
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
                           {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Personal Details</legend>
                                <div class="row">
                                <div class="col-md-12" align="right">
                                    <div class="form-group last">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            @if(!empty($formObj->image))
                                                <img src='{{ asset("/uploads/users/$user_id/$profile_pic")}}' alt="" />
                                            @else
                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="image"> </span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
								<div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Firstname <span class="required">*</span></label>
                                        {!! Form::text('firstname',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter First Name']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Lastname <span class="required">*</span></label>
                                        {!! Form::text('lastname',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Last Name']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Email <span class="required">*</span></label>                                        
                                        {!! Form::text('email',null,['class' => 'form-control', 'data-required' => true, 'data-type' => 'email','placeholder'=>'Enter Email']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Mobile <span class="required">*</span></label>                                        
                                        {!! Form::text('phone',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Mobile number']) !!}
                                    </div>
                                    
                                </div>
                                @if(isset($show_password) && $show_password == 1)
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Password <span class="required">*</span></label>                                        
                                        {!! Form::password('password',['class' => 'form-control','data-required' => 'true','placeholder'=>'Enter Password']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Confirm Password <span class="required">*</span></label>                                        
                                        {!! Form::password('confirm_password',['class' => 'form-control','data-required' => 'true','placeholder'=>'Enter Confirm Password']) !!}
                                    </div>   
                                </div>
                                @endif
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">User Type <span class="required">*</span></label>                                        
                                        {!! Form::select('user_type_id',['' => 'select Type']+$users_type,null,['class' => 'form-control', 'data-required' => true,'id'=>'user_id']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Address <span class="required">*</span></label>                                        
                                        {!! Form::textarea('address',null,['class' => 'form-control', 'data-required' => true,'rows'=>1,'placeholder'=>'Enter Address']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Status: <span class="required">*</span></label>                            
                                        {!! Form::select('status',['1'=>'Active','0'=>'inactive'],null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Blood Group: <span class="required">*</span></label>       
                                         {!! Form::select('blood_group',[''=>'Select Blood Group']+$blood_groups,null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">PAN No. </label>                                        
                                        {!! Form::text('pan_num',null,['class' => 'form-control', 'data-required' => false,'placeholder'=>'Enter PAN Number']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Adhar No.</label>                                        
                                        {!! Form::text('adhar_num',null,['class' => 'form-control', 'data-required' => false,'placeholder'=>'Enter Adhar  number']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Designation <span class="required">*</span></label>                                        
                                        {!! Form::text('designation',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Designation']) !!}
                                    </div>
									<div class="col-md-6">
                                        <label class="control-label">Is Require To Add Tasks: <span class="required">*</span></label>                            
                                        {!! Form::select('is_add_task',['1'=>'Yes','0'=>'No'],null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                    
                                </div>
								 <?php if(!empty($formObj->id)){ ?>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Balance Paid Leave: <span class="required">*</span></label>  
                                        {!! Form::text('balance_paid_leave',null,['class' => 'form-control', 'data-required' => false]) !!}
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Joining Date:<span class="required">*</span></label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('joining_date',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Select Joining Date','id'=>'start_date']) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
									<div class="col-md-4">
                                        <label class="control-label">DOB:<span class="required">*</span></label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('dob',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Select DOB','id'=>'dob']) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Relieving Date :</label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('relieving_date',null,['class' => 'form-control task_date']) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </div>
                                @if(\Auth::guard('admins')->user()->id == SUPER_ADMIN_ID)
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Salary</label>  
                                        {!! Form::text('salary',null,['class' => 'form-control','placeholder'=>'Enter User Salary']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">&nbsp;</label>  
                                        <div class="md-checkbox">
                                            {!! Form::checkbox('is_salary_generate',1,null,['class' => 'md-check','id'=>"checkbox1"]) !!}
                                            <label for="checkbox1">
                                                <span></span>
                                                <span class="check" style="z-index: 1;"></span>
                                                <span class="box" ></span>
                                                Is add for generate all users salary slip list?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                            </fieldset> 

                            <div class="clearfix">&nbsp;</div>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Bank Details</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Account Name <span class="required">*</span></label>
                                        {!! Form::text('account_nm',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Account Name']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Account Number <span class="required">*</span></label>
                                        {!! Form::text('account_no',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Account Number']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Bank Name <span class="required">*</span></label>
                                        {!! Form::text('bank_nm',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Bank Name']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">IFSC Code<span class="required">*</span></label>
                                        {!! Form::text('ifsc_code',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter IFSC Code']) !!}
                                    </div>
                                </div>
                            </fieldset>
                                
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 20px">
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
<style type="text/css">
fieldset.scheduler-border 
{
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border 
{
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
    width:auto;
    padding:0 10px;
    border-bottom:none;
}
</style>
@section('scripts') 
<script type="text/javascript">
    $(document).ready(function () { 
		$("#user_id").select2({
                placeholder: "Search User Type",
                allowClear: true,
                minimumInputLength: 2,
                width: null
        });
        $('#main-frm').submit(function () {
            
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
<script src="{{ asset("themes/admin/assets/")}}/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
@endsection

