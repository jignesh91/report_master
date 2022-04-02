<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search"></i>Advance Search 
        </div>
        <div class="tools">
            <a href="javascript:;" class="expand"> </a>
        </div>                    
    </div>
    <div class="portlet-body" style="display: none">  
        <form id="search-frm">
            <div class="row">
                @if(!empty($users))
                <div class="col-md-4">
                    <label class="control-label">User Name</label>
                    {!! Form::select('search_name', [''=>'Search User'] + $users, Request::get("search_name"), ['class' => 'form-control','id'=>'user_id']) !!}
                </div>
                @endif
                <div class="col-md-4">
                    <label class="control-label">Date Range</label>
                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                        <input type="text" class="form-control" value="{{ \Request::get('search_start_date') }}" name="search_start_date" id="start_date" placeholder="Start Date" autocomplete="off">
                        <span class="input-group-addon"> To </span>
                        <input type="text" class="form-control" value="{{ \Request::get('search_end_date') }}" name="search_end_date" id="end_date" placeholder="End Date" autocomplete="off">
                    </div>
                </div>
                &nbsp;
                <div class="row" align="center">
                    <input type="submit" class="btn blue mTop25" value="Search"/>
                    &nbsp;
                    <a href="{{ $list_url }}" class="btn red mTop25">Reset</a>
                </div>
            </div>                
        </form>
    </div>
</div>