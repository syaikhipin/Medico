{!! Form::open(array('url'=>'report/mail/'.$row->ReportID, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'', 'id'=> 'contactsFormAjax')) !!}

                <div class="col-md-12">
                    <fieldset>
                        <div class="form-group" >
                            <label for="to" class=" control-label col-md-3 text-left">
                                Doctor
                            </label>
                            <div class="col-md-9">
                                <select name="DoctorID" class="form-control">
                                    <option value="">-- Select --</option>
                                    @foreach($doctors as $k=>$v)
                                       <option value="{!! $v !!}">{!! \SiteHelpers::gridDisplayView($v,'UserID','1:tb_users:id:first_name|last_name') !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="Message" class=" control-label col-md-3 text-left">
                                Message
                            </label>
                            <div class="col-md-9">
                                {!! Form::textarea('message', null,array('class'=>'form-control editor', 'placeholder'=>'', 'required'=>'true'  )) !!}
                            </div>
                        </div>




                    </fieldset>
                </div>
                <div style="clear:both"></div>

                <div class="form-group">
                    <label class="col-sm-4 text-right">&nbsp;</label>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  Mail </button>
                        <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
                    </div>
                </div>
                {!! Form::close() !!}

