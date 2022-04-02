
@extends('admin.layouts.app')

@section('breadcrumb')


@stop

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-list"></i>
                            {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="form-body">                            
                            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 
                                <div class="row ">
                                    <div class="col-md-6">
                                        <label class="control-label">Firstname:<span class="required">*</span></label>                                        
                                        {!! Form::text('firstname',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter First Name']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Lastname:<span class="required">*</span></label>                                        
                                        {!! Form::text('lastname',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter Last Name']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Mobile:<span class="required">*</span></label>                                        
                                        {!! Form::text('phone',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter Mobile']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">WhatsApp Number:<span class="required">*</span></label>                                        
                                        {!! Form::text('whats_app_phone',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter WhatsApp Number']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Village Name:<span class="required">*</span></label>                                        
                                        {!! Form::text('village_name',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter Village Name']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Address:<span class="required">*</span></label>                                        
                                        {!! Form::text('address',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter Address']) !!}
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Professional:<span class="required">*</span></label>                                        
                                        {!! Form::text('professional',null,['class' => 'form-control', 'data-required' => true,'placeholder' => 'Enter Professional']) !!}
                                    </div>
                                    <div class="col-md-12">
                                        <input type="submit" value="{{ $buttonText}}" class="btn btn-success pull-right" />
                                    </div>
                                                                        
                                </div>                                        
                                                         
                                <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    
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
                            window.location = '{{ $list_url }}';    
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

