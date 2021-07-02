Hello {{ $employee_name }},

Please check your rota. 
<table class="table table-striped custom-table datatable" id="table" width="100%">
    <tbody>
    <tr>
        <td>Date</td>
        <td>Start Date & Time</td>
        <td>End Date & Time</td>
        <td>Break Time</td>
        <td>Location</td>
   </tr>
        @for($d = Carbon\Carbon::parse(Carbon\Carbon::today()); $d->lte(Carbon\Carbon::parse(Carbon\Carbon::today()->addDay(7))); $d->addDay()) 
            @php 
                $rotas = \App\Rota::with('branch')->where('user_id',$employee_id)->where('start_date',$d)->get();
            @endphp
        
            @if($rotas->count()!=0)
                @foreach($rotas as $rota)
                <tr>
                    <td>
                        {!!$d->format('Y-m-d')!!}
                    </td>
                    <td>
                        {!!$rota->start_date!!} {!!Carbon\Carbon::parse($rota->start_time)->format('H:i')!!}
                    </td>
                    <td>
                        {!!$rota->end_date!!} {!!Carbon\Carbon::parse($rota->end_time)->format('H:i')!!}
                    </td>
                    <td>
                        {!!$rota->break_time!!} minutes
                    </td>
                    <td class=" col-md-3">
                        @if($rota->remotely_work=='No')
                            {!!$rota->branch->name!!}
                        @else
                            {!!'Remotely Work'!!}
                        @endif
                    </td>
								</tr>  
                @endforeach
            @else
            <tr>
                <td>
                    {!!$d->format('Y-m-d')!!}
                </td>
                <td colspan="4">{!!'No Scheduled'!!}</td>
						</tr>
            @endif
        
        @endfor
    </tbody>
</table>       


Thanks,<br>
{!! config('app.name') !!}