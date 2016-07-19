{!! Form::open(array('url'=> url('clinic/sponsor/'.$row->ClinicID), 'class'=>'form-signup','id' => 'subscribe-form','method' =>'post')) !!}
<div class="payment-errors alert alert-danger" style="display: none;"></div>

<div class="form-group has-feedback">
    <label> Plan </label>


    <select name="plan" class="select2">
        <option value="">--Select--</option>
        @foreach($plans as $plan)
            <option value="{{$plan->id}}">{{ $plan->amount }}$ per {{$plan->duration}} Month</option>
        @endforeach
    </select>
</div>
<input type="hidden" value="{{ \Auth::user()->id }}" name="userid">
<div class="form-group has-feedback">
    <label>Choose Card</label>
    <select name="card" class="select2">
        <option value="">--Select--</option>
        @foreach($cards as $card)
            <option value="{{ $card->id }}">{{ $card->last4 }}</option>
        @endforeach
    </select>
</div>

<div class="form-group right">
    <a href="{{ url('cards/update?continue=clinic') }}">Add Card</a>
</div>

<div class="row form-actions">
    <div class="col-sm-12">
        <button  type="submit" style="width:100%;" class="btn btn-primary pull-right"> Confirm	</button>
    </div>
</div>
{!! Form::close() !!}


<script>
    $(".select2").select2({ width:"98%"});
</script>