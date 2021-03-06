@section('javascript-document-ready')
    @parent
    var today = new Date();

    $('#datepicker_end_date input').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "6,0",
        weekStart: 1,
        startDate: today
    });
@stop

<div class="form-group">
    <label for="{{ $name }}">Date End</label>
    <div class="input-group input-append date" id="datepicker_end_date" data-date-format="dd-mm-yyyy">
        <input name="endDate" id="endDate" class="form-control" @if(!empty($dateTime['dateEnd'])) value="{{ $dateTime['dateEnd'] }}" @endif type="text" placeholder="Select date" value="" readonly="readonly" aria-describedby="endDate" aria-label="Enter end date">
        <div class="input-group-append">
            <span class="input-group-text" id="date-addon-end"><i class="far fa-calendar-alt"></i></span>
        </div>
    </div>
</div>
