{!! Form::open(array('url'=>'doctor/feedback/'.$row->DoctorID, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>'', 'id'=> 'contactsFormAjax')) !!}

<div class="col-md-12">
    <fieldset>

        <div class="form-group" >
            <label for="Feedback" class=" control-label col-md-3 text-left">
                Feedback
            </label>
            <div class="col-md-9">
                {!! Form::textarea('Feedback', null,array('class'=>'form-control editor', 'placeholder'=>'', 'required'=>'true' ,'rows' => 4 )) !!}
            </div>
        </div>




    </fieldset>
</div>
<div style="clear:both"></div>

<div class="form-group">
    <label class="col-sm-4 text-right">&nbsp;</label>
    <div class="col-sm-8">
        <button type="submit" class="btn btn-primary btn-sm "><i class="fa  fa-save "></i>  Submit </button>
        <button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
    </div>
</div>
{!! Form::close() !!}

