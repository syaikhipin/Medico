@extends('layouts.app')

@section('content')
    {{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <div class="page-content row">
        <!-- Page header -->
        <div class="page-header">
            <div class="page-title">
                <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
            </div>

            <ul class="breadcrumb">
                <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
                <li class="active">{{ $pageTitle }}</li>
            </ul>

        </div>


        <div class="page-content-wrapper m-t">

            <div class="sbox animated fadeInRight">
                <div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
                    <div class="sbox-tools" >
                        <a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
                        @if(Session::get('gid') ==1)
                            <a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
                        @endif
                    </div>
                </div>
                <div class="sbox-content">
                    <div class="toolbar-line ">

                        <a href="{{ URL::to( 'doctor/visited/search/native') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
                        @if($access['is_excel'] ==1)
                            <a href="{{ URL::to('doctor/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
                                <i class="fa fa-download"></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
                        @endif

                    </div>



                    {!! Form::open(array('url'=>'doctor/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
                    <div class="table-responsive" style="min-height:300px;">
                        <table class="table table-striped ">
                            <thead>
                            <tr>
                                <th class="number"> No </th>
                                <th> <input type="checkbox" class="checkall" /></th>
                                <th>Name</th>
                                @foreach ($tableGrid as $t)
                                    <?php if($t['view'] =='1') :
                                        if($t['field']!='isSponsored')
                                        {
                                            $limited = isset($t['limited']) ? $t['limited'] :'';
                                            if(SiteHelpers::filterColumn($limited ))
                                            {
                                                echo '<th align="'.$t['align'].'" width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';
                                            }
                                        }
                                    endif;?>
                                @endforeach
                                <th>Feedback</th>
                                <th>Recommend</th>
                                <th width="70">{{ Lang::get('core.btn_action') }}</th>

                            </tr>
                            </thead>

                            <tbody>
                            <?php $i= 0 ?>
                            @foreach ($rowData as $row)
                                <tr>
                                    <td width="30"> {!!  ++$i  !!}</td>
                                    <td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->DoctorID }}" />  </td>
                                    <td>
                                        {!!  App\Models\doctor::getDetail($row -> UserID)->first_name  !!}
                                        {!!  App\Models\doctor::getDetail($row -> UserID)->last_name  !!}
                                    </td>
                                    @foreach ($tableGrid as $field)
                                        @if($field['view'] =='1')
                                            <?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
                                            @if(SiteHelpers::filterColumn($limited ))

                                                <?php
                                                $conn = (isset($field['conn']) ? $field['conn'] : array() );
                                                $value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn);
                                                ?>
                                                @if($field['field']!='isSponsored')

                                                    <td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
                                                        {!! $value !!}
                                                    </td>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                    <td width="100">
                                        @if(!in_array($row->DoctorID,$nofeedback))
                                        <a href="{{ URL::to( 'doctor/feedback/'.$row->DoctorID) }}" class="btn btn-xs btn-default" onclick="SximoModal(this.href,'Give Feedback'); return false;" ><i class="fa fa-comment"></i></a>
                                        @else
                                            <a href='javascript:void(0)' class="btn btn-xs btn-default disabled"><i class="fa fa-comment"></i></a>
                                        @endif
                                    </td>
                                    <td>

                                        @if(!$recommended->contains($row->DoctorID))
                                            <a href="{{ URL::to('doctor/recommend/'.$row->DoctorID)}}" class="tips btn btn-xs btn-primary" title="Recommend"><i class="fa fa-thumbs-o-up"></i> </a>
                                        @else
                                            <a href='javascript:void(0)' class="tips btn btn-xs btn-primary disabled" title="Recommend"><i class="fa fa-thumbs-o-up"></i> </a>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ URL::to('doctor/show/'.$row->DoctorID.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>
                        <input type="hidden" name="md" value="" />
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

            $('.do-quick-search').click(function(){
                $('#SximoTable').attr('action','{{ URL::to("doctor/visited/multisearch")}}');
                $('#SximoTable').submit();
            });

        });
    </script>
@stop